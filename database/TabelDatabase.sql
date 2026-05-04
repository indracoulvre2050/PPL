// NutriFlow — ERD 3NF (No Auth, with Anomaly Notifications)
// Kelompok C9 — Universitas Jember 2025

// ── USER_LOGIN ────────────────────────────────────────────────
Table users {
  id                bigint       [primary key, increment, not null]
  name              varchar(255) [not null]
  email             varchar(255) [not null, unique]
  password          varchar(255) [not null]
  remember_token    varchar(100)
  created_at        timestamp    [default: `CURRENT_TIMESTAMP`]
  updated_at        timestamp    [default: `CURRENT_TIMESTAMP`]
}

// ── PERANGKAT IOT ─────────────────────────────────────────────

Table iot_devices {
  id               bigint      [primary key, increment, not null]
  device_name      varchar(100) [not null]
  device_token     varchar(255) [not null, unique, note: 'token autentikasi perangkat IoT ke backend']
  mac_address      varchar(17) [unique]
  firmware_version varchar(20)
  is_online        boolean     [not null, default: 0]
  last_seen_at     timestamp
  created_at       timestamp   [not null, default: `CURRENT_TIMESTAMP`]
  updated_at       timestamp   [not null, default: `CURRENT_TIMESTAMP`]
}

// ── SENSOR ────────────────────────────────────────────────────

Table sensor_types {
  id          bigint      [primary key, increment, not null]
  name        varchar(50) [not null, unique, note: 'ppm | suhu | ph | cahaya | kelembaban']
  unit        varchar(20) [not null]
  description text
}

Table sensor_readings {
  id             bigint         [primary key, increment, not null]
  device_id      bigint         [not null]
  sensor_type_id bigint         [not null]
  value          decimal(10, 4) [not null]
  recorded_at    timestamp      [not null, default: `CURRENT_TIMESTAMP`]

  Indexes {
    (device_id, sensor_type_id, recorded_at)
    (recorded_at)
  }
}

Table sensor_latest {
  id                bigint         [primary key, increment, not null]
  device_id         bigint         [not null]
  sensor_type_id    bigint         [not null]
  sensor_reading_id bigint         [not null]
  value             decimal(10, 4) [not null]
  updated_at        timestamp      [not null, default: `CURRENT_TIMESTAMP`]

  Indexes {
    (device_id, sensor_type_id) [unique]
  }
}

// ── THRESHOLD & AKTUATOR ──────────────────────────────────────

Table thresholds {
  id             bigint         [primary key, increment, not null]
  device_id      bigint         [not null]
  sensor_type_id bigint         [not null]
  min_value      decimal(10, 4) [not null]
  max_value      decimal(10, 4) [not null]
  updated_at     timestamp      [not null, default: `CURRENT_TIMESTAMP`]

  Indexes {
    (device_id, sensor_type_id) [unique]
  }
}

Table actuators {
  id            bigint      [primary key, increment, not null]
  device_id     bigint      [not null]
  actuator_code varchar(50) [not null, unique]
  type          varchar(50) [not null, note: 'pompa_a | pompa_b | relay_lampu | paranet']
  is_active     boolean     [not null, default: 0]
  updated_at    timestamp   [not null, default: `CURRENT_TIMESTAMP`]
}

// ── LOG ANOMALI ───────────────────────────────────────────────

Table anomaly_logs {
  id                bigint         [primary key, increment, not null]
  sensor_reading_id bigint         [not null, note: 'bacaan sensor yang memicu anomali']
  sensor_type_id    bigint         [not null]
  device_id         bigint         [not null]
  value             decimal(10, 4) [not null, note: 'nilai sensor saat anomali terjadi']
  min_value         decimal(10, 4) [not null, note: 'snapshot threshold min saat kejadian']
  max_value         decimal(10, 4) [not null, note: 'snapshot threshold max saat kejadian']
  occurred_at       timestamp      [not null, default: `CURRENT_TIMESTAMP`]

  Indexes {
    (device_id, occurred_at)
    (sensor_type_id, occurred_at)
  }
}

// ── LOG AKTUATOR ──────────────────────────────────────────────

Table actuator_logs {
  id                bigint      [primary key, increment, not null]
  actuator_id       bigint      [not null]
  anomaly_log_id    bigint      [null, note: 'null jika trigger_mode = manual']
  trigger_mode      varchar(20) [not null, note: 'otomatis | manual']
  action            varchar(20) [not null, note: 'on | off']
  executed_at       timestamp   [not null, default: `CURRENT_TIMESTAMP`]

  Indexes {
    (actuator_id, executed_at)
  }
}

// ── NOTIFIKASI ────────────────────────────────────────────────

Table notifications {
  id             bigint      [primary key, increment, not null]
  anomaly_log_id bigint      [null, note: 'null jika notifikasi dari aktuator manual']
  actuator_log_id bigint     [null, note: 'null jika notifikasi murni anomali tanpa aktuator']
  message        text        [not null]
  is_read        boolean     [not null, default: 0]
  created_at     timestamp   [not null, default: `CURRENT_TIMESTAMP`]

  Indexes {
    (is_read)
    (created_at)
  }
}

// ── RELASI ────────────────────────────────────────────────────

Ref: sensor_readings.device_id          > iot_devices.id        [delete: cascade,  update: cascade]
Ref: sensor_readings.sensor_type_id     > sensor_types.id       [delete: restrict, update: cascade]
Ref: sensor_latest.device_id            > iot_devices.id        [delete: cascade,  update: cascade]
Ref: sensor_latest.sensor_type_id       > sensor_types.id       [delete: restrict, update: cascade]
Ref: sensor_latest.sensor_reading_id    > sensor_readings.id    [delete: restrict, update: cascade]
Ref: thresholds.device_id               > iot_devices.id        [delete: cascade,  update: cascade]
Ref: thresholds.sensor_type_id          > sensor_types.id       [delete: restrict, update: cascade]
Ref: actuators.device_id                > iot_devices.id        [delete: cascade,  update: cascade]
Ref: anomaly_logs.sensor_reading_id     > sensor_readings.id    [delete: restrict, update: cascade]
Ref: anomaly_logs.sensor_type_id        > sensor_types.id       [delete: restrict, update: cascade]
Ref: anomaly_logs.device_id             > iot_devices.id        [delete: cascade,  update: cascade]
Ref: actuator_logs.actuator_id          > actuators.id          [delete: cascade,  update: cascade]
Ref: actuator_logs.anomaly_log_id       > anomaly_logs.id       [delete: set null, update: cascade]
Ref: notifications.anomaly_log_id       > anomaly_logs.id       [delete: set null, update: cascade]
Ref: notifications.actuator_log_id      > actuator_logs.id      [delete: set null, update: cascade]
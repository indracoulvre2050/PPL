<?php

namespace App\Http\Controllers;

use App\Models\User;
use NotificationsChannels\WebPush\WebPushMessage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function terimaDataSensor(Request $request)
    {
        $ph = $request->input('ph');
        $ppm = $request->input('ppm');

        DB::table('sensor_latest')->updateOrInsert(
            ['sensor_type_id' => 3, 'device_id' => 1],
            ['value' => $ph, 'updated_at' => Carbon::now()]
        );
        DB::table('sensor_latest')->updateOrInsert(
            ['sensor_type_id' => 1, 'device_id' => 1],
            ['value' => $ppm, 'updated_at' => Carbon::now()]
        );

        return response()->json(['status' => 'sukses', 'pesan' => 'Data berhasil disimpan']);
    }
    
    // Fetch data terbaru
    public function ambilDataTerbaru()
    {
        $ph = DB::table('sensor_latest')
            ->join('sensor_types', 'sensor_latest.sensor_type_id', '=', 'sensor_types.id')
            ->where('sensor_types.name', 'ph')
            ->value('sensor_latest.value') ?? 0;

        $tds = DB::table('sensor_latest')
            ->join('sensor_types', 'sensor_latest.sensor_type_id', '=', 'sensor_types.id')
            ->where('sensor_types.name', 'ppm')
            ->value('sensor_latest.value') ?? 0;
        
         // Ambang Batas
        $ambangBatasDB = DB::table('ambang_batas')->where('id', 1)->first();
        $ambangBatas = [
            'ph_min' => $ambangBatasDB->ph_min ?? 5.5, 'ph_max' => $ambangBatasDB->ph_max ?? 6.5,
            'tds_min' => $ambangBatasDB->tds_min ?? 900, 'tds_max' => $ambangBatasDB->tds_max ?? 1200,
        ];

        // Hitung Stabilitas
        $stabilitas = [
            'ph_label' => 'Kritis', 'ph_color' => 'text-red-600', 'ph_icon' => 'ph-warning-circle',
            'tds_label' => 'Kritis', 'tds_color' => 'text-red-600', 'tds_icon' => 'ph-warning-circle',
        ];

        // Logika pH
        if ($ph >= $ambangBatas['ph_min'] && $ph <= $ambangBatas['ph_max']) {
            $phMid = ($ambangBatas['ph_min'] + $ambangBatas['ph_max']) / 2;
            $phMaxDeviasi = ($ambangBatas['ph_max'] - $ambangBatas['ph_min']) / 2;
            $phPersen = $phMaxDeviasi == 0 ? 100 : 100 - ((abs($ph - $phMid) / $phMaxDeviasi) * 50);
            
            if ($phPersen >= 80) { $stabilitas['ph_label'] = 'Sangat Stabil'; $stabilitas['ph_color'] = 'text-[#2e7d32]'; $stabilitas['ph_icon'] = 'ph-check-circle'; } 
            elseif ($phPersen >= 60) { $stabilitas['ph_label'] = 'Stabil'; $stabilitas['ph_color'] = 'text-[#388e3c]'; $stabilitas['ph_icon'] = 'ph-check-circle'; } 
            else { $stabilitas['ph_label'] = 'Fluktuatif'; $stabilitas['ph_color'] = 'text-orange-500'; $stabilitas['ph_icon'] = 'ph-warning'; }
        }

        // Logika TDS
        if ($tds >= $ambangBatas['tds_min'] && $tds <= $ambangBatas['tds_max']) {
            $tdsMid = ($ambangBatas['tds_min'] + $ambangBatas['tds_max']) / 2;
            $tdsMaxDeviasi = ($ambangBatas['tds_max'] - $ambangBatas['tds_min']) / 2;
            $tdsPersen = $tdsMaxDeviasi == 0 ? 100 : 100 - ((abs($tds - $tdsMid) / $tdsMaxDeviasi) * 50);
            
            if ($tdsPersen >= 80) { $stabilitas['tds_label'] = 'Sangat Optimal'; $stabilitas['tds_color'] = 'text-[#2e7d32]'; $stabilitas['tds_icon'] = 'ph-check-circle'; } 
            elseif ($tdsPersen >= 60) { $stabilitas['tds_label'] = 'Optimal'; $stabilitas['tds_color'] = 'text-[#388e3c]'; $stabilitas['tds_icon'] = 'ph-check-circle'; } 
            else { $stabilitas['tds_label'] = 'Fluktuatif'; $stabilitas['tds_color'] = 'text-orange-500'; $stabilitas['tds_icon'] = 'ph-warning'; }
        }

        // Logika Pengecekan Status
        $waktuTerakhir = DB::table('sensor_latest')->max('updated_at');
        $statusSistem = [
            'teks' => 'Sistem Terputus', 
            'bg_warna' => 'bg-red-50 border-red-200', 
            'teks_warna' => 'text-red-700', 
            'ikon' => 'ph-plugs text-red-600'
            ];
        $status = [
            'label' => 'Sistem Nonaktif',
            'bg'    => 'bg-red-100',
            'teks  ' => 'text-red-700',
            'ikon'  => 'ph-warning-circle'
        ];

        if ($waktuTerakhir) {
            $selisihMenit = \Carbon\Carbon::parse($waktuTerakhir)->diffInMinutes(\Carbon\Carbon::now());
            if ($selisihMenit <= 5) {
                $status = [
                    'label' => 'Sistem Aktif',
                    'bg'    => 'bg-[#e4f5e1]',
                    'teks'  => 'text-[#2e7d32]',
                    'ikon'  => 'ph-check-circle'
                ];
            }
            if ($stabilitas['ph_label'] == 'Kritis' || $stabilitas['tds_label'] == 'Kritis') {
                $statusSistem = ['teks' => 'Perlu Perhatian', 'bg_warna' => 'bg-orange-50 border-orange-200', 'teks_warna' => 'text-orange-700', 'ikon' => 'ph-warning text-orange-600'];
            } else {
                $statusSistem = ['teks' => 'Sistem Berjalan Normal', 'bg_warna' => 'bg-[#f4faf2] border-[#e2ebd9]', 'teks_warna' => 'text-[#2e7d32]', 'ikon' => 'ph-check-circle text-[#388e3c]'];
            }
        }

        return response()->json([
            'ph' => number_format((float)$ph, 1, '.', ''),
            'tds' => number_format((float)$tds, 0, ',', '.'),
            'stabilitas' => $stabilitas,
            'statusSistem' => $statusSistem,
            'status' => $status
        ]);
    }
    
    public function kirimBatasKeAlat()
    {
        $batas = DB::table('ambang_batas')->where('id', 1)->first();
        
        return response($batas->ph_min . "," . $batas->ph_max . "," . $batas->tds_min);
    }

    public function kirimSensor(Request $request)
    {

    $request->validate([
        'ph' => 'required|numeric',
        'tds' => 'required|numeric',
        'relay_mixer' => 'required|integer',   
        'relay_ph_up' => 'required|integer',
        'relay_nutrisi' => 'required|integer',
    ]);

    $ph = $request->ph;
    $tds = $request->tds;
    $deviceId = 1;

    // 1. UPDATE SENSOR LATEST
    DB::table('sensor_latest')->where('sensor_type_id', 3)->update(['value' => $ph, 'updated_at' => now()]);
    DB::table('sensor_latest')->where('sensor_type_id', 1)->update(['value' => $tds, 'updated_at' => now()]);

    $batas = DB::table('ambang_batas')->orderBy('id', 'desc')->first();
    $anomalyLogId = null;
    $pesanNotif = "";

    // 2. CEK & CATAT ANOMALI
    if ($ph < $batas->ph_min || $ph > $batas->ph_max) {
        $anomalyLogId = DB::table('anomaly_logs')->insertGetId([
            'sensor_type_id' => 3,
            'device_id' => $deviceId,
            'value' => $ph,
            'min_value' => $batas->ph_min,
            'max_value' => $batas->ph_max,
            'occurred_at' => now()
        ]);
        $pesanNotif = "Peringatan! pH air saat ini: " . $ph;
    } 
    elseif ($tds < $batas->tds_min || $tds > $batas->tds_max) {
        $anomalyLogId = DB::table('anomaly_logs')->insertGetId([
            'sensor_type_id' => 1, 
            'device_id' => $deviceId,
            'value' => $tds,
            'min_value' => $batas->tds_min,
            'max_value' => $batas->tds_max,
            'occurred_at' => now()
        ]);
        $pesanNotif = "Peringatan! Kadar Nutrisi (PPM) saat ini: " . $tds;
    }

    // 3. CATAT AKTUATOR
    $actuatorLogId = null;

    if ($request->relay_nutrisi == 1) {
        $actuatorLogId = DB::table('actuator_logs')->insertGetId([
            'actuator_id' => 1, 
            'anomaly_log_id' => $anomalyLogId,
            'trigger_mode' => 'Otomatis',
            'action' => 'Menyala',
            'executed_at' => now()
        ]);
    }

    if ($request->relay_ph_up == 1) {
        $actuatorLogId = DB::table('actuator_logs')->insertGetId([
            'actuator_id' => 2, 
            'anomaly_log_id' => $anomalyLogId,
            'trigger_mode' => 'Otomatis',
            'action' => 'Menyala',
            'executed_at' => now()
        ]);
    }

    if ($request->relay_mixer == 1) {
        $actuatorLogId = DB::table('actuator_logs')->insertGetId([
            'actuator_id' => 3, 
            'anomaly_log_id' => $anomalyLogId,
            'trigger_mode' => 'Otomatis',
            'action' => 'Menyala',
            'executed_at' => now()
        ]);
    }

    // C. SIMPAN NOTIFIKASI & KIRIM WEB
    if ($anomalyLogId) {
        DB::table('notifications')->insert([
            'anomaly_log_id' => $anomalyLogId,
            'actuator_log_id' => $actuatorLogId,
            'message' => $pesanNotif,
            'is_read' => 0,
            'created_at' => now()
        ]);

        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new \NotificationChannels\WebPush\WebPushNotification(
                "Peringatan Sistem",
                [
                    'body' => $pesanNotif,
                    'icon' => '/assets/Logo.png',
                    'data' => '/dashboard'
                ]
            ));
        }
    }

    return response()->json(['success' => true, 'message' => 'Data tersimpan di struktur database asli.']);
    }
}
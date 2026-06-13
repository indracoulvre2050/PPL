<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $namaDepan = explode(' ', $user->name)[0];

        // Data sensor terbaru
        $dataNutrisi = [
            'ph' => 0, 
            'tds' => 0,
        ];

        try {
            $phDB = DB::table('sensor_latest')
                ->join('sensor_types', 'sensor_latest.sensor_type_id', '=', 'sensor_types.id')
                ->where('sensor_types.id', 3)
                ->value('sensor_latest.value');
            if ($phDB !== null) $dataNutrisi['ph'] = $phDB;

            $tdsDB = DB::table('sensor_latest')
                ->join('sensor_types', 'sensor_latest.sensor_type_id', '=', 'sensor_types.id')
                ->where('sensor_types.id', 1)
                ->value('sensor_latest.value');
            if ($tdsDB !== null) $dataNutrisi['tds'] = $tdsDB;
        } catch (\Exception $e) {}

        // Log Anomali Terakhir
        $logAnomali = DB::table('anomaly_logs')
            ->join('sensor_types', 'anomaly_logs.sensor_type_id', '=', 'sensor_types.id')
            ->select('anomaly_logs.*', 'sensor_types.name as nama_sensor')
            ->orderBy('occurred_at', 'desc')
            ->limit(5)
            ->get();

        $logAktuator = DB::table('actuator_logs')
            ->join('actuators', 'actuator_logs.actuator_id', '=', 'actuators.id')
            ->select('actuator_logs.*', 'actuators.type as nama_alat')
            ->orderBy('executed_at', 'desc')
            ->limit(5)
            ->get();

        $notifikasi = DB::table('notifications')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Ambang Batas di Database
        $ambangBatasDB = DB::table('ambang_batas')->where('id', 1)->first();
        $ambangBatas = [
            'ph_min' => $ambangBatasDB->ph_min ?? 5.5, 'ph_max' => $ambangBatasDB->ph_max ?? 6.5,
            'tds_min' => $ambangBatasDB->tds_min ?? 900, 'tds_max' => $ambangBatasDB->tds_max ?? 1200,
        ];

        // Stabilitas pH dan TDS
        $stabilitas = [
            'ph_label' => 'Kritis', 'ph_color' => 'text-red-600', 'ph_icon' => 'ph-warning-circle',
            'tds_label' => 'Kritis', 'tds_color' => 'text-red-600', 'tds_icon' => 'ph-warning-circle',
        ];

        // Logika pH
        $phCurrent = $dataNutrisi['ph'];
        if ($phCurrent >= $ambangBatas['ph_min'] && $phCurrent <= $ambangBatas['ph_max']) {
            $phMid = ($ambangBatas['ph_min'] + $ambangBatas['ph_max']) / 2;
            $phPersen = 100 - ((abs($phCurrent - $phMid) / (($ambangBatas['ph_max'] - $ambangBatas['ph_min']) / 2)) * 50);
            if ($phPersen >= 80) { $stabilitas['ph_label'] = 'Sangat Stabil'; $stabilitas['ph_color'] = 'text-[#2e7d32]'; $stabilitas['ph_icon'] = 'ph-check-circle'; } 
            elseif ($phPersen >= 60) { $stabilitas['ph_label'] = 'Stabil'; $stabilitas['ph_color'] = 'text-[#388e3c]'; $stabilitas['ph_icon'] = 'ph-check-circle'; } 
            else { $stabilitas['ph_label'] = 'Fluktuatif'; $stabilitas['ph_color'] = 'text-orange-500'; $stabilitas['ph_icon'] = 'ph-warning'; }
        }

        // Logika TDS
        $tdsCurrent = $dataNutrisi['tds'];
        if ($tdsCurrent >= $ambangBatas['tds_min'] && $tdsCurrent <= $ambangBatas['tds_max']) {
            $tdsMid = ($ambangBatas['tds_min'] + $ambangBatas['tds_max']) / 2;
            $tdsPersen = 100 - ((abs($tdsCurrent - $tdsMid) / (($ambangBatas['tds_max'] - $ambangBatas['tds_min']) / 2)) * 50);
            if ($tdsPersen >= 80) { $stabilitas['tds_label'] = 'Sangat Optimal'; $stabilitas['tds_color'] = 'text-[#2e7d32]'; $stabilitas['tds_icon'] = 'ph-check-circle'; } 
            elseif ($tdsPersen >= 60) { $stabilitas['tds_label'] = 'Optimal'; $stabilitas['tds_color'] = 'text-[#388e3c]'; $stabilitas['tds_icon'] = 'ph-check-circle'; } 
            else { $stabilitas['tds_label'] = 'Fluktuatif'; $stabilitas['tds_color'] = 'text-orange-500'; $stabilitas['tds_icon'] = 'ph-warning'; }
        }

        // Status Alat
        $waktuTerakhir = DB::table('sensor_latest')->max('updated_at');
        $statusSistem = [
            'teks' => 'Sistem Terputus', 'bg_warna' => 'bg-red-50 border-red-200', 'teks_warna' => 'text-red-700', 'ikon' => 'ph-plugs text-red-600'
        ];

        if ($waktuTerakhir && \Carbon\Carbon::parse($waktuTerakhir)->diffInMinutes(\Carbon\Carbon::now()) <= 5) {
            if ($stabilitas['ph_label'] == 'Kritis' || $stabilitas['tds_label'] == 'Kritis') {
                $statusSistem = ['teks' => 'Perlu Perhatian (Sensor Kritis)', 'bg_warna' => 'bg-orange-50 border-orange-200', 'teks_warna' => 'text-orange-700', 'ikon' => 'ph-warning text-orange-600'];
            } else {
                $statusSistem = ['teks' => 'Sistem Berjalan Normal', 'bg_warna' => 'bg-[#f4faf2] border-[#e2ebd9]', 'teks_warna' => 'text-[#2e7d32]', 'ikon' => 'ph-check-circle text-[#388e3c]'];
            }
        }

        return view('dashboard', compact('namaDepan', 'dataNutrisi', 'logAnomali', 'logAktuator', 'notifikasi', 'stabilitas', 'statusSistem'));
    }
            
    // Halaman Notifikasi
    public function notifikasi()
    {
        // Ambil data notifikasi
        $semuaNotifikasi = collect();
        try {
            $semuaNotifikasi = DB::table('notifications')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}
        return view('notifikasi', compact('semuaNotifikasi'));
    }

    // Halaman Sensor
    public function sensor()
    {
        $user = Auth::user();
        $namaDepan = explode(' ', $user->name)[0];

        $dataNutrisi = ['ph' => 0, 'tds' => 0];
        try {
            $phDB = DB::table('sensor_latest')
                ->join('sensor_types', 'sensor_latest.sensor_type_id', '=', 'sensor_types.id')
                ->where('sensor_types.name', 'ph')
                ->value('sensor_latest.value');
            if ($phDB !== null) $dataNutrisi['ph'] = $phDB;

            $tdsDB = DB::table('sensor_latest')
                ->join('sensor_types', 'sensor_latest.sensor_type_id', '=', 'sensor_types.id')
                ->where('sensor_types.name', 'ppm')
                ->value('sensor_latest.value');
            if ($tdsDB !== null) $dataNutrisi['tds'] = $tdsDB;
        } catch (\Exception $e) {}

        $logAnomali = DB::table('anomaly_logs')
            ->join('sensor_types', 'anomaly_logs.sensor_type_id', '=', 'sensor_types.id')
            ->select('anomaly_logs.*', 'sensor_types.name as nama_sensor')
            ->orderBy('occurred_at', 'desc')
            ->limit(5)
            ->get();

        $logAktuator = DB::table('actuator_logs')
            ->join('actuators', 'actuator_logs.actuator_id', '=', 'actuators.id')
            ->select('actuator_logs.*', 'actuators.type as nama_alat')
            ->orderBy('executed_at', 'desc')
            ->limit(5)
            ->get();

        // Ambil data ambang batas
        $ambangBatasDB = DB::table('ambang_batas')->where('id', 1)->first();
        $ambangBatas = [
            'ph_min' => $ambangBatasDB->ph_min ?? 5.5,
            'ph_max' => $ambangBatasDB->ph_max ?? 6.5,
            'tds_min' => $ambangBatasDB->tds_min ?? 900,
            'tds_max' => $ambangBatasDB->tds_max ?? 1200,
        ];

        // Hitung Stabilitas
        $stabilitas = [
            'ph_persen' => 0, 'ph_label' => 'Kritis', 'ph_color' => 'text-red-500', 'ph_bg' => 'bg-red-500',
            'tds_persen' => 0, 'tds_label' => 'Kritis', 'tds_color' => 'text-red-500', 'tds_bg' => 'bg-red-500',
        ];

        // Logika Stabilitas pH
        $phCurrent = $dataNutrisi['ph'];
        $phMin = $ambangBatas['ph_min'];
        $phMax = $ambangBatas['ph_max'];
        
        if ($phCurrent >= $phMin && $phCurrent <= $phMax) {
            $phMid = ($phMin + $phMax) / 2;
            $phDeviasi = abs($phCurrent - $phMid);
            $phMaxDeviasi = ($phMax - $phMin) / 2;
            $stabilitas['ph_persen'] = 100 - (($phDeviasi / $phMaxDeviasi) * 50); 
        } else {
            $stabilitas['ph_persen'] = 15; 
        }

        // Tentukan Label & Warna pH
        if ($stabilitas['ph_persen'] >= 80) {
            $stabilitas['ph_label'] = 'Sangat Stabil'; $stabilitas['ph_color'] = 'text-[#2e7d32]'; $stabilitas['ph_bg'] = 'bg-[#2e7d32]';
        } elseif ($stabilitas['ph_persen'] >= 60) {
            $stabilitas['ph_label'] = 'Cukup Stabil'; $stabilitas['ph_color'] = 'text-[#388e3c]'; $stabilitas['ph_bg'] = 'bg-[#388e3c]';
        } elseif ($stabilitas['ph_persen'] >= 40) {
            $stabilitas['ph_label'] = 'Fluktuatif'; $stabilitas['ph_color'] = 'text-[#f57c00]'; $stabilitas['ph_bg'] = 'bg-[#f57c00]';
        } else {
            $stabilitas['ph_label'] = 'Kritis'; $stabilitas['ph_color'] = 'text-[#d32f2f]'; $stabilitas['ph_bg'] = 'bg-[#d32f2f]';
        }

        // Logika Stabilitas TDS
        $tdsCurrent = $dataNutrisi['tds'];
        $tdsMin = $ambangBatas['tds_min'];
        $tdsMax = $ambangBatas['tds_max'];

        if ($tdsCurrent >= $tdsMin && $tdsCurrent <= $tdsMax) {
            $tdsMid = ($tdsMin + $tdsMax) / 2;
            $tdsDeviasi = abs($tdsCurrent - $tdsMid);
            $tdsMaxDeviasi = ($tdsMax - $tdsMin) / 2;
            $stabilitas['tds_persen'] = 100 - (($tdsDeviasi / $tdsMaxDeviasi) * 50); 
        } else {
            $stabilitas['tds_persen'] = 15; 
        }

        // Tentukan Label & Warna TDS
        if ($stabilitas['tds_persen'] >= 80) {
            $stabilitas['tds_label'] = 'Konsisten'; $stabilitas['tds_color'] = 'text-[#6d4c41]'; $stabilitas['tds_bg'] = 'bg-[#6d4c41]';
        } elseif ($stabilitas['tds_persen'] >= 60) {
            $stabilitas['tds_label'] = 'Cukup Stabil'; $stabilitas['tds_color'] = 'text-[#8d6e63]'; $stabilitas['tds_bg'] = 'bg-[#8d6e63]';
        } elseif ($stabilitas['tds_persen'] >= 40) {
            $stabilitas['tds_label'] = 'Fluktuatif'; $stabilitas['tds_color'] = 'text-[#f57c00]'; $stabilitas['tds_bg'] = 'bg-[#f57c00]';
        } else {
            $stabilitas['tds_label'] = 'Kritis'; $stabilitas['tds_color'] = 'text-[#d32f2f]'; $stabilitas['tds_bg'] = 'bg-[#d32f2f]';
        }
        
        // Status Sistem (Aktif / Nonaktif)
        $waktuTerakhir = DB::table('sensor_latest')->max('updated_at');
        
        // Status default (Offline)
        $statusAlat = [
            'label' => 'Sistem Nonaktif',
            'bg' => 'bg-red-100',
            'teks' => 'text-red-700',
            'ikon' => 'ph-warning-circle'
        ];

        if ($waktuTerakhir) {
            $selisihMenit = \Carbon\Carbon::parse($waktuTerakhir)->diffInMinutes(\Carbon\Carbon::now());
            
            if ($selisihMenit <= 5) {
                $statusAlat = [
                    'label' => 'Sistem Aktif',
                    'bg' => 'bg-[#e4f5e1]', 
                    'teks' => 'text-[#2e7d32]',
                    'ikon' => 'ph-check-circle'
                ];
            }
        }

        return view('sensor', compact('namaDepan', 'dataNutrisi', 'logAktuator', 'logAnomali', 'ambangBatas', 'stabilitas', 'statusAlat'));
    }

    // Menympan pengaturan
    public function updateAmbangBatas(Request $request)
    {
        $request->validate([
            'ph_min' => 'required|numeric',
            'ph_max' => 'required|numeric|gte:ph_min', 
            'tds_min' => 'required|numeric',
            'tds_max' => 'required|numeric|gte:tds_min',
        ]);
        
        // Update data ke database
        DB::table('ambang_batas')->where('id', 1)->update([
            'ph_min' => $request->ph_min,
            'ph_max' => $request->ph_max,
            'tds_min' => $request->tds_min,
            'tds_max' => $request->tds_max,
            'updated_at' => now()
        ]);

        return back()->with('success', 'Pengaturan ambang batas berhasil disimpan ke sistem pusat!');
    }

    // Menyimpan perangkat yang mengaktifkan notifikasi
    public function simpanSubscription(Request $request)
    {
        $request->validate([
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);

        $user = Auth::user();
        
        $user->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth']
        );

        return response()->json(['success' => true, 'message' => 'Perangkat berhasil didaftarkan.']);
    }
}
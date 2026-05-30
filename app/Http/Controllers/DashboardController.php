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

        // 1. Ambil data sensor terbaru
        $dataNutrisi = [
            'ph' => 0, 
            'tds' => 0,
        ];

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

        // 2. Ambil Log Anomali Terakhir
        $logAnomali = collect();
        try {
            $logAnomali = DB::table('anomaly_logs')
                ->orderBy('occurred_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {}

        $notifikasi = collect();
        try {
            $notifikasi = DB::table('notifications')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        } catch (\Exception $e) {}
        
        return view('dashboard', compact('namaDepan', 'dataNutrisi', 'logAnomali', 'notifikasi'));
    }
            
    // Halaman Semua Notifikasi
    public function notifikasi()
    {
        // Ambil semua data notifikasi dari database
        $semuaNotifikasi = collect();
        try {
            $semuaNotifikasi = DB::table('notifications')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            // Abaikan jika tabel kosong/belum ada
        }
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

        $logAktuator = collect();
        try {
            $logAktuator = DB::table('actuator_logs')
                ->orderBy('executed_at', 'desc')
                ->limit(4)
                ->get();
        } catch (\Exception $e) {}

        // ---Ambil data ambang batas dari Session
        $ambangBatas = session('ambangBatas', [
            'ph_min' => 5.5, 'ph_max' => 6.5,
            'tds_min' => 900, 'tds_max' => 1200
        ]);

        // 4. Hitung Stabilitas secara Dinamis
        $stabilitas = [
            'ph_persen' => 0, 'ph_label' => 'Kritis', 'ph_color' => 'text-red-500', 'ph_bg' => 'bg-red-500',
            'tds_persen' => 0, 'tds_label' => 'Kritis', 'tds_color' => 'text-red-500', 'tds_bg' => 'bg-red-500',
        ];

        // --- Logika Stabilitas pH ---
        $phCurrent = $dataNutrisi['ph'];
        $phMin = $ambangBatas['ph_min'];
        $phMax = $ambangBatas['ph_max'];
        
        if ($phCurrent >= $phMin && $phCurrent <= $phMax) {
            // Hitung kedekatan dengan nilai tengah (ideal)
            $phMid = ($phMin + $phMax) / 2;
            $phDeviasi = abs($phCurrent - $phMid);
            $phMaxDeviasi = ($phMax - $phMin) / 2;
            // Jika dalam batas, nilainya antara 50% - 100%
            $stabilitas['ph_persen'] = 100 - (($phDeviasi / $phMaxDeviasi) * 50); 
        } else {
            // Jika di luar batas, persentase hancur
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

        // --- Logika Stabilitas TDS ---
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

        return view('sensor', compact('namaDepan', 'dataNutrisi', 'logAktuator', 'ambangBatas', 'stabilitas'));
    }

    // --- FUNGSI BARU UNTUK MENYIMPAN PENGATURAN
    public function updateAmbangBatas(Request $request)
    {
        // Validasi input agar tidak boleh kosong
        $request->validate([
            'ph_min' => 'required|numeric',
            'ph_max' => 'required|numeric|gte:ph_min', 
            'tds_min' => 'required|numeric',
            'tds_max' => 'required|numeric|gte:tds_min',
        ]);

        session(['ambangBatas' => $request->only('ph_min', 'ph_max', 'tds_min', 'tds_max')]);
        return back()->with('success', 'Pengaturan ambang batas berhasil disimpan!');
        
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil nama user yang sedang login
        $user = Auth::user();
        
        // Mengambil nama depan saja (Misal: "Admin NutriFlow" jadi "Admin")
        $namaDepan = explode(' ', $user->name)[0];

        // 2. Ambil data sensor dari tabel sensor_latest (Berdasarkan ERD Anda)
        // Set nilai default awal jaga-jaga jika tabel di database masih kosong
        $dataNutrisi = [
            'ph' => 0,
            'tds' => 0,
            'suhu_air' => 0,
            'suhu_ruang' => 0,
            'kelembaban' => 0
        ];

        try {
            // Mengambil nilai pH
            $phDB = DB::table('sensor_latest')
                ->join('sensor_types', 'sensor_latest.sensor_type_id', '=', 'sensor_types.id')
                ->where('sensor_types.name', 'ph')
                ->value('sensor_latest.value');
            if ($phDB !== null) $dataNutrisi['ph'] = $phDB;

            // Mengambil nilai TDS (ppm)
            $tdsDB = DB::table('sensor_latest')
                ->join('sensor_types', 'sensor_latest.sensor_type_id', '=', 'sensor_types.id')
                ->where('sensor_types.name', 'ppm')
                ->value('sensor_latest.value');
            if ($tdsDB !== null) $dataNutrisi['tds'] = $tdsDB;

            // Mengambil nilai Suhu
            $suhuDB = DB::table('sensor_latest')
                ->join('sensor_types', 'sensor_latest.sensor_type_id', '=', 'sensor_types.id')
                ->where('sensor_types.name', 'suhu')
                ->value('sensor_latest.value');

            if ($suhuDB !== null) $dataNutrisi['suhu_air'] = $suhuDB;
            $kelembabanDB = DB::table('sensor_latest')
                ->join('sensor_types', 'sensor_latest.sensor_type_id', '=', 'sensor_types.id')
                ->where('sensor_types.name', 'kelembaban')
                ->value('sensor_latest.value');
            if ($kelembabanDB !== null) $dataNutrisi['kelembaban'] = $kelembabanDB;
            $suhuRuangDB = DB::table('sensor_latest')
                ->join('sensor_types', 'sensor_latest.sensor_type_id', '=', 'sensor_types.id')
                ->where('sensor_types.name', 'suhu_ruang')
                ->value('sensor_latest.value');
            if ($suhuRuangDB !== null) $dataNutrisi['suhu_ruang'] = $suhuRuangDB;
            
            $notifikasi = collect(); // Siapkan koleksi kosong sebagai default
            try {
                // Ambil 3 notifikasi terakhir, urutkan dari yang terbaru
                $notifikasi = DB::table('notifications')
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get();
            } catch (\Exception $e) {
                // Jika tabel notifications belum ada, tetap lanjut tanpa notifikasi
            }

        } catch (\Exception $e) {
            // Jika tabel sensor_latest / sensor_types belum dibuat di pgAdmin, 
            // sistem akan mengabaikan error dan tetap menggunakan nilai default di atas.
        }

        // 3. Kirim data ke tampilan (View)
        return view('dashboard', compact('namaDepan', 'dataNutrisi', 'notifikasi'));
    }

    // Tambahkan fungsi baru ini untuk halaman Semua Notifikasi
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
}
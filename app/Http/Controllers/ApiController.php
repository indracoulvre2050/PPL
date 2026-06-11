<?php

namespace App\Http\Controllers;

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
}
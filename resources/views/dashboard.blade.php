<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NutriFlow</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="min-h-screen flex flex-col relative overflow-x-hidden bg-[#f4faf2]">

    <!-- ========================================== -->
    <!-- HEADER / NAVBAR                            -->
    <!-- ========================================== -->
    <header class="w-full px-6 md:px-12 py-5 flex items-center justify-between bg-[#f4faf2] sticky top-0 z-40 border-b border-transparent transition-all" id="navbar">
        <div class="text-xl font-extrabold text-[#1e7b2a] tracking-tight">NutriFlow</div>

        <div class="flex items-center gap-5">
            <a href="{{ route('notifikasi') }}" class="text-gray-500 hover:text-[#1e7b2a] transition relative">
                <i class="ph ph-bell text-xl"></i>
                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
            </a>

            <button class="text-gray-500 hover:text-gray-800 transition"><i class="ph ph-gear text-xl"></i></button>
            
            <div class="relative">
                <button id="profileDropdownBtn" class="focus:outline-none flex items-center transition-transform active:scale-95">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=1e7b2a&color=fff&bold=true" alt="Profile" class="w-8 h-8 rounded-full border-2 border-[#1e7b2a] shadow-sm cursor-pointer">
                </button>

                <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.08)] border border-gray-100 py-2 z-50 transform transition-all origin-top-right">
                    <div class="px-4 py-3 border-b border-gray-50 mb-1">
                        <p class="text-[13px] font-bold text-gray-800">Admin NutriFlow</p>
                        <p class="text-[10px] text-gray-400 font-medium truncate">admin@gmail.com</p>
                    </div>

                    <a href="#" class="px-4 py-2 text-[13px] font-semibold text-gray-600 hover:bg-[#f4faf2] hover:text-[#1e7b2a] transition-colors flex items-center gap-2.5">
                        <i class="ph ph-gear text-[16px]"></i> Pengaturan Akun
                    </a>
                    
                    <hr class="border-gray-50 my-1">
                    
                    <form action="{{ route('logout') }}" method="POST" class="block w-full m-0">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-[13px] font-semibold text-red-500 hover:bg-red-50 transition-colors flex items-center gap-2.5">
                            <i class="ph ph-sign-out text-[16px]"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- ========================================== -->
    <!-- WRAPPER TENGAH (SIDEBAR + KONTEN)          -->
    <!-- ========================================== -->
    <div class="flex flex-grow w-full max-w-[1600px] mx-auto">
        
        <!-- SIDEBAR -->
        <aside class="w-64 pt-8 px-8 hidden md:block shrink-0">
            <nav class="space-y-2">
                <a href="/dashboard" class="flex items-center gap-3 bg-[#388e3c] text-white px-4 py-3 rounded-xl text-[13px] font-bold shadow-sm">
                    <i class="ph-fill ph-squares-four text-lg"></i> Dashboard
                </a>
                <a href="/sensor" class="flex items-center gap-3 text-gray-500 hover:text-[#1e7b2a] px-4 py-3 rounded-xl text-[13px] font-bold transition-colors">
                    <i class="ph ph-broadcast text-lg"></i> Sensor
                </a>
            </nav>
        </aside>

        <!-- BODY (KONTEN DASHBOARD) -->
        <main class="flex-grow pt-8 px-6 md:px-8 pb-10 max-w-5xl">
                
            <!-- Ringkasan Sistem -->
            <div class="mb-6">
                <h2 class="text-[#1e7b2a] font-bold text-lg">Ringkasan Sistem</h2>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="w-2.5 h-2.5 bg-[#388e3c] rounded-full shadow-[0_0_5px_rgba(56,142,60,0.5)]"></span>
                    <span class="text-[13px] text-gray-600 font-medium">Sistem Berjalan Normal</span>
                </div>
            </div>

            <!-- Kartu Sensor -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                <!-- Kadar Nutrisi -->
                <div class="bg-white rounded-[1.5rem] p-7 flex justify-between items-start shadow-sm border border-gray-100">
                    <div>
                        <p class="text-[13px] font-bold text-gray-800 mb-4">Kadar Nutrisi</p>
                        <p class="text-3xl font-extrabold text-gray-900 mb-2">
                            {{ isset($dataNutrisi['tds']) ? number_format($dataNutrisi['tds'], 0, ',', '.') : '1.350' }} <span class="text-sm text-gray-500 font-semibold ml-0.5">PPM</span>
                        </p>
                        <p class="text-[11px] font-bold text-[#388e3c] flex items-center gap-1.5">
                            <i class="ph ph-arrow-up"></i> Optimal
                        </p>
                    </div>
                    <i class="ph ph-drop text-4xl text-gray-200 opacity-60"></i>
                </div>

                <!-- Tingkat pH -->
                <div class="bg-white rounded-[1.5rem] p-7 flex justify-between items-start shadow-sm border border-gray-100">
                    <div>
                        <p class="text-[13px] font-bold text-gray-800 mb-4">Tingkat pH</p>
                        <p class="text-3xl font-extrabold text-gray-900 mb-2">
                            {{ isset($dataNutrisi['ph']) ? number_format($dataNutrisi['ph'], 1, '.', '') : '6.8' }} <span class="text-sm text-gray-500 font-semibold ml-0.5">pH</span>
                        </p>
                        <p class="text-[11px] font-bold text-[#388e3c] flex items-center gap-1.5">
                            <i class="ph ph-check-circle"></i> Stabil
                        </p>
                    </div>
                    <i class="ph ph-drop text-4xl text-gray-200 opacity-60"></i>
                </div>
            </div>

            <!-- Log Anomali -->
            <div class="bg-white rounded-[1.5rem] p-7 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[14px] font-bold text-gray-900">Log Anomali Terakhir</h3>
                    <i class="ph ph-warning text-gray-600 text-lg"></i>
                </div>

                <div class="space-y-4">
                    @forelse ($logAnomali as $log)
                        @php
                            // Menerjemahkan sensor_type_id menjadi nama sensor
                            $namaSensor = 'Sensor ' . $log->sensor_type_id;
                            if ($log->sensor_type_id == 1) $namaSensor = 'TDS/PPM';
                            if ($log->sensor_type_id == 2) $namaSensor = 'Suhu/pH';

                            // Format angka dengan membuang angka nol di belakang koma berlebih
                            $nilai = floatval($log->value);
                            $batasMaks = floatval($log->max_value);
                            
                            $title = "Peringatan Ambang Batas " . $namaSensor;
                            $deskripsi = "Sistem mendeteksi nilai " . $namaSensor . " mencapai " . $nilai . ", melebihi batas maksimal normal (" . $batasMaks . "). Segera lakukan pengecekan pada reservoir.";
                        @endphp

                        <div class="bg-[#fff5f5] rounded-xl p-5 flex gap-4 items-start border border-transparent hover:border-gray-100 transition-colors">
                            <div class="mt-0.5 shrink-0">
                                <div class="w-5 h-5 rounded-full bg-[#d32f2f] flex items-center justify-center text-white">
                                    <i class="ph-fill ph-warning-circle text-[11px]"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-[13px] font-bold text-gray-900 mb-1.5">{{ $title }}</h4>
                                <p class="text-[12px] text-gray-600 leading-relaxed mb-2.5 pr-4">
                                    {{ $deskripsi }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-medium">
                                    {{ \Carbon\Carbon::parse($log->occurred_at)->translatedFormat('l, H:i') }} WIB
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400">
                            <p class="text-[13px] font-bold">Tidak ada anomali terdeteksi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="px-8 py-6 border-t border-gray-200/60 mt-auto text-[12px] text-gray-500 font-medium flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="max-w-screen-2xl mx-auto flex flex-col md:flex-row justify-between items-end gap-6">
            <div class="text-[11px] text-gray-500 font-medium">
                © 2026 Kelompok C9 Universitas Jember. Hak Cipta Dilindungi.
            </div>
        </div>
        <div class="flex items-center gap-1.5"><i class="ph ph-leaf"></i> NutriFlow System</div>
    </footer>

    <!-- Efek sticky navbar & Dropdown Profil -->
    <script>
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 10) {
                nav.classList.add('shadow-sm', 'bg-white/90', 'backdrop-blur-md');
                nav.classList.remove('bg-[#f4faf2]', 'border-transparent');
            } else {
                nav.classList.remove('shadow-sm', 'bg-white/90', 'backdrop-blur-md');
                nav.classList.add('bg-[#f4faf2]', 'border-transparent');
            }
        });

        const profileBtn = document.getElementById('profileDropdownBtn');
        const profileMenu = document.getElementById('profileDropdownMenu');

        profileBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function(event) {
            if (!profileBtn.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
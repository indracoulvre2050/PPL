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
<body class="min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- ========================================== -->
    <!-- HEADER / NAVBAR                            -->
    <!-- ========================================== -->
    <header class="w-full px-6 md:px-12 py-5 flex items-center justify-between bg-[#f4faf2] sticky top-0 z-40 border-b border-transparent transition-all" id="navbar">
        <!-- Logo -->
        <div class="text-xl font-extrabold text-[#1e7b2a] tracking-tight">NutriFlow</div>
        
        <!-- Navigasi Tengah -->
        <nav class="hidden md:flex items-center gap-8 text-[13px] font-bold text-gray-400">
            <!-- Menu Aktif (Garis Bawah Hijau) -->
            <a href="#/dashboard" class="text-[#1e7b2a] border-b-2 border-[#1e7b2a] pb-1">Beranda</a>
            <a href="#" class="hover:text-gray-700 transition-colors">Tanaman</a>
            <a href="#" class="hover:text-gray-700 transition-colors">Nutrisi</a>
            <a href="#" class="hover:text-gray-700 transition-colors">Laporan</a>
        </nav>

        <!-- Profil & Ikon Kanan -->
        <div class="flex items-center gap-5">
            <a href="{{ route('notifikasi') }}" class="text-gray-500 hover:text-[#1e7b2a] transition relative">
                <i class="ph ph-bell text-xl"></i>
                <!-- Opsional: Titik merah kecil penanda ada notif baru -->
                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
            </a>

            <button class="text-gray-500 hover:text-gray-800 transition"><i class="ph ph-gear text-xl"></i></button>
            <!-- Foto Profil Dummy -->
            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=1e7b2a&color=fff&bold=true" alt="Profile" class="w-8 h-8 rounded-full border-2 border-white shadow-sm cursor-pointer">
        </div>
        
    </header>

    <!-- ========================================== -->
    <!-- MAIN KONTEN (DASHBOARD GRID)               -->
    <!-- ========================================== -->
    <main class="flex-grow px-6 md:px-12 pt-6 pb-20 w-full max-w-screen-2xl mx-auto relative z-10">
        
        <!-- BAGIAN HERO (Sambutan) -->
        <section class="relative mb-8 w-full flex justify-between items-center">
            <div class="relative z-10">
                <p class="text-[11px] font-bold tracking-[0.15em] text-gray-500 uppercase mb-2">Selamat Datang, {{ Auth::user()->name }}</p>
                <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 leading-tight tracking-tight">
                    Kebun Anda sedang <span class="text-[#1e7b2a]">mekar sempurna.</span>
                </h1>
                <div class="inline-flex items-center gap-2 bg-[#2d7a31] text-white px-4 py-2 rounded-full text-[12px] font-bold mt-5 shadow-sm">
                    <i class="ph-fill ph-leaf"></i> 98% Kesehatan Optimal
                </div>
            </div>
            
            <!-- Gambar Dekorasi Kanan Atas (Ganti nama file sesuai asset Anda) -->
            <img src="{{ asset('assets/hero-leaves.png') }}" class="hidden md:block absolute -top-10 -right-10 w-72 h-72 object-cover opacity-80 mix-blend-multiply pointer-events-none" style="border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;" alt="">
        </section>

        <!-- GRID UTAMA DASHBOARD -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Baris 1: Status Tanaman (Lebar 2/3) -->
            <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-gray-100 col-span-1 md:col-span-2 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Status Tanaman</h2>
                        <p class="text-[12px] text-gray-500 font-medium mt-0.5">Pemantauan Real-time Sistem</p>
                    </div>
                    <div class="bg-[#f4faf2] p-2.5 rounded-xl text-[#1e7b2a]"><i class="ph ph-plant text-xl"></i></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Kartu Mini 1 -->
                    <div class="bg-[#f8fbf7] rounded-2xl p-4 border border-green-50">
                        <p class="text-[11px] text-gray-500 font-semibold mb-1">Selada Romaine</p>
                        <div class="flex items-end gap-1"><span class="text-2xl font-extrabold text-[#1e7b2a]">120</span> <span class="text-[11px] text-gray-500 font-medium mb-1">Unit</span></div>
                        <div class="w-full bg-gray-200 h-1 mt-3 rounded-full"><div class="bg-[#1e7b2a] h-1 rounded-full w-[80%]"></div></div>
                    </div>
                    <!-- Kartu Mini 2 -->
                    <div class="bg-[#f8fbf7] rounded-2xl p-4 border border-green-50">
                        <p class="text-[11px] text-gray-500 font-semibold mb-1">Pak Choy</p>
                        <div class="flex items-end gap-1"><span class="text-2xl font-extrabold text-[#1e7b2a]">85</span> <span class="text-[11px] text-gray-500 font-medium mb-1">Unit</span></div>
                        <div class="w-full bg-gray-200 h-1 mt-3 rounded-full"><div class="bg-[#1e7b2a] h-1 rounded-full w-[60%]"></div></div>
                    </div>
                    <!-- Kartu Mini 3 -->
                    <div class="bg-[#f8fbf7] rounded-2xl p-4 border border-green-50">
                        <p class="text-[11px] text-gray-500 font-semibold mb-1">Kesehatan</p>
                        <div class="flex items-end gap-1"><span class="text-2xl font-extrabold text-[#1e7b2a]">98%</span></div>
                        <p class="text-[9px] text-[#3d9829] font-bold mt-2">+2% dari kemarin</p>
                    </div>
                    <!-- Kartu Mini 4 -->
                    <div class="bg-[#f8fbf7] rounded-2xl p-4 border border-green-50">
                        <p class="text-[11px] text-gray-500 font-semibold mb-1">Usia Rata-rata</p>
                        <div class="flex items-end gap-1"><span class="text-2xl font-extrabold text-[#1e7b2a]">18</span> <span class="text-[11px] text-gray-500 font-medium mb-1">Hari</span></div>
                        <p class="text-[9px] text-gray-400 font-bold mt-2">Fase Pertumbuhan</p>
                    </div>
                </div>
            </div>

            <!-- Baris 1: Keseimbangan Nutrisi (Lebar 1/3) -->
            <div class="bg-[#fcfdfa] rounded-[2rem] p-7 shadow-sm border border-gray-100 col-span-1">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-lg font-bold text-gray-800">Keseimbangan Nutrisi</h2>
                    <i class="ph ph-flask text-gray-400 text-xl"></i>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[13px] font-bold text-gray-700">Tingkat pH</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">Target: 6.0</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-extrabold text-gray-800 mb-0.5">{{ number_format($dataNutrisi['ph'], 1) }}</p>
                            <span class="text-[9px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-md tracking-wider">STABIL</span>
                        </div>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[13px] font-bold text-gray-700">TDS (PPM)</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">Target: 1200</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-extrabold text-gray-800 mb-0.5">{{ number_format($dataNutrisi['tds'], 0) }}</p>
                            <span class="text-[9px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-md tracking-wider">OPTIMAL</span>
                        </div>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[13px] font-bold text-gray-700">Suhu Air</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">Target: 24°C</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-extrabold text-gray-800">{{ number_format($dataNutrisi['suhu_air'], 1) }}<span class="text-[12px] text-gray-400 font-semibold ml-0.5">°c</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baris 2: Laporan Pertumbuhan (Lebar 1/3) -->
            <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-gray-100 col-span-1 flex flex-col justify-between">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-md font-bold text-gray-800">Laporan Pertumbuhan</h2>
                    <a href="#" class="text-[11px] font-bold text-[#1e7b2a] hover:underline">Detail</a>
                </div>
                
                <!-- Diagram Batang (CSS Murni) -->
                <div class="flex items-end justify-between h-28 mb-5 gap-2 px-2">
                    <div class="w-full bg-[#e9f1e8] rounded-t-[4px] h-[35%] transition-all hover:bg-[#d1e3ce]"></div>
                    <div class="w-full bg-[#e9f1e8] rounded-t-[4px] h-[55%] transition-all hover:bg-[#d1e3ce]"></div>
                    <div class="w-full bg-[#e9f1e8] rounded-t-[4px] h-[40%] transition-all hover:bg-[#d1e3ce]"></div>
                    <div class="w-full bg-[#e9f1e8] rounded-t-[4px] h-[65%] transition-all hover:bg-[#d1e3ce]"></div>
                    <div class="w-full bg-[#e9f1e8] rounded-t-[4px] h-[80%] transition-all hover:bg-[#d1e3ce]"></div>
                    <div class="w-full bg-[#2d7a31] rounded-t-[4px] h-[100%] shadow-sm"></div>
                </div>
                
                <p class="text-[11px] text-gray-500 font-medium leading-relaxed">
                    Pertumbuhan minggu ini meningkat <span class="font-bold text-[#1e7b2a]">12%</span> dibanding periode sebelumnya karena optimalisasi cahaya.
                </p>
            </div>

            <!-- Baris 2: Sistem Otomasi (Lebar 2/3) -->
            <div class="bg-[#2d7a31] rounded-[2rem] p-8 shadow-sm col-span-1 md:col-span-2 relative overflow-hidden flex flex-col justify-center">
                <!-- Ornamen Lingkaran Background -->
                <div class="absolute top-0 right-0 w-80 h-80 bg-white opacity-5 rounded-full -translate-y-1/3 translate-x-1/4"></div>
                <div class="absolute bottom-0 right-1/3 w-40 h-40 bg-black opacity-10 rounded-full translate-y-1/2"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between h-full gap-6">
                    <div class="text-white">
                        <h2 class="text-2xl font-bold mb-4 leading-tight">Sistem Otomasi<br>Berjalan Lancar</h2>
                        <div class="flex gap-3">
                            <span class="bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-full text-[11px] font-bold flex items-center gap-1.5 border border-white/10">
                                <i class="ph ph-thermometer text-sm"></i> {{ number_format($dataNutrisi['suhu_ruang'], 0) }}°C Suhu Ruang
                            </span>
                            <span class="bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-full text-[11px] font-bold flex items-center gap-1.5 border border-white/10">
                                <i class="ph ph-drop text-sm"></i> {{ number_format($dataNutrisi['kelembaban'], 0) }}% Kelembaban
                            </span>
                        </div>
                    </div>
                    <button class="bg-[#bdf0a6] text-[#163300] hover:bg-[#a8e08f] px-6 py-3.5 rounded-[1rem] font-bold text-[13px] transition flex items-center gap-2 shadow-lg w-full md:w-auto justify-center">
                        <i class="ph-fill ph-lightning"></i> Atur Manual
                    </button>
                </div>
            </div>

            <!-- Baris 3: Aktivitas Terbaru (Lebar 1/3) -->
            <div class="col-span-1 pr-4">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Aktivitas Terbaru</h2>
                <p class="text-[12px] text-gray-500 font-medium mb-6 leading-relaxed">Pantau perubahan sistem dan intervensi yang telah dilakukan pada instalasi hidroponik Anda.</p>
                
                <!-- Timeline List -->
                <div class="relative border-l border-gray-200 ml-2 space-y-7 pb-4 mt-2">
                    @forelse ($notifikasi as $notif)
                        <div class="relative pl-6">
                            <!-- Logika Warna: Hijau gelap jika belum dibaca (false), hijau muda jika sudah dibaca (true) -->
                            <span class="absolute -left-[6px] top-1 w-3 h-3 {{ $notif->is_read ? 'bg-[#a8e08f]' : 'bg-[#1e7b2a]' }} rounded-full ring-4 ring-[#f4faf2]"></span>
                            
                            <!-- Pesan Notifikasi -->
                            <h3 class="text-[12px] font-bold text-gray-800">{{ $notif->message }}</h3>
                            
                            <!-- Format Waktu (Contoh: "2 hours ago") -->
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                            </p>
                        </div>
                    @empty
                        <!-- Tampilan jika tabel database kosong -->
                        <div class="relative pl-6">
                            <span class="absolute -left-[6px] top-1 w-3 h-3 bg-gray-300 rounded-full ring-4 ring-[#f4faf2]"></span>
                            <h3 class="text-[12px] font-bold text-gray-800">Belum ada aktivitas</h3>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">Sistem berjalan normal</p>
                        </div>
                    @endforelse

                </div>
            </div>

            <!-- Baris 3: Tips NutriFlow (Lebar 2/3) -->
            <div class="col-span-1 md:col-span-2 relative rounded-[2rem] overflow-hidden shadow-sm min-h-[220px]">
                <!-- Background Image (Ganti src dengan path foto hidroponik ungu Anda) -->
                <img src="{{ asset('assets/tips-bg.jpg') }}" alt="Tips Hidroponik" class="absolute inset-0 w-full h-full object-cover">
                <!-- Gradient overlay agar teks terbaca -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#163300]/90 via-[#163300]/40 to-transparent"></div>
                
                <div class="absolute bottom-0 left-0 p-8 text-white w-full md:w-3/4">
                    <h2 class="text-xl font-bold mb-2">Tips NutriFlow</h2>
                    <p class="text-[12px] text-gray-200 mb-5 font-medium leading-relaxed">Pastikan sirkulasi udara pada pukul 12:00 - 14:00 terjaga untuk mencegah tip-burn pada daun selada muda.</p>
                    <button class="bg-white text-gray-900 hover:bg-gray-100 px-5 py-2.5 rounded-full font-bold text-[11px] transition shadow-md">
                        Pelajari Selengkapnya
                    </button>
                </div>
            </div>
        </div> <!-- End Grid -->
    </main>

    <!-- Tombol Floating Bawah Kanan (+) -->
    <button class="fixed bottom-24 right-10 bg-[#1e7b2a] text-white w-14 h-14 rounded-full shadow-xl flex items-center justify-center hover:bg-[#16601f] transition-transform hover:scale-105 active:scale-95 z-50">
        <i class="ph ph-plus text-2xl font-bold"></i>
    </button>

    <!-- FOOTER -->
    <footer class="w-full px-6 md:px-12 py-8 bg-[#f4faf2] border-t border-gray-200 mt-auto relative z-20">
        <div class="max-w-screen-2xl mx-auto flex flex-col md:flex-row justify-between items-end gap-6">
            
            <!-- Bagian Kiri Footer -->
            <div>
                <div class="text-lg font-extrabold text-[#1e7b2a] mb-1">NutriFlow</div>
                <div class="text-[11px] text-gray-500 font-medium">
                    © 2024 NutriFlow Digital Experience. Hak Cipta Dilindungi.
                </div>
            </div>
            
            <!-- Bagian Kanan Footer -->
            <div class="flex flex-wrap items-center gap-x-8 gap-y-2 text-[11px]">
                <a href="#" class="text-gray-500 hover:text-[#1e7b2a] font-bold transition-colors">Tentang Kami</a>
                <a href="#" class="text-gray-500 hover:text-[#1e7b2a] font-bold transition-colors">Bantuan</a>
                <a href="#" class="text-gray-500 hover:text-[#1e7b2a] font-bold transition-colors">Kebijakan Privasi</a>
                <a href="#" class="text-gray-500 hover:text-[#1e7b2a] font-bold transition-colors">Syarat & Ketentuan</a>
            </div>

        </div>
    </footer>

    <!-- Efek sticky navbar -->
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
    </script>
</body>
</html>
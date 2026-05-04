<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Notifikasi - NutriFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- HEADER -->
    <header class="w-full px-6 md:px-12 py-5 flex items-center justify-between bg-white shadow-sm sticky top-0 z-40">
        <div class="text-xl font-extrabold text-[#1e7b2a] tracking-tight">NutriFlow</div>
        
        <nav class="hidden md:flex items-center gap-8 text-[13px] font-bold text-gray-400">
            <a href="/dashboard" class="hover:text-gray-700 transition-colors">Beranda</a>
            <a href="#" class="hover:text-gray-700 transition-colors">Tanaman</a>
            <a href="#" class="hover:text-gray-700 transition-colors">Nutrisi</a>
            <a href="#" class="hover:text-gray-700 transition-colors">Laporan</a>
        </nav>

        <div class="flex items-center gap-5">
            <a href="{{ route('notifikasi') }}" class="text-[#1e7b2a] transition relative">
                <i class="ph-fill ph-bell text-xl"></i>
            </a>
            <button class="text-gray-500 hover:text-gray-800 transition"><i class="ph ph-gear text-xl"></i></button>
            <img src="https://ui-avatars.com/api/?name=Admin&background=1e7b2a&color=fff&bold=true" alt="Profile" class="w-8 h-8 rounded-full border-2 border-white shadow-sm cursor-pointer">
        </div>
    </header>

    <!-- MAIN KONTEN: DAFTAR NOTIFIKASI -->
    <main class="flex-grow px-6 md:px-12 pt-8 pb-20 w-full max-w-screen-md mx-auto relative z-10">
        
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Pusat Notifikasi</h1>
                <p class="text-[12px] text-gray-500 font-medium mt-1">Riwayat lengkap peringatan dan aktivitas sistem.</p>
            </div>
        </div>

        <!-- Kotak Daftar Notifikasi -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            @forelse ($semuaNotifikasi as $notif)
                <!-- Kondisi: Jika is_read = true, background lebih pucat -->
                <div class="p-5 border-b border-gray-50 flex items-start gap-4 transition-colors hover:bg-gray-50 {{ $notif->is_read ? 'opacity-60' : 'bg-[#f4faf2]/30' }}">
                    
                    <!-- Indikator Titik (Hijau = Baru, Abu = Lama) -->
                    <div class="mt-1.5 shrink-0">
                        <span class="w-2.5 h-2.5 flex rounded-full {{ $notif->is_read ? 'bg-gray-300' : 'bg-[#1e7b2a] shadow-[0_0_8px_rgba(30,123,42,0.4)]' }}"></span>
                    </div>

                    <!-- Teks Pesan -->
                    <div class="flex-grow">
                        <p class="text-[13px] font-bold {{ $notif->is_read ? 'text-gray-600' : 'text-gray-900' }}">
                            {{ $notif->message }}
                        </p>
                        <!-- Format Waktu yang Rapi -->
                        <p class="text-[11px] text-gray-400 font-medium mt-1.5 flex items-center gap-1">
                            <i class="ph ph-clock"></i> 
                            {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <!-- Tampilan Jika Tidak Ada Notifikasi -->
                <div class="p-12 text-center text-gray-400">
                    <i class="ph ph-bell-slash text-5xl mb-3 opacity-50"></i>
                    <p class="text-[14px] font-bold text-gray-600">Tidak ada notifikasi</p>
                    <p class="text-[12px] mt-1">Sistem Anda berjalan dengan sangat tenang saat ini.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="w-full px-6 md:px-12 py-8 bg-[#f4faf2] border-t border-gray-200 mt-auto">
        <div class="max-w-screen-2xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-[11px] text-gray-500 font-medium text-center md:text-left">
                <span class="text-lg font-extrabold text-[#1e7b2a] block mb-1">NutriFlow</span>
                © 2024 NutriFlow Digital Experience. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

</body>
</html>
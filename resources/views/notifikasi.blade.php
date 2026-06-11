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

    <!-- MAIN KONTEN: DAFTAR NOTIFIKASI -->
    <a href="{{ route('dashboard') }}" class="px-4 py-2 max-w-[200px] mx-100px rounded-full flex items-center gap-2 text-[13px] bg-[#1e7b2a] text-white font-bold transition-all duration-500">
        <i class="ph ph-arrow-left text-lg"></i> Kembali ke Dashboard</a>
        
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
    <footer class="px-8 py-6 border-t border-gray-200/60 mt-auto text-[12px] text-gray-500 font-medium flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="max-w-screen-2xl mx-auto flex flex-col md:flex-row justify-between items-end gap-6">
            <div class="text-[11px] text-gray-500 font-medium">
                © 2026 Kelompok C9 Universitas Jember. Hak Cipta Dilindungi.
            </div>
        </div>
        <div class="flex items-center gap-1.5"><i class="ph ph-leaf"></i> NutriFlow System</div>
    </footer>
    
    <script>   
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NutriFlow</title>
    
    <!-- Menggunakan Tailwind CSS via CDN untuk kemudahan (Bisa diganti dengan Vite bawaan Laravel) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Menggunakan Phosphor Icons untuk Ikon Email dan Password -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-[#f4faf2] min-h-screen relative font-sans flex flex-col justify-between overflow-hidden">
    <img src="{{ asset('assets/Login-kiri.png') }}" alt="Dekorasi Kiri" class="absolute top-0 left-0 w-72 opacity-40 pointer-events-none mix-blend-multiply">
    <img src="{{ asset('assets/Login-kanan.png') }}" alt="Dekorasi Kanan" class="absolute bottom-16 right-0 w-[450px] opacity-40 pointer-events-none mix-blend-multiply">

    <!-- <body class="min-h-screen flex flex-col relative overflow-hidden bg-cover bg-center bg-no-repeat bg-[#f2f8f0]" 
      style="background-image: url('{{ asset('assets/Login BG.png') }}');">
       -->
    <!-- Kontainer Form Utama -->
    <div class="flex-grow flex items-center justify-center relative z-10 px-4">
        <!-- Card Putih -->
        <div class="bg-white rounded-[2rem] shadow-[0_10px_40px_rgba(0,0,0,0.04)] w-full max-w-[420px] p-8 md:p-10 text-center">

            <!-- Bagian Logo NutriFlow -->
            <div class="flex justify-center mb-3">
                <div class="bg-[#1e7b2a] text-white p-3 rounded-[1rem] shadow-sm">
                    <!-- Anda bisa mengganti tag <i> ini dengan tag <img> jika logo Anda berupa file gambar -->
                    <i class="ph ph-leaf text-3xl"></i> 
                </div>
            </div>
            <h1 class="text-2xl font-bold text-[#1e7b2a] mb-8 tracking-tight">NutriFlow</h1>
            
            @if($errors->any())
                <div class="bg-[#ffdbdb] text-[#d32f2f] rounded-xl p-3.5 flex items-center gap-2 mb-6 text-[13px] font-semibold text-left">
                    <i class="ph ph-warning-circle text-[18px]"></i>
                    <span>Email dan password salah!</span>
                </div>
            @endif
            
            <!-- Form Input -->
            <form action="{{ route('login.submit') }}" method="POST" class="text-left space-y-5">
                @csrf <!-- Token keamanan wajib dari Laravel -->
                
                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-[13px] font-medium text-gray-500 mb-1.5 ml-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph ph-at text-gray-400 text-lg"></i>
                        </div>
                        <input type="email" id="email" name="email" 
                            class="bg-[#f4f9f3] border border-transparent text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-[#1e7b2a] focus:border-transparent block w-full pl-10 p-3.5 outline-none transition-all" 
                            placeholder="nama@email.com" required>
                    </div>
                </div>

                <!-- Input Kata Sandi -->
                <div>
                    <div class="flex justify-between items-center mb-1.5 ml-1">
                        <label for="password" class="block text-[13px] font-medium text-gray-500">Kata Sandi</label>
                        <a href="#" class="text-[12px] font-bold text-[#1e7b2a] hover:underline">Lupa Sandi?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ph ph-lock-key text-gray-400 text-lg"></i>
                        </div>
                        <input type="password" id="password" name="password" 
                            class="bg-[#f4f9f3] border border-transparent text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-[#1e7b2a] focus:border-transparent block w-full pl-10 p-3.5 outline-none transition-all" 
                            placeholder="••••••••" required>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" 
                    class="w-full text-white bg-[#1e7b2a] hover:bg-[#16601f] font-semibold rounded-xl text-sm px-5 py-3.5 mt-2 text-center transition duration-200 shadow-md hover:shadow-lg">
                    Masuk Ke Dashboard
                </button>
            </form>

            <!-- Status Sistem -->
            <div class="mt-8 pt-6 border-t border-gray-50 flex justify-center items-center gap-2 text-[11px] text-gray-500 font-medium">
                <span class="w-2 h-2 rounded-full bg-[#3d9829] shadow-[0_0_5px_rgba(61,152,41,0.5)]"></span>
                Sistem Berjalan: V2.4.1 Stable
            </div>
        </div>
    </div>

    <!-- Footer Bawah -->
    <footer class="relative z-20 w-full px-8 py-6 bg-[#f4faf2] backdrop-blur-md border-t border-gray-200/60 mt-auto">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
            <div class="text-[12px] text-gray-500 font-medium">
                © 2024 NutriFlow Digital Experience. Pantauan Hidroponik Real-time.
            </div>
            
            <div class="flex items-center gap-6 text-[12px]">
                <a href="#" class="text-gray-400 hover:text-gray-600 font-medium transition-colors">Bantuan</a>
                <a href="#" class="text-gray-400 hover:text-gray-600 font-medium transition-colors">Dokumentasi</a>
                <a href="#" class="text-gray-400 hover:text-gray-600 font-medium transition-colors">Privasi</a>
            </div>
        </div>
    </footer>
</body>
</html>
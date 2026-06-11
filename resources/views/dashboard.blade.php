<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="manifest" href="/manifest.json">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NutriFlow</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="min-h-screen flex flex-col relative overflow-x-hidden bg-[#f4faf2]">

    <!-- HEADER / NAVBAR -->
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

    <!-- WRAPPER TENGAH -->
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

        <!-- DASHBOARD -->
        <main class="flex-grow pt-8 px-6 md:px-8 pb-10 max-w-5xl">
                
            <!-- Ringkasan Sistem -->
            <div class="{{ $statusSistem['bg_warna'] }} border rounded-2xl p-5 flex items-center gap-4 mb-6 transition-colors duration-500" id="dash-summary-box">
                <div class="bg-white p-3 rounded-full shadow-sm" id="dash-summary-icon-bg">
                    <i class="ph-fill {{ $statusSistem['ikon'] }} text-2xl" id="dash-summary-icon"></i>
                </div>
                <div>
                    <p class="text-[12px] font-bold text-gray-600 uppercase tracking-wider mb-1">Status Sistem</p>
                    <h2 class="text-lg font-bold {{ $statusSistem['teks_warna'] }}" id="dash-summary-text">{{ $statusSistem['teks'] }}</h2>
                </div>
            </div>

            <!-- Kartu Sensor -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                <!-- Kadar Nutrisi -->
                <div class="bg-white rounded-[1.5rem] p-7 flex justify-between items-start shadow-sm border border-gray-100">
                    <div>
                        <p class="text-[13px] font-bold text-gray-800 mb-4">Kadar Nutrisi</p>
                        <p class="text-3xl font-extrabold text-gray-900 mb-2">
                            <span id="live-tds-dash">{{ number_format($dataNutrisi['tds'], 0, ',', '.')}}</span>
                            <span class="text-sm text-gray-500 font-semibold ml-0.5">PPM</span>
                        </p>
                        <p class="text-[12px] font-bold {{ $stabilitas['tds_color'] }} flex items-center gap-1.5 mt-2 transition-colors duration-500">
                            <i class="ph-fill {{ $stabilitas['tds_icon'] }} text-lg" id="live-tds-icon"></i> 
                            <span id="live-tds-label">{{ $stabilitas['tds_label'] }}</span>
                        </p>
                    </div>
                    <i class="ph ph-drop text-4xl text-gray-200 opacity-60"></i>
                </div>

                <!-- Tingkat pH -->
                <div class="bg-white rounded-[1.5rem] p-7 flex justify-between items-start shadow-sm border border-gray-100">
                    <div>
                        <p class="text-[13px] font-bold text-gray-800 mb-4">Tingkat pH</p>
                        <p class="text-3xl font-extrabold text-gray-900 mb-2">
                            <span id="live-ph-dash">{{ number_format($dataNutrisi['ph'], 1, '.', '') }}</span>
                            <span class="text-sm text-gray-500 font-semibold ml-0.5">pH</span>
                        </p>
                        <p class="text-[12px] font-bold {{ $stabilitas['ph_color'] }} flex items-center gap-1.5 mt-2 transition-colors duration-500">
                            <i class="ph-fill {{ $stabilitas['ph_icon'] }} text-lg" id="live-ph-icon"></i> 
                            <span id="live-ph-label">{{ $stabilitas['ph_label'] }}</span>
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

        // Update Data
        setInterval(function() {
            fetch('/api/data-terbaru')
                .then(response => response.json())
                .then(data => {
                    let tdsDash = document.getElementById('live-tds-dash');
                    let phDash = document.getElementById('live-ph-dash');
                    let sumBox = document.getElementById('dash-summary-box');
                    let sumText = document.getElementById('dash-summary-text');
                    let sumIcon = document.getElementById('dash-summary-icon');
                    let tdsLabel = document.getElementById('live-tds-label');
                    let tdsIcon = document.getElementById('live-tds-icon');
                    let phLabel = document.getElementById('live-ph-label');
                    let phIcon = document.getElementById('live-ph-icon');
                    
                    if(tdsDash) tdsDash.innerText = data.tds;
                    if(phDash) phDash.innerText = data.ph;
                    if(sumBox && data.statusSistem) {
                        sumBox.className = `${data.statusSistem.bg_warna} border rounded-2xl p-5 flex items-center gap-4 mb-6 transition-colors duration-500`;
                        sumText.className = `text-lg font-bold ${data.statusSistem.teks_warna}`;
                        sumText.innerText = data.statusSistem.teks;
                        sumIcon.className = `ph-fill ${data.statusSistem.ikon} text-2xl`;
                    }
                    if(tdsLabel && data.stabilitas) {
                        tdsLabel.innerText = data.stabilitas.tds_label;
                        tdsLabel.parentElement.className = `text-[12px] font-bold ${data.stabilitas.tds_color} flex items-center gap-1.5 mt-2 transition-colors duration-500`;
                        tdsIcon.className = `ph-fill ${data.stabilitas.tds_icon} text-lg`;
                    }
                    if(phLabel && data.stabilitas) {
                        phLabel.innerText = data.stabilitas.ph_label;
                        phLabel.parentElement.className = `text-[12px] font-bold ${data.stabilitas.ph_color} flex items-center gap-1.5 mt-2 transition-colors duration-500`;
                        phIcon.className = `ph-fill ${data.stabilitas.ph_icon} text-lg`;
                    }
                })
                .catch(error => console.error('Gagal mengambil data:', error));
        }, 3000);
        

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.register('/sw.js').then(function (registration) {
                console.log('Service Worker terdaftar.');

                Notification.requestPermission().then(function (permission) {
                    if (permission === 'granted') {
                        const vapidPublicKey = "{{ env('VAPID_PUBLIC_KEY') }}";
                        const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

                        registration.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: convertedVapidKey
                        }).then(function (subscription) {
                            fetch("{{ route('simpan.subscription') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify(subscription)
                            })
                            .then(response => response.json())
                            .then(data => console.log('Sukses tersimpan di DB:', data))
                            .catch(err => console.error('Gagal mengirim ke DB:', err));

                        });
                    }
                });
            }).catch(function (error) {
                console.error('Service Worker Error:', error);
            });
        }
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Sensor - NutriFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .slider-container {
            position: relative;
            width: 100%;
            height: 6px;
            background-color: #e5e7eb;
            border-radius: 9999px;
            margin: 1.5rem 0 1rem 0;
        }
        .slider-track {
            position: absolute;
            height: 100%;
            border-radius: 9999px;
            z-index: 10;
        }
        .range-input {
            position: absolute;
            width: 100%;
            height: 6px;
            top: 0;
            background: none;
            pointer-events: none;
            -webkit-appearance: none;
            outline: none;
            z-index: 20;
            margin: 0;
            padding: 0;
        }
        .range-input::-webkit-slider-thumb {
            height: 18px;
            width: 18px;
            border-radius: 50%;
            pointer-events: auto;
            -webkit-appearance: none;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            margin-top: -6px; /* Posisi vertikal ke tengah garis */
        }
        /* Mengatur Lingkaran Drag */
        .thumb-ph::-webkit-slider-thumb { background: white; border: 5px solid #2e7d32; }
        .thumb-tds::-webkit-slider-thumb { background: white; border: 5px solid #6d4c41; }
    </style>
</head>

<body class="min-h-screen flex flex-col relative overflow-x-hidden bg-[#f4faf2]">

    <!-- HEADER  -->
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
                <!-- Dashboard Inaktif -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-gray-500 hover:text-[#1e7b2a] px-4 py-3 rounded-xl text-[13px] font-bold transition-colors">
                    <i class="ph ph-squares-four text-lg"></i> Dashboard
                </a>
                <!-- Sensor Aktif (Hijau) -->
                <a href="{{ route('sensor') }}" class="flex items-center gap-3 bg-[#388e3c] text-white px-4 py-3 rounded-xl text-[13px] font-bold shadow-sm">
                    <i class="ph-fill ph-broadcast text-lg"></i> Sensor
                </a>
            </nav>
        </aside>

        <!-- KONTEN UTAMA SENSOR -->
        <main class="flex-grow pt-8 px-6 md:px-8 pb-10 w-full">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM KIRI (Lebar 2/3) -->
                <div class="lg:col-span-2 flex flex-col gap-6">
                    
                    <!-- Judul Halaman -->
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Detail Sensor Nutrisi</h1>
                            <p class="text-[14px] text-gray-600 mt-1">Pemantauan real-time dan pengaturan ambang batas larutan hidroponik.</p>
                        </div>
                        <div id="status-container" class="{{ $statusAlat['bg'] }} {{ $statusAlat['teks'] }} px-4 py-2 rounded-full flex items-center gap-2 text-[13px] font-bold transition-all duration-500">
                            <i id="status-icon" class="ph-fill {{ $statusAlat['ikon'] }} text-lg"></i>
                            <span id="status-text">{{ $statusAlat['label'] }}</span>
                        </div>
                    </div>

                    <!-- Kartu Sensor (Grid 2 Kolom) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <!-- Kartu pH -->
                        <div class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-[#2e7d32] text-white flex items-center justify-center shadow-md">
                                        <i class="ph-fill ph-drop text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-[12px] font-bold text-gray-800 uppercase tracking-wide">Tingkat pH</h3>
                                        <p class="text-[10px] text-gray-500 font-medium mt-0.5">Target: {{ $ambangBatas['ph_min'] }} - {{ $ambangBatas['ph_max'] }}</p>
                                    </div>
                                </div>
                                <span class="bg-[#b9f6ca] text-[#1b5e20] text-[10px] font-bold px-3 py-1 rounded-md">Normal</span>
                            </div>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-5xl font-extrabold text-gray-900 tracking-tighter"> <span id="live-ph-sensor"> {{ number_format($dataNutrisi['ph'], 1, '.', '') }} </span> </span>
                                <span class="text-sm font-semibold text-gray-500">pH</span>
                            </div>
                        </div>

                        <!-- Kartu TDS -->
                        <div class="bg-white rounded-[1.5rem] p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                            <div class="flex justify-between items-start mb-6">
                                <div class="flex gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-[#ffcc80] text-[#e65100] flex items-center justify-center shadow-md">
                                        <i class="ph-fill ph-grid-four text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-[12px] font-bold text-gray-800 uppercase tracking-wide">Konsentrasi TDS</h3>
                                        <p class="text-[10px] text-gray-500 font-medium mt-0.5">Target: {{ $ambangBatas['tds_min'] }} - {{ $ambangBatas['tds_max'] }} PPM</p>
                                    </div>
                                </div>
                                <span class="bg-[#b9f6ca] text-[#1b5e20] text-[10px] font-bold px-3 py-1 rounded-md">Normal</span>
                            </div>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-5xl font-extrabold text-gray-900 tracking-tighter"> <span id="live-tds-sensor">{{ number_format($dataNutrisi['tds'], 0, ',', '.') }} </span> </span>
                                <span class="text-sm font-semibold text-gray-500">PPM</span>
                            </div>
                        </div>
                    </div>

                    <!-- Log Aktuator -->
                    <div class="bg-[#eef5eb] rounded-[1.5rem] p-7 border border-[#e2ebd9]">
                        <h3 class="text-[15px] font-bold text-gray-900 flex items-center gap-2 mb-6">
                            <i class="ph ph-clock-counter-clockwise text-xl text-gray-600"></i> Log Aktuator
                        </h3>
                        
                        <div class="relative border-l-2 border-gray-300/50 ml-3 space-y-6">
                            @forelse ($logAktuator as $log)
                                @php
                                    // 1. Terjemahkan ID Aktuator menjadi Nama Alat
                                    $namaAktuator = 'Alat ' . $log->actuator_id;
                                    if ($log->actuator_id == 1) $namaAktuator = 'Pompa Nutrisi A';
                                    if ($log->actuator_id == 2) $namaAktuator = 'Solenoid pH Down';
                                    if ($log->actuator_id == 3) $namaAktuator = 'Pompa Sirkulasi';

                                    // 2. Terjemahkan Status Aksi (on -> Dinyalakan)
                                    $statusAksi = strtolower($log->action) == 'on' ? 'Dinyalakan' : 'Dimatikan';
                                    $judulLog = $namaAktuator . ' ' . $statusAksi;

                                    // 3. Buat kalimat detail berdasarkan mode trigger
                                    $detailLog = 'Sistem mengeksekusi perintah secara ' . $log->trigger_mode . ' untuk ' . strtolower($namaAktuator) . '.';

                                    // 4. Logika warna untuk 24 jam terakhir
                                    $isRecent = \Carbon\Carbon::parse($log->executed_at)->diffInHours() < 24;
                                    $dotColor = $isRecent ? 'bg-[#388e3c]' : 'bg-gray-400';
                                    $ringColor = $isRecent ? 'ring-[#c8e6c9]' : 'ring-gray-200';
                                @endphp
                                <div class="relative pl-6">
                                    <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $dotColor }} ring-4 {{ $ringColor }}"></span>
                                    
                                    <h4 class="text-[14px] font-bold text-gray-900">{{ $judulLog }}</h4>
                                    
                                    <p class="text-[13px] text-gray-600 mt-1 leading-relaxed">
                                        {{ $detailLog }}
                                    </p>
                                    
                                    <p class="text-[9px] text-gray-500 font-bold uppercase tracking-wider mt-2">
                                        <!-- Menggunakan executed_at -->
                                        {{ \Carbon\Carbon::parse($log->executed_at)->diffForHumans() }}
                                    </p>
                                </div>
                            @empty
                                <div class="pl-6 text-[13px] text-gray-500 font-medium">Belum ada aktivitas aktuator.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (Lebar 1/3) -->
                <div class="flex flex-col gap-6">
                    
                    <!-- Pengaturan Ambang Batas (100% Sesuai Gambar) -->
                    <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-[#2e7d32] text-white px-6 py-4 flex items-center gap-2">
                            <i class="ph ph-sliders-horizontal text-xl"></i>
                            <h3 class="text-[14px] font-bold">Atur Ambang Batas</h3>
                        </div>
                        
                        <form action="{{ route('sensor.update-batas') }}" method="POST" class="p-6">
                            @csrf
                            
                            @if(session('success'))
                                <div class="bg-[#e4f5e1] text-[#2e7d32] px-4 py-3 rounded-xl text-[12px] font-bold mb-5 flex items-center gap-2">
                                    <i class="ph-fill ph-check-circle text-[16px]"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Area Drag pH -->
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-[12px] font-bold text-gray-800">Batas pH</label>
                                    <span class="bg-[#f3f4f6] text-gray-600 text-[10px] font-bold px-2 py-1 rounded-md">
                                        <span id="ph_display_min">{{ old('ph_min', $ambangBatas['ph_min']) }}</span> - 
                                        <span id="ph_display_max">{{ old('ph_max', $ambangBatas['ph_max']) }}</span>
                                    </span>
                                </div>
                                
                                <div class="slider-container">
                                    <div id="ph_track" class="slider-track bg-[#2e7d32]"></div>
                                    <input type="range" name="ph_min" id="ph_min" min="0" max="14" step="0.1" value="{{ old('ph_min', $ambangBatas['ph_min']) }}" class="range-input thumb-ph">
                                    <input type="range" name="ph_max" id="ph_max" min="0" max="14" step="0.1" value="{{ old('ph_max', $ambangBatas['ph_max']) }}" class="range-input thumb-ph">
                                </div>

                                <div class="flex gap-4 mt-4">
                                    <div class="bg-[#f4faf2] py-2 px-4 rounded-xl flex-1 text-center font-bold text-gray-800 text-[13px]" id="ph_text_min">{{ old('ph_min', $ambangBatas['ph_min']) }}</div>
                                    <div class="bg-[#f4faf2] py-2 px-4 rounded-xl flex-1 text-center font-bold text-gray-800 text-[13px]" id="ph_text_max">{{ old('ph_max', $ambangBatas['ph_max']) }}</div>
                                </div>
                            </div>

                            <!-- Area Drag TDS -->
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-[12px] font-bold text-gray-800">Batas TDS (PPM)</label>
                                    <span class="bg-[#f3f4f6] text-gray-600 text-[10px] font-bold px-2 py-1 rounded-md">
                                        <span id="tds_display_min">{{ old('tds_min', $ambangBatas['tds_min']) }}</span> - 
                                        <span id="tds_display_max">{{ old('tds_max', $ambangBatas['tds_max']) }}</span>
                                    </span>
                                </div>
                                
                                <div class="slider-container">
                                    <div id="tds_track" class="slider-track bg-[#6d4c41]"></div>
                                    <input type="range" name="tds_min" id="tds_min" min="0" max="2000" step="10" value="{{ old('tds_min', $ambangBatas['tds_min']) }}" class="range-input thumb-tds">
                                    <input type="range" name="tds_max" id="tds_max" min="0" max="2000" step="10" value="{{ old('tds_max', $ambangBatas['tds_max']) }}" class="range-input thumb-tds">
                                </div>

                                <div class="flex gap-4 mt-4">
                                    <div class="bg-[#f4faf2] py-2 px-4 rounded-xl flex-1 text-center font-bold text-gray-800 text-[13px]" id="tds_text_min">{{ old('tds_min', $ambangBatas['tds_min']) }}</div>
                                    <div class="bg-[#f4faf2] py-2 px-4 rounded-xl flex-1 text-center font-bold text-gray-800 text-[13px]" id="tds_text_max">{{ old('tds_max', $ambangBatas['tds_max']) }}</div>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-[#2e7d32] hover:bg-[#1b5e20] text-white text-[13px] font-bold py-3.5 rounded-xl transition-colors flex items-center justify-center gap-2 shadow-sm">
                                <i class="ph ph-floppy-disk"></i> Simpan Pengaturan
                            </button>
                        </form>
                    </div>

                    <!-- Stabilitas Nutrisi -->
                    <div class="bg-[#eef5eb] rounded-[1.5rem] p-6 border border-[#e2ebd9]">
                        <h3 class="text-[15px] font-bold text-gray-900 mb-5">Stabilitas Nutrisi</h3>
                        
                        <div class="space-y-4">
                            <!-- Bar pH -->
                            <div class="bg-white p-4 rounded-xl shadow-sm">
                                <div class="flex justify-between text-[11px] font-bold mb-2">
                                    <span class="text-gray-600">Stabilitas pH</span>
                                    <!-- Label dan Warna Otomatis dari Controller -->
                                    <span class="{{ $stabilitas['ph_color'] }} transition-colors">{{ $stabilitas['ph_label'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                    <!-- Lebar Bar (Persentase) Otomatis dari Controller -->
                                    <div class="{{ $stabilitas['ph_bg'] }} h-1.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $stabilitas['ph_persen'] }}%"></div>
                                </div>
                            </div>

                            <!-- Bar TDS -->
                            <div class="bg-white p-4 rounded-xl shadow-sm">
                                <div class="flex justify-between text-[11px] font-bold mb-2">
                                    <span class="text-gray-600">Stabilitas TDS</span>
                                    <!-- Label dan Warna Otomatis dari Controller -->
                                    <span class="{{ $stabilitas['tds_color'] }} transition-colors">{{ $stabilitas['tds_label'] }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                    <!-- Lebar Bar (Persentase) Otomatis dari Controller -->
                                    <div class="{{ $stabilitas['tds_bg'] }} h-1.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $stabilitas['tds_persen'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Script Dropdown & Sticky Navbar -->
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
        profileBtn.addEventListener('click', e => {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', e => {
            if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        // Double Slider
        function setupDualSlider(minId, maxId, trackId, displayMinId, displayMaxId, textMinId, textMaxId, limitMin, limitMax) {
            const minEl = document.getElementById(minId);
            const maxEl = document.getElementById(maxId);
            const track = document.getElementById(trackId);

            function updateUI() {
                let minVal = parseFloat(minEl.value);
                let maxVal = parseFloat(maxEl.value);

                let percent1 = ((minVal - limitMin) / (limitMax - limitMin)) * 100;
                let percent2 = ((maxVal - limitMin) / (limitMax - limitMin)) * 100;

                track.style.left = percent1 + "%";
                track.style.width = (percent2 - percent1) + "%";

                document.getElementById(displayMinId).innerText = minVal;
                document.getElementById(displayMaxId).innerText = maxVal;
                document.getElementById(textMinId).innerText = minVal;
                document.getElementById(textMaxId).innerText = maxVal;
            }

            minEl.addEventListener('input', () => {
                if (parseFloat(minEl.value) >= parseFloat(maxEl.value)) minEl.value = maxEl.value; 
                updateUI();
            });
            
            maxEl.addEventListener('input', () => {
                if (parseFloat(maxEl.value) <= parseFloat(minEl.value)) maxEl.value = minEl.value;
                updateUI();
            });

            updateUI();
        }

        setupDualSlider('ph_min', 'ph_max', 'ph_track', 'ph_display_min', 'ph_display_max', 'ph_text_min', 'ph_text_max', 0, 14);
        setupDualSlider('tds_min', 'tds_max', 'tds_track', 'tds_display_min', 'tds_display_max', 'tds_text_min', 'tds_text_max', 0, 2000);

        // Fetch Data Terbaru
        setInterval(function() {
            fetch('/api/data-terbaru')
                .then(response => response.json())
                .then(data => {
                    let tdsSensor = document.getElementById('live-tds-sensor');
                    let phSensor = document.getElementById('live-ph-sensor');
                    let statusContainer = document.getElementById('status-container');
                    let statusIcon = document.getElementById('status-icon');
                    let statusText = document.getElementById('status-text');

                    if(tdsSensor) tdsSensor.innerText = data.tds;
                    if(phSensor) phSensor.innerText = data.ph;

                    if(statusContainer && data.status) {
                        statusContainer.className = `${data.status.bg} ${data.status.teks} px-4 py-2 rounded-full flex items-center gap-2 text-[13px] font-bold transition-all duration-500`;
                    }
                    if(statusIcon && data.status) {
                        statusIcon.className = `ph-fill ${data.status.ikon} text-lg`;
                    }
                    if(statusText && data.status) {
                        statusText.innerText = data.status.label;
                    }
                })
                .catch(error => console.error('Gagal mengambil data:', error));
        }, 3000);
    </script>
</body>
</html>
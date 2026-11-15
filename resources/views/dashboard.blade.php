<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Farm Dashboard - Pixel Plant</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800&family=Pixelify+Sans:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Pixelify Sans', 'Cairo', sans-serif;
            background-image: linear-gradient(127deg, rgb(237, 255, 201) 0%, rgb(245, 255, 226) 50%, rgb(224, 255, 166) 100%);
            min-height: 100vh;
            color: #141414;
            overflow-x: hidden;
        }

        /* شكل الحواف البيكسلية */
        .pixel-box {
            clip-path: polygon(
                0px 12px, 4px 12px, 4px 8px, 8px 8px, 8px 4px, 12px 4px, 12px 0px,
                calc(100% - 12px) 0px, calc(100% - 12px) 4px, calc(100% - 8px) 4px, calc(100% - 8px) 8px, calc(100% - 4px) 8px, calc(100% - 4px) 12px, 100% 12px,
                100% calc(100% - 12px), calc(100% - 4px) calc(100% - 12px), calc(100% - 4px) calc(100% - 8px), calc(100% - 8px) calc(100% - 8px), calc(100% - 8px) calc(100% - 4px), calc(100% - 12px) calc(100% - 4px), calc(100% - 12px) 100%,
                12px 100%, 12px calc(100% - 4px), 8px calc(100% - 4px), 8px calc(100% - 8px), 4px calc(100% - 8px), 4px calc(100% - 12px), 0px calc(100% - 12px)
            );
        }

        .pixel-border-wrap {
            background-color: #1a1a1a;
            padding: 3px;
            transition: transform 0.15s steps(2), background-color 0.15s steps(2);
        }

        .pixel-border-wrap:hover {
            transform: translateY(-4px);
            background-color: #6b9654;
        }

        .pixel-card-inner {
            background-color: #ffffff;
            height: 100%;
            padding: 20px;
        }

        .pixel-nav {
            background-color: rgba(255, 255, 255, 0.92);
            border-bottom: 4px solid #1a1a1a;
        }

        .ar-text {
            font-family: 'Cairo', sans-serif;
        }

        /* رؤوس الشخصيات الطائرة على الجوانب */
        .floating-head {
            position: fixed;
            z-index: 40;
            width: 76px;
            height: 76px;
            background-color: #1a1a1a;
            padding: 3px;
            cursor: pointer;
            transition: transform 0.15s steps(2);
            filter: drop-shadow(0 6px 0 rgba(0, 0, 0, 0.35));
        }

        .floating-head:hover {
            transform: scale(1.1) rotate(-4deg);
            background-color: #6b9654;
        }

        .floating-head img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            image-rendering: pixelated;
            background-color: #cff982;
        }

        .float-anim-1 {
            animation: bobbing 2.6s steps(2, end) infinite;
        }

        .float-anim-2 {
            animation: bobbing 3.2s steps(2, end) infinite reverse;
        }

        @keyframes bobbing {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        /* حاوية البطاقة مع التاب المنبثق وقت الهافر */
        .crop-card-container {
            position: relative;
        }

        .crop-hover-tab {
            position: absolute;
            bottom: calc(100% + 14px);
            left: 0;
            right: 0;
            z-index: 50;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: opacity 0.15s steps(2), transform 0.15s steps(2), visibility 0.15s;
            pointer-events: none;
            filter: drop-shadow(0 8px 0 rgba(0, 0, 0, 0.4));
        }

        .crop-card-container:hover .crop-hover-tab {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .crop-hover-inner {
            background-color: #ffffff;
            padding: 14px 16px;
            text-align: right;
        }

        .crop-hover-arrow {
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 14px;
            height: 8px;
            background-color: #1a1a1a;
            clip-path: polygon(0 0, 100% 0, 50% 100%);
        }

        /* رأس الشخصية الطائرة فوق تاب الهافر */
        .hover-char-head {
            position: absolute;
            top: -36px;
            left: 16px;
            width: 56px;
            height: 56px;
            background-color: #1a1a1a;
            padding: 3px;
            z-index: 60;
            filter: drop-shadow(0 4px 0 rgba(0, 0, 0, 0.35));
            animation: bobbing 2.2s steps(2, end) infinite;
        }

        .hover-char-head img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            image-rendering: pixelated;
        }

        /* بطاقات الاستراتيجيات/المحاصيل بستايل اللعبة */
        .strategy-card-wrap {
            background-color: #1a1a1a;
            padding: 4px;
            transition: transform 0.15s steps(2), background-color 0.15s steps(2);
            filter: drop-shadow(0 8px 0 #000000);
            cursor: pointer;
        }

        .strategy-card-wrap:hover {
            transform: translateY(-6px);
        }

        .strategy-card-wrap.selected {
            background-color: #cff982;
            transform: translateY(-6px);
            filter: drop-shadow(0 10px 0 #1a1a1a);
        }

        .strategy-card {
            height: 100%;
            padding: 14px 12px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            border: 3px solid rgba(255, 255, 255, 0.25);
        }

        .theme-profit {
            background: linear-gradient(180deg, #9c6615 0%, #c88a22 45%, #7f4f12 100%);
        }

        .theme-soil {
            background: linear-gradient(180deg, #1b4332 0%, #2d6a4f 45%, #143627 100%);
        }

        .theme-balance {
            background: linear-gradient(90deg, #2d6a4f 0%, #2d6a4f 50%, #c88a22 50%, #c88a22 100%);
        }

        .strategy-title {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            text-shadow: 2px 2px 0 #000000, -1px -1px 0 #000000;
            margin-bottom: 10px;
        }

        .strategy-art-box {
            width: 100%;
            height: 165px;
            background-color: rgba(0, 0, 0, 0.22);
            border: 3px solid #1a1a1a;
            margin-bottom: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .strategy-art-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            image-rendering: pixelated;
        }

        .strategy-desc-bar {
            width: 100%;
            background-color: rgba(0, 0, 0, 0.45);
            border: 2px solid #1a1a1a;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            padding: 5px 8px;
            margin-bottom: 12px;
        }

        .strategy-select-btn {
            background-color: #3a7ca5;
            color: #ffffff;
            border: 3px solid #1a1a1a;
            box-shadow: inset 0 3px 0 rgba(255, 255, 255, 0.35), 0 4px 0 #1a1a1a;
            padding: 6px 28px;
            font-size: 15px;
            font-weight: 800;
            transition: background-color 0.1s steps(2), transform 0.1s steps(2);
        }

        .strategy-card-wrap:hover .strategy-select-btn {
            background-color: #4ea8de;
        }

        .strategy-card-wrap.selected .strategy-select-btn {
            background-color: #52b788;
            color: #000000;
        }
    </style>
</head>

<body class="antialiased relative">

    <!-- رؤوس شخصيات طائرة على الجوانب -->
    <div class="hidden xl:block floating-head pixel-box float-anim-1" style="top: 130px; left: 22px;" title="Character 1">
        <img src="https://placehold.co/80x80/cff982/1a1a1a?text=Head+1" alt="Floating Head 1" class="pixel-box">
    </div>
    <div class="hidden xl:block floating-head pixel-box float-anim-2" style="bottom: 140px; left: 28px;" title="Character 2">
        <img src="https://placehold.co/80x80/e0fbfc/1a1a1a?text=Head+2" alt="Floating Head 2" class="pixel-box">
    </div>
    <div class="hidden xl:block floating-head pixel-box float-anim-2" style="top: 150px; right: 22px;" title="Character 3">
        <img src="https://placehold.co/80x80/ffe5ec/1a1a1a?text=Head+3" alt="Floating Head 3" class="pixel-box">
    </div>
    <div class="hidden xl:block floating-head pixel-box float-anim-1" style="bottom: 130px; right: 28px;" title="Character 4">
        <img src="https://placehold.co/80x80/fff3b0/1a1a1a?text=Head+4" alt="Floating Head 4" class="pixel-box">
    </div>

    <div class="min-h-screen flex flex-col">

        <!-- Navbar -->
        <nav class="pixel-nav px-6 py-3 flex items-center justify-between sticky top-0 z-50">
            <div class="flex items-center gap-3">
                <span class="inline-block w-4 h-4 bg-[#6b9654] border-2 border-black"></span>
                <span class="text-xl font-bold tracking-wider text-black">AGRO-PIXEL</span>
                @if(isset($location))
                    <span class="hidden md:inline-block bg-[#cff982] border-2 border-black px-3 py-0.5 text-xs font-bold">
                        LAT: {{ $location->latitude }} | LON: {{ $location->longitude }}
                    </span>
                @endif
            </div>

            <div class="flex items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border-2 border-black text-sm leading-4 font-bold text-black bg-[#cff982] hover:bg-[#b5eb59] focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8 max-w-6xl w-full mx-auto space-y-8">

            @php
                $latest = isset($records) && $records->count() > 0 ? $records->last() : null;

                $clay = $latest->clay ?? 25;
                $sand = $latest->sand ?? 45;
                $silt = $latest->silt ?? 30;
                $totalSoil = ($clay + $sand + $silt) > 0 ? ($clay + $sand + $silt) : 100;

                $clayPct = round(($clay / $totalSoil) * 100, 1);
                $sandPct = round(($sand / $totalSoil) * 100, 1);
                $siltPct = round(100 - $clayPct - $sandPct, 1);

                $stop1 = $clayPct;
                $stop2 = $clayPct + $sandPct;
            @endphp

            <!-- 1. قسم الإحصائيات السريعة (4 مربعات) -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-black flex items-center gap-2">
                        <span class="w-3 h-3 bg-black inline-block"></span>
                        LAND METRICS & STATS
                    </h2>
                    @if(Auth::user()->last_crop)
                        <span class="ar-text bg-black text-white px-4 py-1 text-sm font-bold pixel-box">
                            آخر محصول: {{ Auth::user()->last_crop }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- مربع 1 -->
                    <div class="pixel-border-wrap pixel-box">
                        <div class="pixel-card-inner pixel-box flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-bold text-gray-500 uppercase">Temperature</span>
                                <span class="bg-[#ffe5ec] text-[#c9184a] border border-black px-2 py-0.5 text-xs font-bold">T2M</span>
                            </div>
                            <div class="my-4">
                                <span class="text-4xl font-extrabold text-black">{{ $latest->t2m ?? '24.5' }}°C</span>
                                <p class="text-xs text-gray-500 mt-1">Max: {{ $latest->max ?? '31.2' }}°C</p>
                            </div>
                            <div class="w-full bg-gray-200 h-2 border border-black">
                                <div class="bg-[#ff4d6d] h-full" style="width: 68%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- مربع 2 -->
                    <div class="pixel-border-wrap pixel-box">
                        <div class="pixel-card-inner pixel-box flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-bold text-gray-500 uppercase">Soil Moisture</span>
                                <span class="bg-[#e0fbfc] text-[#0077b6] border border-black px-2 py-0.5 text-xs font-bold">WATER</span>
                            </div>
                            <div class="my-4">
                                <span class="text-4xl font-extrabold text-black">{{ $latest ? round($latest->soil_moisture * 100, 1) : '18.4' }}%</span>
                                <p class="text-xs text-gray-500 mt-1">Humidity: {{ $latest->RH2M ?? '51.2' }}%</p>
                            </div>
                            <div class="w-full bg-gray-200 h-2 border border-black">
                                <div class="bg-[#00b4d8] h-full" style="width: 55%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- مربع 3 -->
                    <div class="pixel-border-wrap pixel-box">
                        <div class="pixel-card-inner pixel-box flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-bold text-gray-500 uppercase">Vegetation</span>
                                <span class="bg-[#cff982] text-black border border-black px-2 py-0.5 text-xs font-bold">NDVI</span>
                            </div>
                            <div class="my-4">
                                <span class="text-4xl font-extrabold text-black">{{ $latest->NDVI ?? '0.58' }}</span>
                                <p class="text-xs text-gray-500 mt-1">Elevation: {{ $latest->elevation_m ?? '42' }}m</p>
                            </div>
                            <div class="w-full bg-gray-200 h-2 border border-black">
                                <div class="bg-[#52b788] h-full" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- مربع 4 -->
                    <div class="pixel-border-wrap pixel-box">
                        <div class="pixel-card-inner pixel-box flex flex-col justify-between">
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-bold text-gray-500 uppercase">Soil Acidity</span>
                                <span class="bg-[#fff3b0] text-[#9e2a2b] border border-black px-2 py-0.5 text-xs font-bold">pH</span>
                            </div>
                            <div class="my-4">
                                <span class="text-4xl font-extrabold text-black">{{ $latest->ph ?? '6.8' }}</span>
                                <p class="text-xs text-gray-500 mt-1">Organic Matter: {{ $latest->om ?? '1.2' }}%</p>
                            </div>
                            <div class="w-full bg-gray-200 h-2 border border-black">
                                <div class="bg-[#f4a261] h-full" style="width: 62%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. صف الرسوم البيانية -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pie Chart -->
                <div class="pixel-border-wrap pixel-box">
                    <div class="pixel-card-inner pixel-box flex flex-col justify-between">
                        <div class="flex items-center justify-between border-b-2 border-dashed border-gray-300 pb-2 mb-4">
                            <h3 class="text-xl font-bold text-black">SOIL PIE CHART</h3>
                            <span class="bg-[#cff982] border border-black px-2 py-0.5 text-xs font-bold">RATIO %</span>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-around gap-6 my-2">
                            <div class="relative w-44 h-44 rounded-full border-4 border-black shadow-[4px_4px_0px_#1a1a1a] flex items-center justify-center shrink-0"
                                style="background: conic-gradient(#bc6c25 0% {{ $stop1 }}%, #dda15e {{ $stop1 }}% {{ $stop2 }}%, #606c38 {{ $stop2 }}% 100%);">
                                <div class="w-20 h-20 bg-white rounded-full border-4 border-black flex flex-col items-center justify-center">
                                    <span class="text-xs font-bold text-gray-500">SOIL</span>
                                    <span class="text-sm font-extrabold text-black">100%</span>
                                </div>
                            </div>

                            <div class="space-y-3 w-full sm:w-auto">
                                <div class="flex items-center justify-between gap-4 border-2 border-black px-3 py-1.5 bg-[#fefae0]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 bg-[#bc6c25] border border-black inline-block"></span>
                                        <span class="font-bold text-sm">Clay (طين)</span>
                                    </div>
                                    <span class="font-extrabold text-sm">{{ $clayPct }}%</span>
                                </div>

                                <div class="flex items-center justify-between gap-4 border-2 border-black px-3 py-1.5 bg-[#fefae0]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 bg-[#dda15e] border border-black inline-block"></span>
                                        <span class="font-bold text-sm">Sand (رمل)</span>
                                    </div>
                                    <span class="font-extrabold text-sm">{{ $sandPct }}%</span>
                                </div>

                                <div class="flex items-center justify-between gap-4 border-2 border-black px-3 py-1.5 bg-[#fefae0]">
                                    <div class="flex items-center gap-2">
                                        <span class="w-4 h-4 bg-[#606c38] border border-black inline-block"></span>
                                        <span class="font-bold text-sm">Silt (طمي)</span>
                                    </div>
                                    <span class="font-extrabold text-sm">{{ $siltPct }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-2 border-t-2 border-gray-200 flex justify-between text-xs font-bold text-gray-600">
                            <span>Ksat: {{ $latest->ksat ?? '14' }} mm/hr</span>
                            <span>Slope: {{ $latest->slope_deg ?? '2.4' }}°</span>
                        </div>
                    </div>
                </div>

                <!-- Bar Chart -->
                <div class="pixel-border-wrap pixel-box">
                    <div class="pixel-card-inner pixel-box flex flex-col justify-between">
                        <div class="flex items-center justify-between border-b-2 border-dashed border-gray-300 pb-2 mb-4">
                            <h3 class="text-xl font-bold text-black">CLIMATE & ENERGY BARS</h3>
                            <span class="bg-[#e0fbfc] border border-black px-2 py-0.5 text-xs font-bold">OVERVIEW</span>
                        </div>

                        <div class="flex items-end justify-around h-44 pt-6 pb-2 px-4 border-b-4 border-black bg-[#f8fff0]">
                            <div class="flex flex-col items-center gap-2 w-16">
                                <span class="text-xs font-extrabold">{{ $latest->ALLSKY_SFC_SW_DWN ?? '25.1' }}</span>
                                <div class="w-10 bg-[#ffb703] border-2 border-black transition-all" style="height: 105px;"></div>
                                <span class="text-xs font-bold">SOLAR</span>
                            </div>

                            <div class="flex flex-col items-center gap-2 w-16">
                                <span class="text-xs font-extrabold">{{ $latest->RH2M ?? '51.2' }}%</span>
                                <div class="w-10 bg-[#00b4d8] border-2 border-black transition-all" style="height: 85px;"></div>
                                <span class="text-xs font-bold">HUMID</span>
                            </div>

                            <div class="flex flex-col items-center gap-2 w-16">
                                <span class="text-xs font-extrabold">{{ $latest->ET_mm ?? '14.1' }}</span>
                                <div class="w-10 bg-[#52b788] border-2 border-black transition-all" style="height: 65px;"></div>
                                <span class="text-xs font-bold">ET_MM</span>
                            </div>

                            <div class="flex flex-col items-center gap-2 w-16">
                                <span class="text-xs font-extrabold">{{ $latest->precipitation_mm ?? '0.03' }}</span>
                                <div class="w-10 bg-[#4361ee] border-2 border-black transition-all" style="height: 35px;"></div>
                                <span class="text-xs font-bold">RAIN</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-2 flex justify-between text-xs font-bold text-gray-600">
                            <span>Records Count: {{ isset($records) ? $records->count() : 0 }}</span>
                            <span class="text-[#6b9654]">● LIVE DATA</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. قسم البطاقات الثلاث (نتائج موديل AI + تاب بيانات وقت الهافر) -->
            <div class="pt-4" dir="rtl">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-5">
                    <h3 class="ar-text text-xl font-extrabold text-black flex items-center gap-2">
                        <span class="w-3 h-3 bg-[#6b9654] border-2 border-black inline-block"></span>
                        إختر استراتيجيتك الزراعية القادمة (توصيات الـ AI):
                    </h3>

                    <div class="flex items-center gap-2">
                        @if(isset($apiError) && $apiError)
                            <span class="ar-text text-xs font-bold bg-[#ffe5ec] text-[#c9184a] border-2 border-black px-3 py-1">
                                {{ $apiError }}
                            </span>
                        @endif
                        <span class="ar-text text-xs font-bold bg-white border-2 border-black px-3 py-1">
                            المحصول الحالي: <span class="text-[#2d6a4f]">{{ Auth::user()->last_crop ?? 'طماطم' }}</span>
                        </span>
                    </div>
                </div>

                @php
                    $cropSuggestions = $suggestions ?? [
                        [
                            'id'         => 'max_profit',
                            'title'      => 'الربح القصوى',
                            'crop_name'  => 'خيار (Cucumber)',
                            'theme'      => 'theme-profit',
                            'art_img'    => asset('ooo.jpg'),
                            'short_desc' => 'استراتيجية التركيز على المال (خيار)',
                            'badge'      => 'ملاءمة 94%',
                            'badge_bg'   => '#cff982',
                            'char_img'   => 'https://placehold.co/64x64/fff3b0/1a1a1a?text=Ice+King',
                            'char_name'  => 'ملك الجليد',
                            'duration'   => '45 - 60 يوم',
                            'water'      => 'متوسط - مرتفع',
                            'temp'       => '20°C - 30°C',
                            'reason'     => 'أسرع دورة رأس مال بعد الطماطم وطلب مرتفع في السوق مع إنتاجية عالية للمتر المربع.'
                        ],
                        [
                            'id'         => 'soil_health',
                            'title'      => 'تحسين التربة',
                            'crop_name'  => 'جزر (Carrot)',
                            'theme'      => 'theme-soil',
                            'art_img'    => asset('Bmo.jpg'),
                            'short_desc' => 'استراتيجية التركيز على صحة الأرض (جزر)',
                            'badge'      => 'ملاءمة 89%',
                            'badge_bg'   => '#fff3b0',
                            'char_img'   => 'https://placehold.co/64x64/cff982/1a1a1a?text=Fern',
                            'char_name'  => 'فيرن',
                            'duration'   => '70 - 80 يوم',
                            'water'      => 'متوسط (منتظم)',
                            'temp'       => '16°C - 24°C',
                            'reason'     => 'محصول جذري يفكك التربة الطينية ويكسر دورة أمراض الطماطم ويعيد التوازن العضوي.'
                        ],
                        [
                            'id'         => 'perfect_balance',
                            'title'      => 'التوازن المثالي',
                            'crop_name'  => 'فلفل ألوان (Bell Pepper)',
                            'theme'      => 'theme-balance',
                            'art_img'    => asset('hhh.jpg'),
                            'short_desc' => 'استراتيجية التوازن بين المال والأرض (فلفل)',
                            'badge'      => 'ملاءمة 85%',
                            'badge_bg'   => '#e0fbfc',
                            'char_img'   => 'https://placehold.co/64x64/e0fbfc/1a1a1a?text=Huntress',
                            'char_name'  => 'الصيادة',
                            'duration'   => '65 - 85 يوم',
                            'water'      => 'متوسط',
                            'temp'       => '21°C - 32°C',
                            'reason'     => 'يحقق توازناً ممتازاً بين الربحية الجيدة والحفاظ على خصوبة التربة واستقرار pH.'
                        ],
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($cropSuggestions as $crop)
                        <div class="crop-card-container">
                            
                            <!-- تاب البيانات المنبثق وقت الهافر بالماوس -->
                            <div class="crop-hover-tab">
                                <!-- رأس الشخصية الطائر فوق التاب -->
                                <div class="hover-char-head pixel-box">
                                    <img src="{{ $crop['char_img'] }}" alt="{{ $crop['char_name'] }}" class="pixel-box">
                                </div>

                                <div class="pixel-box" style="background-color: #1a1a1a; padding: 3px;">
                                    <div class="crop-hover-inner pixel-box ar-text space-y-2 pt-5">
                                        <div class="flex items-center justify-between border-b-2 border-dashed border-gray-300 pb-1.5">
                                            <span class="font-extrabold text-sm text-black">رأي {{ $crop['char_name'] }}:</span>
                                            <span class="text-xs font-bold px-2 py-0.5 border border-black" style="background-color: {{ $crop['badge_bg'] }}">
                                                {{ $crop['badge'] }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                                            <div class="bg-[#f8fff0] border border-black p-1.5">
                                                <span class="text-gray-500 block">مدة الحصاد:</span>
                                                <span class="font-extrabold text-black">{{ $crop['duration'] }}</span>
                                            </div>
                                            <div class="bg-[#e0fbfc] border border-black p-1.5">
                                                <span class="text-gray-500 block">الري المطلوب:</span>
                                                <span class="font-extrabold text-black">{{ $crop['water'] }}</span>
                                            </div>
                                        </div>

                                        <div class="bg-[#fefae0] border border-black p-1.5 text-xs">
                                            <span class="text-gray-500">الحرارة المثالية: </span>
                                            <span class="font-extrabold text-black" dir="ltr">{{ $crop['temp'] }}</span>
                                        </div>

                                        <p class="text-xs text-gray-700 leading-relaxed pt-1">
                                            <strong class="text-black">لماذا نرشحه؟</strong> {{ $crop['reason'] }}
                                        </p>
                                    </div>
                                </div>

                                <div class="crop-hover-arrow"></div>
                            </div>

                            <!-- البطاقة الرئيسية بستايل اللعبة -->
                            <div onclick="selectStrategy(this, '{{ $crop['crop_name'] ?? '' }}')" class="strategy-card-wrap pixel-box h-full">
                                <div class="strategy-card pixel-box {{ $crop['theme'] }} ar-text">
                                    
                                    <h4 class="strategy-title">{{ $crop['title'] }}</h4>

                                    <div class="strategy-art-box pixel-box">
                                        <img src="{{ $crop['art_img'] }}" alt="{{ $crop['title'] }}">
                                    </div>

                                    <div class="strategy-desc-bar pixel-box">
                                        {{ $crop['short_desc'] }}
                                    </div>

                                    <button type="button" class="strategy-select-btn pixel-box">
                                        اختر هذه
                                    </button>

                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <script>
                function selectStrategy(cardElement, cropName) {
                    document.querySelectorAll('.strategy-card-wrap').forEach(card => {
                        card.classList.remove('selected');
                        card.querySelector('.strategy-select-btn').textContent = 'اختر هذه';
                    });

                    cardElement.classList.add('selected');
                    cardElement.querySelector('.strategy-select-btn').textContent = 'تم الاختيار ✓';
                    
                    console.log('الاستراتيجية / المحصول المختار:', cropName);
                }
            </script>

        </main>
    </div>
</body>

</html>
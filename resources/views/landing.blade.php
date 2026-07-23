<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('panel.organization', 'Jharkhand State Housing Board') }}</title>
    <!-- Tailwind CSS (via NPM / Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(config('panel.faviconIcon', 'favicon.ico')) }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-emerald-500 selection:text-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/85 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <img src="{{ asset(config('panel.logo')) }}" alt="JSHB Logo" class="h-12 w-12 object-contain bg-white rounded-full p-1 shadow-sm border border-emerald-100" onerror="this.src='https://placehold.co/80x80/ffffff/1f7b4d?text=JH'">
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-emerald-700 leading-tight">{{ config('panel.organization_hindi', 'झारखण्ड राज्य आवास बोर्ड') }}</span>
                        <span class="text-sm md:text-lg font-bold text-slate-900 tracking-tight leading-tight">{{ config('panel.organization', 'Jharkhand State Housing Board') }}</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#about" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">About Us</a>
                    <a href="#schemes" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">Schemes</a>
                    <a href="#contact" class="text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">Contact</a>
                    <a href="{{ route('login') }}" class="group relative inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-full overflow-hidden transition-all hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-200">
                        <span>Member Portal</span>
                        <i class="fa-solid fa-arrow-right-to-bracket group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
                <!-- Mobile Login Button -->
                <div class="md:hidden">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-1 px-4 py-2 text-xs font-bold text-white bg-emerald-600 rounded-full hover:bg-emerald-700 shadow-sm">
                        Login <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden min-h-screen flex items-center">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-slate-50 z-[-2]"></div>
        <div class="absolute inset-0 opacity-[0.15] z-[-1]" style="background-image: radial-gradient(#10b981 1px, transparent 1px); background-size: 32px 32px;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative w-full">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wide mb-6 shadow-sm border border-emerald-200">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-600"></span>
                        </span>
                        Official Portal Live
                    </div>
                    <h1 class="font-heading text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                        Building Homes, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Building Jharkhand.</span>
                    </h1>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                        The comprehensive digital platform for allotments, housing schemes, and public works management. Access your member portal for a seamless digital experience.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-bold text-white bg-slate-900 rounded-full hover:bg-slate-800 transition-all hover:shadow-xl hover:shadow-slate-300 hover:-translate-y-0.5">
                            Access Portal <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="#schemes" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-bold text-slate-700 bg-white border border-slate-200 rounded-full hover:border-emerald-200 hover:bg-emerald-50 transition-all shadow-sm">
                            View Schemes
                        </a>
                    </div>
                </div>
                
                <div class="relative lg:ml-auto w-full max-w-lg mx-auto mt-8 lg:mt-0">
                    <!-- Premium Glass Card Feature -->
                    <div class="absolute -top-6 -left-6 w-32 h-32 bg-emerald-200 rounded-full mix-blend-multiply filter blur-2xl opacity-60 animate-pulse"></div>
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-teal-200 rounded-full mix-blend-multiply filter blur-2xl opacity-60 animate-pulse" style="animation-delay: 1s;"></div>
                    
                    <div class="relative bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl p-6 lg:p-8 shadow-2xl">
                        <img src="{{ asset('img/slider1.png') }}" alt="Housing Board" class="rounded-2xl w-full h-auto object-cover shadow-inner mb-6 border border-slate-100" onerror="this.src='https://placehold.co/600x400/f8fafc/94a3b8?text=Jharkhand+Housing'">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-emerald-50/80 rounded-xl p-4 border border-emerald-100/50 backdrop-blur-sm transition-transform hover:-translate-y-1 cursor-default">
                                <i class="fa-solid fa-building-user text-emerald-600 text-2xl mb-2"></i>
                                <h3 class="font-bold text-slate-900 text-lg">Digital</h3>
                                <p class="text-xs text-slate-600 font-medium">Allotments</p>
                            </div>
                            <div class="bg-blue-50/80 rounded-xl p-4 border border-blue-100/50 backdrop-blur-sm transition-transform hover:-translate-y-1 cursor-default">
                                <i class="fa-solid fa-file-signature text-blue-600 text-2xl mb-2"></i>
                                <h3 class="font-bold text-slate-900 text-lg">Secure</h3>
                                <p class="text-xs text-slate-600 font-medium">Applications</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 pt-16 pb-8 text-slate-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 border-b border-slate-800 pb-8 mb-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset(config('panel.logo')) }}" alt="Logo" class="h-10 w-10 bg-white rounded-full p-1" onerror="this.src='https://placehold.co/80x80/ffffff/1f7b4d?text=JH'">
                    <span class="text-lg font-bold text-white tracking-tight">{{ config('panel.organization', 'Jharkhand State Housing Board') }}</span>
                </div>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-emerald-400 transition-colors"><i class="fa-brands fa-twitter text-xl"></i></a>
                    <a href="#" class="hover:text-emerald-400 transition-colors"><i class="fa-brands fa-facebook text-xl"></i></a>
                    <a href="#" class="hover:text-emerald-400 transition-colors"><i class="fa-solid fa-envelope text-xl"></i></a>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
                <div>&copy; {{ date('Y') }} {{ config('panel.organization', 'Jharkhand State Housing Board') }}. All rights reserved.</div>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-slate-300 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-300 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

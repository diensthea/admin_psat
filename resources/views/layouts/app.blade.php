<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PSAT') - Administrasi Penilaian Sumatif Akhir Tahun</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a8a',
                        secondary: '#3b82f6',
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Navigation Bar -->
    <nav class="bg-blue-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <i class="fa-solid fa-file-signature text-2xl text-yellow-400"></i>
                        <span class="font-extrabold text-xl tracking-wider">PSAT ADMIN</span>
                    </a>
                </div>

                <!-- Navigation Links based on Role -->
                @auth
                    <div class="hidden md:flex space-x-4 items-center">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 {{ Route::is('admin.dashboard') ? 'bg-blue-800' : '' }}">Dashboard</a>
                            <a href="{{ route('admin.guru.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 {{ Route::is('admin.guru.*') ? 'bg-blue-800' : '' }}">Guru</a>
                            <a href="{{ route('admin.siswa.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 {{ Route::is('admin.siswa.*') ? 'bg-blue-800' : '' }}">Siswa</a>
                            <a href="{{ route('admin.kelas.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 {{ Route::is('admin.kelas.*') ? 'bg-blue-800' : '' }}">Kelas</a>
                            <a href="{{ route('admin.penempatan.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 {{ Route::is('admin.penempatan.*') ? 'bg-blue-800' : '' }}">Penempatan</a>
                            <a href="{{ route('admin.mapel.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 {{ Route::is('admin.mapel.*') ? 'bg-blue-800' : '' }}">Mapel</a>
                            <a href="{{ route('admin.berita-acara.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 {{ Route::is('admin.berita-acara.*') ? 'bg-blue-800' : '' }}">Monitoring</a>
                        @elseif(Auth::user()->isGuru())
                            <a href="{{ route('guru.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 {{ Route::is('guru.dashboard') ? 'bg-blue-800' : '' }}">Dashboard</a>
                            <a href="{{ route('guru.berita-acara.create') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 {{ Route::is('guru.berita-acara.create') ? 'bg-blue-800' : '' }}">Buat Berita Acara</a>
                        @elseif(Auth::user()->isSiswa())
                            <a href="{{ route('siswa.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-800 {{ Route::is('siswa.dashboard') ? 'bg-blue-800' : '' }}">Dashboard</a>
                        @endif
                    </div>

                    <!-- User Actions -->
                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-blue-200">Login sebagai ({{ ucfirst(Auth::user()->role) }})</p>
                            <p class="text-sm font-bold text-yellow-300">{{ Auth::user()->name }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-bold transition">
                                <i class="fa-solid fa-right-from-bracket mr-1"></i> Keluar
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Mobile Navigation Menu Toggle (visible on mobil only) -->
    @auth
    <div class="md:hidden bg-blue-800 text-white py-2 px-4 shadow-inner flex overflow-x-auto space-x-3 text-sm scrollbar-none">
        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="flex-shrink-0 px-2 py-1 rounded {{ Route::is('admin.dashboard') ? 'bg-blue-900 border border-yellow-400' : '' }}">Dashboard</a>
            <a href="{{ route('admin.guru.index') }}" class="flex-shrink-0 px-2 py-1 rounded {{ Route::is('admin.guru.*') ? 'bg-blue-900 border border-yellow-400' : '' }}">Guru</a>
            <a href="{{ route('admin.siswa.index') }}" class="flex-shrink-0 px-2 py-1 rounded {{ Route::is('admin.siswa.*') ? 'bg-blue-900 border border-yellow-400' : '' }}">Siswa</a>
            <a href="{{ route('admin.kelas.index') }}" class="flex-shrink-0 px-2 py-1 rounded {{ Route::is('admin.kelas.*') ? 'bg-blue-900 border border-yellow-400' : '' }}">Kelas</a>
            <a href="{{ route('admin.penempatan.index') }}" class="flex-shrink-0 px-2 py-1 rounded {{ Route::is('admin.penempatan.*') ? 'bg-blue-900 border border-yellow-400' : '' }}">Penempatan</a>
            <a href="{{ route('admin.mapel.index') }}" class="flex-shrink-0 px-2 py-1 rounded {{ Route::is('admin.mapel.*') ? 'bg-blue-900 border border-yellow-400' : '' }}">Mapel</a>
            <a href="{{ route('admin.berita-acara.index') }}" class="flex-shrink-0 px-2 py-1 rounded {{ Route::is('admin.berita-acara.*') ? 'bg-blue-900 border border-yellow-400' : '' }}">Monitoring</a>
        @elseif(Auth::user()->isGuru())
            <a href="{{ route('guru.dashboard') }}" class="flex-shrink-0 px-2 py-1 rounded {{ Route::is('guru.dashboard') ? 'bg-blue-900 border border-yellow-400' : '' }}">Dashboard</a>
            <a href="{{ route('guru.berita-acara.create') }}" class="flex-shrink-0 px-2 py-1 rounded {{ Route::is('guru.berita-acara.create') ? 'bg-blue-900 border border-yellow-400' : '' }}">Buat Berita Acara</a>
        @elseif(Auth::user()->isSiswa())
            <a href="{{ route('siswa.dashboard') }}" class="flex-shrink-0 px-2 py-1 rounded {{ Route::is('siswa.dashboard') ? 'bg-blue-900 border border-yellow-400' : '' }}">Dashboard</a>
        @endif
    </div>
    @endauth

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Alerts & Notifications -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex justify-between items-center transition" role="alert">
                <div class="flex items-center">
                    <i class="fa-solid fa-circle-check text-xl mr-3"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-700 font-bold hover:text-green-900">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm flex justify-between items-center transition" role="alert">
                <div class="flex items-center">
                    <i class="fa-solid fa-triangle-exclamation text-xl mr-3"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-700 font-bold hover:text-red-900">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                <div class="flex items-center mb-2 font-bold">
                    <i class="fa-solid fa-triangle-exclamation text-xl mr-3"></i>
                    <span>Terdapat kesalahan input:</span>
                </div>
                <ul class="list-disc list-inside text-sm pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-6 text-center text-sm border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} - Administrasi Penilaian Sumatif Akhir Tahun (PSAT). All Rights Reserved.</p>
            <p class="text-xs mt-1 text-gray-500">Dikembangkan secara digital untuk pengarsipan daftar hadir & berita acara menggunakan tanda tangan digital.</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
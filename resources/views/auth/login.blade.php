@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg border border-gray-150">
        <div>
            <div class="mx-auto h-16 w-16 bg-blue-100 flex items-center justify-center rounded-full text-blue-900">
                <i class="fa-solid fa-file-signature text-3xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-blue-950">
                PSAT Digital Sign
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Administrasi Penilaian Sumatif Akhir Tahun
            </p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username (NIP / NIS)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input id="username" name="username" type="text" autocomplete="username" required 
                            value="{{ old('username') }}"
                            class="appearance-none rounded-lg relative block w-full pl-10 pr-3 py-2.5 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                            placeholder="Masukkan Username">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="appearance-none rounded-lg relative block w-full pl-10 pr-3 py-2.5 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                            placeholder="Masukkan Password">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Ingat Saya
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition shadow">
                    Masuk <i class="fa-solid fa-right-to-bracket ml-2 mt-0.5"></i>
                </button>
            </div>
        </form>

        <div class="text-center text-xs text-gray-500 border-t pt-4">
            <p>Admin default: <span class="font-semibold">admin</span> | Password: <span class="font-semibold">password</span></p>
        </div>
    </div>
</div>
@endsection
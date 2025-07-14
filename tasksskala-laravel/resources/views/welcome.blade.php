@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-8">
                    <h1 class="text-4xl font-bold text-white text-center">Bem-vindo ao Laravel 12</h1>
                </div>
                <div class="p-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-semibold text-gray-800 mb-4">Projeto Laravel com Tailwind CSS</h2>
                        <p class="text-xl text-gray-600 mb-8">Este é um projeto Laravel 12 configurado com Tailwind CSS 3.4.0 sem usar Node.js.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-blue-500 text-white rounded-lg p-6 shadow-md">
                                <h5 class="text-xl font-semibold mb-2">Laravel 12</h5>
                                <p class="text-blue-100">Framework PHP moderno e elegante</p>
                            </div>
                            <div class="bg-green-500 text-white rounded-lg p-6 shadow-md">
                                <h5 class="text-xl font-semibold mb-2">Tailwind CSS</h5>
                                <p class="text-green-100">Framework CSS utility-first</p>
                            </div>
                            <div class="bg-purple-500 text-white rounded-lg p-6 shadow-md">
                                <h5 class="text-xl font-semibold mb-2">Sem Node.js</h5>
                                <p class="text-purple-100">Configuração simples e direta</p>
                            </div>
                        </div>

                        <div class="space-x-4 mb-8">
                            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                Botão Primary
                            </button>
                            <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                Botão Secondary
                            </button>
                            <button type="button" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                Botão Success
                            </button>
                        </div>

                        @if (Route::has('login'))
                            <div class="space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="inline-block bg-white border-2 border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-block bg-white border-2 border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                        Login
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-block bg-white border-2 border-gray-500 text-gray-500 hover:bg-gray-500 hover:text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                            Registrar
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
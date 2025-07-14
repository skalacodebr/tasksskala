<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ColaboradorAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.colaborador-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $colaborador = Colaborador::where('email', $credentials['email'])->first();

        if ($colaborador && Hash::check($credentials['password'], $colaborador->senha)) {
            session(['colaborador_id' => $colaborador->id]);
            session(['colaborador' => $colaborador]);
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['colaborador_id', 'colaborador']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
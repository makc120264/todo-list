<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function showLoginForm(): Factory|View|Application
    {
        return view('auth.login');
    }

    /**
     * @return Factory|View|Application
     */
    public function showRegistrationForm(): Factory|View|Application
    {
        return view('auth.register');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function register(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if ($request->is('api/*')) {
            $token = JWTAuth::fromUser($user);
            return response()->json(['token' => $token]);
        } else {
            return redirect()->route('login')->with('success', 'Registration successful, please login.');
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function login(Request $request): RedirectResponse|JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if ($request->is('api/*')) {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
            return response()->json(['token' => $token]);
        } else {
            if (!Auth::attempt($credentials)) {
                return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
            }

            return redirect()->route('tasks.index');
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->is('api/*')) {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out']);
        }

        Auth::logout();

        return redirect()->route('login')->with('message', 'Successfully logged out');
    }


    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }
}

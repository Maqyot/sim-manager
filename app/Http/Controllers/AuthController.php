<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'account_type' => ['nullable', 'in:staff,student'],
        ]);
        $credentials = $request->only('email', 'password');
        $accountType = $request->input('account_type', 'staff');

        if ($accountType === 'student') {
            $data = $request->validate([
                'student_number' => ['required', 'string'],
                'password' => ['required', 'string'],
            ]);
            $student = Student::where('student_number', $data['student_number'])->first();
            $user = $student?->user;

            if ($student && ! $user && hash_equals($student->course, $data['password'])) {
                if (User::where('email', $student->email)->exists()) {
                    return back()->withErrors(['student_number' => 'Please contact your administrator to set up your student account.'])->withInput($request->only('student_number', 'account_type'));
                }

                $user = User::create([
                    'name' => $student->full_name,
                    'email' => $student->email,
                    'password' => $student->course,
                    'role' => User::ROLE_STUDENT,
                    'student_id' => $student->id,
                ]);
            }

            if (! $user?->isStudent() || ! Hash::check($data['password'], $user->password)) {
                return back()->withErrors(['student_number' => 'The student number or password is incorrect.'])->withInput($request->only('student_number', 'account_type'));
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('student.dashboard');
        }

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput($request->only('email', 'account_type'));
        }

        $user = $request->user();
        if (! $user->isStaff()) {
            Auth::logout();

            return back()->withErrors(['email' => 'Choose the correct login type for this account.'])->withInput($request->only('email', 'account_type'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function showChangePassword(): View
    {
        return view('auth.password');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update(['password' => $request->validated('password')]);

        return redirect()->route($request->user()->isStudent() ? 'student.dashboard' : 'dashboard')->with('success', 'Password updated.');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create($data + ['role' => User::ROLE_STAFF]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Account created. Welcome aboard.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRegistrationRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.student-register');
    }

    public function store(StudentRegistrationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data): void {
            $student = Student::firstOrCreate(
                ['student_number' => $data['student_number']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'course' => $data['course'],
                    'year_level' => $data['year_level'],
                ],
            );

            User::create([
                'name' => $student->full_name,
                'email' => $student->email,
                'password' => $student->course,
                'role' => User::ROLE_STUDENT,
                'student_id' => $student->id,
                'is_approved' => false,
            ]);
        });

        return redirect()->route('login', ['account_type' => 'student'])
            ->with('success', 'Registration submitted. An administrator must approve your account before you can log in.');
    }

    public function approve(User $user): RedirectResponse
    {
        abort_unless($user->isStudent() && ! $user->is_approved, 404);

        $user->forceFill(['is_approved' => true])->save();

        return redirect()->route('dashboard')->with('success', 'Student account approved. The student can now log in.');
    }
}

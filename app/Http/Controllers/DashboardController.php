<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Event;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'studentCount' => Student::count(),
            'pendingStudentRegistrations' => auth()->user()->isAdmin()
                ? User::where('role', User::ROLE_STUDENT)->where('is_approved', false)->with('student')->oldest()->get()
                : collect(),
            'subjectCount' => Subject::count(),
            'enrollmentCount' => Enrollment::count(),
            'statusCounts' => Enrollment::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'courseCounts' => Student::selectRaw('course, count(*) as total')->groupBy('course')->orderByDesc('total')->pluck('total', 'course'),
            'recentEnrollments' => Enrollment::with(['student', 'subject'])->latest()->take(5)->get(),
            'upcomingEvents' => Schema::hasTable('events')
                ? Event::where('starts_at', '>=', now())->orderBy('starts_at')->take(4)->get()
                : collect(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StudentPortalController extends Controller
{
    public function dashboard(Request $request): View
    {
        $student = $request->user()->student;
        $enrollments = $student->enrollments()->with('subject')->latest()->get();
        $activeEnrollments = $enrollments->where('status', 'enrolled');
        $today = now()->format('l');

        return view('student.dashboard', [
            'student' => $student,
            'enrollmentCount' => $enrollments->count(),
            'activeEnrollments' => $activeEnrollments,
            'completedCount' => $enrollments->where('status', 'completed')->count(),
            'todaySchedule' => $activeEnrollments->filter(fn (Enrollment $enrollment) => in_array($today, $enrollment->subject->meeting_days ?? [], true))
                ->sortBy(fn (Enrollment $enrollment) => $enrollment->subject->start_time),
            'recentEnrollments' => $enrollments->take(5),
            'today' => $today,
            'upcomingEvents' => Schema::hasTable('events')
                ? Event::where('starts_at', '>=', now())->orderBy('starts_at')->take(4)->get()
                : collect(),
        ]);
    }

    public function enrollments(Request $request): View
    {
        $enrollments = $request->user()->student
            ->enrollments()
            ->with('subject')
            ->latest()
            ->paginate(10);

        return view('student.enrollments', compact('enrollments'));
    }
}

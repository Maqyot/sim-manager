<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'studentCount' => Student::count(),
            'subjectCount' => Subject::count(),
            'enrollmentCount' => Enrollment::count(),
            'statusCounts' => Enrollment::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'courseCounts' => Student::selectRaw('course, count(*) as total')->groupBy('course')->orderByDesc('total')->pluck('total', 'course'),
            'recentEnrollments' => Enrollment::with(['student', 'subject'])->latest()->take(5)->get(),
        ]);
    }
}

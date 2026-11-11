<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Enrollment;
use App\Models\Student;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::query()
            ->withCount('enrollments')
            ->when($request->string('q')->trim()->toString(), fn ($query, $term) => $query->where(fn ($q) => $q
                ->where('student_number', 'like', "%{$term}%")
                ->orWhere('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")))
            ->orderBy('last_name')
            ->paginate(10)
            ->withQueryString();

        return view('students.index', compact('students'));
    }

    public function create(): View
    {
        return view('students.create');
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        Student::create($request->validated());

        return redirect()->route('students.index')->with('success', 'Student added.');
    }

    public function show(Student $student): View
    {
        $student->load('user');
        $enrollments = $student->enrollments()->with('subject')->latest()->get();
        $now = CarbonImmutable::now();
        $nextClass = $enrollments
            ->where('status', 'enrolled')
            ->map(function (Enrollment $enrollment) use ($now): ?array {
                $subject = $enrollment->subject;
                if (! $subject->meeting_days || ! $subject->start_time || ! $subject->end_time) {
                    return null;
                }

                for ($daysAhead = 0; $daysAhead <= 7; $daysAhead++) {
                    $date = $now->startOfDay()->addDays($daysAhead);
                    if (! in_array($date->format('l'), $subject->meeting_days, true)) {
                        continue;
                    }

                    $startsAt = CarbonImmutable::parse($date->toDateString().' '.$subject->start_time);
                    if ($startsAt->greaterThan($now)) {
                        return [
                            'enrollment' => $enrollment,
                            'starts_at' => $startsAt,
                        ];
                    }
                }

                return null;
            })
            ->filter()
            ->sortBy('starts_at')
            ->first();
        $enrolledCount = $enrollments->where('status', 'enrolled')->count();
        $completedCount = $enrollments->where('status', 'completed')->count();
        $enrolledUnits = $enrollments
            ->where('status', 'enrolled')
            ->sum(fn (Enrollment $enrollment) => $enrollment->subject->units);

        return view('students.show', compact('student', 'enrollments', 'nextClass', 'enrolledCount', 'completedCount', 'enrolledUnits'));
    }

    public function edit(Student $student): View
    {
        return view('students.edit', compact('student'));
    }

    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($request->validated());

        return redirect()->route('students.index')->with('success', 'Student updated.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted.');
    }
}

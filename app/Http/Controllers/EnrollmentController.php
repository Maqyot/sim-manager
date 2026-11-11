<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(): View
    {
        $enrollments = Enrollment::with(['student', 'subject'])->latest()->paginate(10);

        return view('enrollments.index', compact('enrollments'));
    }

    public function create(): View
    {
        return view('enrollments.create', $this->formData());
    }

    public function store(EnrollmentRequest $request): RedirectResponse
    {
        Enrollment::create($request->validated());

        return redirect()->route('enrollments.index')->with('success', 'Student enrolled.');
    }

    public function edit(Enrollment $enrollment): View
    {
        return view('enrollments.edit', $this->formData() + ['enrollment' => $enrollment]);
    }

    public function update(EnrollmentRequest $request, Enrollment $enrollment): RedirectResponse
    {
        $enrollment->update($request->validated());

        return redirect()->route('enrollments.index')->with('success', 'Enrollment updated.');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return redirect()->route('enrollments.index')->with('success', 'Enrollment removed.');
    }

    private function formData(): array
    {
        return [
            'students' => Student::orderBy('last_name')->get(),
            'subjects' => Subject::orderBy('code')->get(),
        ];
    }
}

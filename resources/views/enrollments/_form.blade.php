@php $e = $enrollment ?? null; @endphp
<div class="mb-3">
    <label for="student_id" class="form-label">Student</label>
    <select id="student_id" name="student_id" class="form-select @error('student_id') is-invalid @enderror">
        <option value="">Select student</option>
        @foreach ($students as $s)<option value="{{ $s->id }}" @selected(old('student_id', $e?->student_id) == $s->id)>{{ $s->student_number }} · {{ $s->full_name }}</option>@endforeach
    </select>
    @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="subject_id" class="form-label">Subject</label>
    <select id="subject_id" name="subject_id" class="form-select @error('subject_id') is-invalid @enderror">
        <option value="">Select subject</option>
        @foreach ($subjects as $s)<option value="{{ $s->id }}" @selected(old('subject_id', $e?->subject_id) == $s->id)>{{ $s->code }} · {{ $s->name }}</option>@endforeach
    </select>
    @error('subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="school_year" class="form-label">School Year</label>
        <input id="school_year" name="school_year" placeholder="2026-2027" value="{{ old('school_year', $e?->school_year) }}" class="form-control @error('school_year') is-invalid @enderror">
        @error('school_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="semester" class="form-label">Semester</label>
        <select id="semester" name="semester" class="form-select @error('semester') is-invalid @enderror">
            @foreach (\App\Models\Enrollment::SEMESTERS as $opt)<option @selected(old('semester', $e?->semester) == $opt)>{{ $opt }}</option>@endforeach
        </select>
        @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
            @foreach (\App\Models\Enrollment::STATUSES as $opt)<option value="{{ $opt }}" @selected(old('status', $e?->status) == $opt)>{{ ucfirst($opt) }}</option>@endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="grade" class="form-label">Grade (1.00 – 5.00, optional)</label>
        <input id="grade" name="grade" value="{{ old('grade', $e?->grade) }}" class="form-control @error('grade') is-invalid @enderror">
        @error('grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<button class="btn btn-primary">Save</button>
<a href="{{ route('enrollments.index') }}" class="btn btn-outline-secondary">Cancel</a>

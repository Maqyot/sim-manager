@php $s = $student ?? null; @endphp
@foreach ([['student_number', 'Student Number', 'text'], ['first_name', 'First Name', 'text'], ['last_name', 'Last Name', 'text'], ['email', 'Email', 'email'], ['course', 'Course', 'text']] as [$field, $label, $type])
    <div class="mb-3">
        <label for="{{ $field }}" class="form-label">{{ $label }}</label>
        <input id="{{ $field }}" type="{{ $type }}" name="{{ $field }}" value="{{ old($field, $s?->$field) }}" class="form-control @error($field) is-invalid @enderror">
        @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
@endforeach
<div class="mb-4">
    <label for="year_level" class="form-label">Year Level</label>
    <select id="year_level" name="year_level" class="form-select @error('year_level') is-invalid @enderror">
        @foreach (range(1, 6) as $year)<option value="{{ $year }}" @selected(old('year_level', $s?->year_level) == $year)>Year {{ $year }}</option>@endforeach
    </select>
    @error('year_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<button class="btn btn-primary">Save</button>
<a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Cancel</a>

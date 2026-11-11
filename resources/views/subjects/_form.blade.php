@php $s = $subject ?? null; @endphp
@php
    $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $selectedDays = (array) old('meeting_days', $s?->meeting_days ?? []);
@endphp
@foreach ([['code', 'Subject Code', 'text'], ['name', 'Subject Name', 'text']] as [$field, $label, $type])
    <div class="mb-3">
        <label for="{{ $field }}" class="form-label">{{ $label }}</label>
        <input id="{{ $field }}" type="{{ $type }}" name="{{ $field }}" value="{{ old($field, $s?->$field) }}" class="form-control @error($field) is-invalid @enderror">
        @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
@endforeach
<div class="mb-4">
    <label for="units" class="form-label">Units</label>
    <select id="units" name="units" class="form-select @error('units') is-invalid @enderror">
        @foreach (range(1, 6) as $u)<option value="{{ $u }}" @selected(old('units', $s?->units ?? 3) == $u)>{{ $u }}</option>@endforeach
    </select>
    @error('units')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<fieldset class="mb-3">
    <legend class="form-label fs-6">Meeting days <span class="text-body-secondary fw-normal">(optional)</span></legend>
    <div class="row g-2">
        @foreach ($weekdays as $day)
            <div class="col-6 col-sm-4 col-lg-3">
                <div class="form-check">
                    <input id="meeting_day_{{ strtolower($day) }}" type="checkbox" name="meeting_days[]" value="{{ $day }}" class="form-check-input @error('meeting_days') is-invalid @enderror @error('meeting_days.*') is-invalid @enderror" @checked(in_array($day, $selectedDays, true))>
                    <label class="form-check-label" for="meeting_day_{{ strtolower($day) }}">{{ $day }}</label>
                </div>
            </div>
        @endforeach
    </div>
    @error('meeting_days')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    @error('meeting_days.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</fieldset>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="start_time" class="form-label">Start time</label>
        <input id="start_time" type="time" name="start_time" value="{{ old('start_time', $s?->start_time ? substr($s->start_time, 0, 5) : '') }}" class="form-control @error('start_time') is-invalid @enderror">
        @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="end_time" class="form-label">End time</label>
        <input id="end_time" type="time" name="end_time" value="{{ old('end_time', $s?->end_time ? substr($s->end_time, 0, 5) : '') }}" class="form-control @error('end_time') is-invalid @enderror">
        @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
<p class="form-text">Choose at least one meeting day and both times, or leave the schedule empty.</p>
<button class="btn btn-primary">Save</button>
<a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary">Cancel</a>

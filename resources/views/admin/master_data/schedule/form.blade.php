<x-admin-layout>
    <div style="padding: 2rem 3rem">
        <h1 class="pb-2">{{ $title }}</h1>
        <h5 class="pb-3">{{ $sub_title }}</h5>

        <form action="{{ $route }}" method="POST">
            @csrf
            @if ($method === 'PUT')
                @method('PUT')
            @endif

            <div class="mb-3 form-group">
                <label for="id_student_class">Kelas Mahasiswa</label>
                <select class="form-control @error('id_student_class') is-invalid @enderror" id="id_student_class" name="id_student_class">
                    <option value="" disabled selected>Pilih Kelas</option>
                    @foreach ($studentClasses as $class)
                        <option value="{{ $class->id_student_class }}" {{ old('id_student_class', $model->id_student_class ?? '') == $class->id_student_class ? 'selected' : '' }}>
                            {{ $class->student_class_name }}
                        </option>
                    @endforeach
                </select>
                @error('id_student_class')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-group">
                <label for="course_code">Mata Kuliah</label>
                <select class="form-control @error('course_code') is-invalid @enderror" id="course_code" name="course_code">
                    <option value="" disabled selected>Pilih Mata Kuliah</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->course_code }}" {{ old('course_code', $model->course_code ?? '') == $course->course_code ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
                @error('course_code')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-group">
                <label for="nip">Dosen Pengajar</label>
                <select class="form-control @error('nip') is-invalid @enderror" id="id_lecturer" name="nip">
                    <option value="" disabled selected>Pilih Dosen Pengajar</option>
                    @foreach ($lecturers as $lecturer)
                        <option value="{{ $lecturer->nip }}" {{ old('nip', $model->nip ?? '') == $lecturer->nip ? 'selected' : '' }}>
                            {{ $lecturer->lecturer_name }}
                        </option>
                    @endforeach
                </select>
                @error('nip')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-group">
                <label for="schedule_day">Hari</label>
                <select class="form-control @error('schedule_day') is-invalid @enderror" id="schedule_day" name="schedule_day">
                    <option value="" disabled selected>Pilih Hari</option>
                    <option value="Senin" {{ old('schedule_day', $model->schedule_day ?? '') == 'Senin' ? 'selected' : '' }}>Senin</option>
                    <option value="Selasa" {{ old('schedule_day', $model->schedule_day ?? '') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                    <option value="Rabu" {{ old('schedule_day', $model->schedule_day ?? '') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                    <option value="Kamis" {{ old('schedule_day', $model->schedule_day ?? '') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                    <option value="Jumat" {{ old('schedule_day', $model->schedule_day ?? '') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                    <option value="Sabtu" {{ old('schedule_day', $model->schedule_day ?? '') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                </select>
                @error('schedule_day')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-group">
                <label for="schedule_start_time">Waktu Mulai</label>
                <input type="time" class="form-control @error('schedule_start_time') is-invalid @enderror" id="schedule_start_time" name="schedule_start_time"
                       value="{{ old('schedule_start_time', \Carbon\Carbon::parse($model->schedule_start_time ?? '00:00:00')->format('H:i')) }}">
                @error('schedule_start_time')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-group">
                <label for="schedule_end_time">Waktu Selesai</label>
                <input type="time" class="form-control @error('schedule_end_time') is-invalid @enderror" id="schedule_end_time" name="schedule_end_time"
                       value="{{ old('schedule_end_time', \Carbon\Carbon::parse($model->schedule_end_time ?? '00:00:00')->format('H:i')) }}">
                @error('schedule_end_time')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 d-flex justify-content-between">
                <a href="{{ route('admin.master_data.schedule.index') }}" class="btn btn-danger">Batal</a>
                <button type="submit" class="btn btn-primary">{{ $button }}</button>
            </div>
        </form>
    </div>
</x-admin-layout>

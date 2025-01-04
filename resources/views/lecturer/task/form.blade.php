<x-admin-layout>
    <div style="padding: 2rem 3rem;min-height: 100vh;">
        <!-- Navigation Tabs -->
        <h1 class="pb-2">{{ $title }}</h1>
        <h5 class="pb-3">{{ $sub_title }}</h5>

        <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($method === 'PUT')
                @method('PUT')
            @endif

            <!-- Nama Tugas -->
            <div class="mb-3 form-group">
                <label for="task_name">Nama Tugas</label>
                <input type="text" class="form-control" id="task_name" name="task_name" value="{{ old('task_name', $task->task_name ?? '') }}" placeholder="Masukkan Nama Tugas">
                @error('task_name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Deskripsi Tugas -->
            <div class="mb-3 form-group">
                <label for="task_description">Deskripsi Tugas</label>
                <textarea class="form-control" id="task_description" name="task_description" placeholder="Masukkan Deskripsi Tugas">{{ old('task_description', $task->task_description ?? '') }}</textarea>
                @error('task_description')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Mata Kuliah -->
            <div class="mb-3 form-group">
                <label for="course_code">Mata Kuliah</label>
                <select class="form-control" id="course_code" name="course_code">
                    @foreach($courses as $course)
                        <option value="{{ $course->course_code }}" {{ old('course_code', $task->course_code ?? '') == $course->course_code ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
                @error('course_code')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Kelas -->
            <div class="mb-3 form-group">
                <label for="id_student_class">Kelas</label>
                <select class="form-control" id="id_student_class" name="id_student_class">
                    @foreach($studentClasses as $studentClass)
                        <option value="{{ $studentClass->id_student_class }}" {{ old('id_student_class', $task->id_student_class ?? '') == $studentClass->id_student_class ? 'selected' : '' }}>
                            {{ $studentClass->student_class_name }}
                        </option>
                    @endforeach
                </select>
                @error('id_student_class')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Dosen Pengajar -->
            {{-- <div class="mb-3 form-group">
                <label for="nip">Dosen Pengajar</label>
                <select class="form-control" id="nip" name="nip">
                    @foreach($lecturers as $lecturer)
                        <option value="{{ $lecturer->nip }}" {{ old('nip', $task->nip ?? '') == $lecturer->nip ? 'selected' : '' }}>
                            {{ $lecturer->lecturer_name }}
                        </option>
                    @endforeach
                </select>
                @error('nip')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div> --}}

            <!-- Tenggat Waktu -->
            <div class="mb-3 form-group">
                <label for="task_deadline">Tenggat Waktu</label>
                <input type="datetime-local" class="form-control" id="task_deadline" name="task_deadline" value="{{ old('task_deadline', $task->task_deadline ?? '') }}">
                @error('task_deadline')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Module (Optional) -->
            <div class="mb-3 form-group">
                <label for="task_module">Module</label>
                <input type="file" class="form-control" id="task_module" name="task_module">
                @if(isset($task) && $task->task_module)
                    <p>File yang diunggah: <a href="{{ Storage::url($task->task_module) }}" target="_blank">Lihat File</a></p>
                @endif
                @error('task_module')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Lampiran (Optional) -->
            <div class="mb-3 form-group">
                <label for="task_file">Lampiran</label>
                <input type="file" class="form-control" id="task_file" name="task_file">
                @if(isset($task) && $task->task_file)
                    <p>File yang diunggah: <a href="{{ Storage::url($task->task_file) }}" target="_blank">Lihat File</a></p>
                @endif
                @error('task_file')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 d-flex justify-content-between">
                <a href="{{ route($routePrefix . 'index') }}" class="btn btn-danger">Batal</a>
                <button type="submit" class="btn btn-primary">{{ $button }}</button>
            </div>
        </form>
    </div>
</x-admin-layout>

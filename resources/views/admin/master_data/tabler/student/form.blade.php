<x-admin-layout>
    <div style="padding: 2rem 3rem">
        <!-- Navigation Tabs -->
        <h1 class="pb-2">{{ $title }}</h1>
        <h5 class="pb-3">{{ $sub_title }}</h5>

        <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($method === 'PUT')
                @method('PUT')
            @endif

            <div class="mb-3 form-group">
                <label for="nim">NIM Mahasiswa</label>
                <input type="text" class="form-control" id="nim" name="nim" value="{{ old('nim', $model->nim ?? '') }}" placeholder="Masukkan NIM Mahasiswa">
                @error('nim')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3 form-group">
                <label for="student_name">Nama Mahasiswa</label>
                <input type="text" class="form-control" id="student_name" name="student_name" value="{{ old('student_name', $model->student_name ?? '') }}" placeholder="Masukkan Nama Mahasiswa">
                @error('student_name')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3 form-group">
                <label for="student_class">Kelas Mahasiswa</label>
                <select class="form-control" id="student_class" name="id_student_class">
                    <option value="" disabled selected>Pilih Kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id_student_class }}" {{ old('student_class', $model->id_student_class ?? '') == $class->id_student_class? 'selected' : '' }}>
                            {{ $class->student_class_name }}
                        </option>
                    @endforeach
                </select>
                @error('student_class')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3 form-group">
                <label for="guardian_lecturer">Dosen Wali</label>
                <select class="form-control" id="guardian_lecturer" name="guardian_lecturer">
                    <option value="" disabled selected>Pilih Dosen Wali</option>
                    @foreach ($lecturers as $lecturer)
                        <option value="{{ $lecturer->lecturer_code }}" {{ old('guardian_lecturer', $model->guardian_lecturer ?? '') == $lecturer->lecturer_code ? 'selected' : '' }}>
                            {{ $lecturer->lecturer_name }}
                        </option>
                    @endforeach
                </select>
                @error('guardian_lecturer')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3 d-flex justify-content-between">
                <a href="{{ route('admin.master_data.tabler.student.index') }}" class="btn btn-danger">Batal</a>
                <button type="submit" class="btn btn-primary">{{ $button }}</button>
            </div>
        </form>
    </div>
</x-admin-layout>

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

            <!-- Course Code -->
            <div class="mb-3 form-group">
                <label for="course_code">Kode Mata Kuliah</label>
                <input type="text" class="form-control" id="course_code" name="course_code" value="{{ old('course_code', $model->course_code ?? '') }}" placeholder="Masukan Kode Mata Kuliah">
                <small class="form-text text-muted">Contoh: IF1234.</small>
                @error('course_code')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Course Name -->
            <div class="mb-3 form-group">
                <label for="course_name">Nama Mata Kuliah</label>
                <input type="text" class="form-control" id="course_name" name="course_name" value="{{ old('course_name', $model->course_name ?? '') }}" placeholder="Masukan Nama Mata Kuliah">
                <small class="form-text text-muted">Contoh: Pemrograman Berorientasi Objek.</small>
                @error('course_name')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Course SKS -->
            <div class="mb-3 form-group">
                <label for="course_sks">Jumlah SKS</label>
                <input type="number" class="form-control" id="course_sks" name="course_sks" value="{{ old('course_sks', $model->course_sks ?? '') }}" placeholder="Masukan Jumlah SKS">
                <small class="form-text text-muted">Contoh: 3.</small>
                @error('course_sks')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <!-- Form Buttons -->
            <div class="mb-3 d-flex justify-content-between">
                <a href="{{ route($routePrefix . 'index') }}" class="btn btn-danger">Batal</a>
                <button type="submit" class="btn btn-primary">{{ $button }}</button>
            </div>
        </form>
    </div>
</x-admin-layout>

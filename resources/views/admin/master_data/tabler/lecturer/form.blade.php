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
                <label for="nip">NIP Dosen</label>
                <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip', $model->nip ?? '') }}" placeholder="Masukkan NIP Dosen">
                @error('nip')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3 form-group">
                <label for="lecturer_name">Nama Dosen</label>
                <input type="text" class="form-control" id="lecturer_name" name="lecturer_name" value="{{ old('lecturer_name', $model->lecturer_name ?? '') }}" placeholder="Masukkan Nama Dosen">
                @error('lecturer_name')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3 form-group">
                <label for="lecturer_code">Kode Dosen</label>
                <input type="text" class="form-control" id="lecturer_code" name="lecturer_code" value="{{ old('lecturer_code', $model->lecturer_code ?? '') }}" placeholder="Masukkan Kode Dosen">
                @error('lecturer_code')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3 d-flex justify-content-between">
                <a href="{{ route('admin.master_data.tabler.lecturer.index') }}" class="btn btn-danger">Batal</a>
                <button type="submit" class="btn btn-primary">{{ $button }}</button>
            </div>
        </form>
    </div>
</x-admin-layout>

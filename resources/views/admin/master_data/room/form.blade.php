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
                <label for="room_name">Nama Ruangan</label>
                <input type="text" class="form-control" id="room_name" name="room_name" value="{{ old('name', $model->room_name ?? '') }}" placeholder="Masukan Nama Ruangan">
                <small id="emailHelp" class="form-text text-muted">Contoh: A4.</small>
                @error('room_name')
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

@push('js')
    <script>
        $(document).ready(function() {
            var table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route($routePrefix . 'index') }}',
                    type: 'GET'
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        title: "No",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'course_code',
                        name: 'course_code',
                        title: 'Kode Mata Kuliah',
                    },
                    {
                        data: 'course_name',
                        name: 'course_name',
                        title: 'Nama Mata Kuliah',
                    },
                    {
                        data: 'course_sks',
                        name: 'course_sks',
                        title: 'SKS',
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        title: 'Aksi',
                    }
                ],
                paging: true,
                searching: true,
                ordering: true,
                serverSide: false,
                processing: true,
                deferRender: true,
            });

            $(document).on('click', '.status-button', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin memperbarui status?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Perbarui!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $(this).closest('.status-form');
                        form.submit();
                    }
                });
            });

            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menghapus mata kuliah?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $(this).closest('.delete-form');
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush

<x-admin-layout>
    <div style="padding: 2rem 3rem">
        <!-- Navigation Tabs -->
        <h1 class="pb-2">{{ $title ?? 'Kelola Mata Kuliah' }}</h1>

        <!-- Add Button -->
        <a href="{{ route($routePrefix . 'create') }}">
            <button class="mb-4 btn btn-primary">
                <i class="ti ti-plus"></i>
                {{ $add_button }}
            </button>
        </a>

        <!-- Main Content Card -->
        <div class="card">
            <div class="px-4 card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Mata Kuliah</th>
                                <th>Nama Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

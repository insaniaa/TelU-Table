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
                columns: [{
                        data: "DT_RowIndex"
                    },
                    {
                        data: 'student_class_name',
                        name: 'student_class_name',
                        title: 'Nama Kelas'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
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

                // Show SweetAlert confirmation
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
                        console.log(form)
                        form.submit();
                    }
                });
            });

            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault();

                // Show SweetAlert confirmation
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin memperbarui delete?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                }).then((result) => {
                    if (result.isConfirmed) {

                        const form = $(this).closest('.delete-form');
                        console.log(form)
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
        <h1 class="pb-2">{{ $title ?? 'Kelola Kelas' }}</h1>

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
                                <th>Nama Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

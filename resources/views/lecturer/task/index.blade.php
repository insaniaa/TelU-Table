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
                    data: "DT_RowIndex"
                },
                {
                    data: 'course_name', // Assuming you are returning the course name for course_code
                    name: 'course_name',
                    title: 'Kelas Mata Kuliah'
                },
                {
                    data: 'student_class_name', // Assuming you are returning the student class name for id_student_class
                    name: 'student_class_name',
                    title: 'Kelas'
                },
                {
                    data: 'task_name',
                    name: 'task_name',
                    title: 'Nama Tugas'
                },
                {
                    data: 'task_description', // Display the task description
                    name: 'task_description',
                    title: 'Deskripsi Tugas',
                    render: function(data, type, row) {
                        // Truncate description for better display in table (if it's too long)
                        return data.length > 50 ? data.substr(0, 50) + '...' : data;
                    }
                },
                {
                    data: 'task_deadline',
                    name: 'task_deadline',
                    title: 'Tenggat Waktu',
                },
                {
                    data: 'task_file',
                    name: 'task_file',
                    title: 'Lampiran',
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    title: 'Aksi'
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
                text: 'Apakah Anda yakin ingin memperbarui status tugas?',
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
                text: 'Apakah Anda yakin ingin menghapus tugas ini?',
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
        <h1 class="pb-2">{{ $title }}</h1>

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
                                <th>Kelas Mata Kuliah</th>
                                <th>Kelas</th>
                                <th>Nama Tugas</th>
                                <th>Deskripsi Tugas</th>  <!-- New Column for Task Description -->
                                <th>Tenggat Waktu</th>
                                {{-- <th>Module</th> --}}
                                <th>Lampiran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

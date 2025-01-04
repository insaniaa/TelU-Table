<x-admin-layout>
    <div style="padding: 2rem 3rem">
        <h1 class="pb-2">{{ $title }}</h1>
        <h5 class="pb-3">Daftar Jadwal Kuliah</h5>

        <a href="{{ route('admin.master_data.schedule.create') }}">
            <button class="mb-4 btn btn-primary">
                <i class="ti ti-plus"></i>
                Tambah Jadwal
            </button>
        </a>

        <table class="table table-bordered" id="scheduleTable">
            <thead>
                <tr>
                    <th class="text-center">Kelas</th>
                    <th class="text-center" colspan="5">Jadwal</th> <!-- Jadwal sub-kolom -->
                    <th class="text-center">Aksi</th>
                </tr>
                <tr>
                    <th class="text-center"></th>
                    <th class="text-center">Mata Kuliah</th>
                    <th class="text-center">Dosen</th>
                    <th class="text-center">Hari</th>
                    <th class="text-center">Jam Mulai</th>
                    <th class="text-center">Jam Selesai</th>
                    <th class="text-center"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                $('#scheduleTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.master_data.schedule.index') }}",
                    columns: [{
                            data: 'student_class.student_class_name',
                            name: 'student_class.student_class_name'
                        }, // Kolom Kelas
                        {
                            data: 'course.course_name',
                            name: 'course.course_name'
                        }, // Mata Kuliah
                        {
                            data: 'lecturer.lecturer_name',
                            name: 'lecturer.lecturer_name'
                        }, // Dosen
                        {
                            data: 'schedule_day',
                            name: 'schedule_day'
                        }, // Hari
                        {
                            data: 'schedule_start_time',
                            name: 'schedule_start_time'
                        }, // Jam Mulai
                        {
                            data: 'schedule_end_time',
                            name: 'schedule_end_time'
                        }, // Jam Selesai
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        } // Aksi
                    ]
                });

                $(document).on('click', '.status-button', function(e) {
                    e.preventDefault();

                    // Show SweetAlert confirmation
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah Anda yakin ingin memperbarui status jadwal?',
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
                        text: 'Apakah Anda yakin ingin menghapus jadwal ini?',
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
</x-admin-layout>

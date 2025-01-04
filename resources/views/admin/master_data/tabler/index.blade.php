<x-admin-layout>
    <div style="padding: 2rem 3rem">
        <h1 class="pb-2">Kelola Dosen & Mahasiswa</h1>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="lecturer-tab" data-bs-toggle="tab" data-bs-target="#lecturer"
                    type="button" role="tab" aria-controls="lecturer" aria-selected="true">
                    Dosen
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="studentTab" disabled data-bs-toggle="tab" data-bs-target="#student"
                    type="button" role="tab" aria-controls="student" aria-selected="false">
                    Mahasiswa
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="pt-3 tab-content" id="myTabContent">
            <!-- Lecturer Tab -->
            <div class="tab-pane fade show active" id="lecturer" role="tabpanel" aria-labelledby="lecturer-tab">
                <div class="mb-3">
                    <a href="{{ route('admin.master_data.tabler.lecturer.create') }}" class="btn btn-primary">Tambah
                        Dosen</a>
                </div>
                <div class="table-responsive">
                    <table class="table" id="lecturerTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kode</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!-- Student Tab -->
            <div class="tab-pane fade" id="student" role="tabpanel" aria-labelledby="student-tab">
                <div class="table-responsive">
                    <div class="mb-3">
                        <a href="{{ route('admin.master_data.tabler.student.create') }}" class="btn btn-primary">Tambah
                            Mahasiswa</a>
                    </div>
                    <table class="table" id="studentTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                // Inisialisasi tabel dosen saat halaman dimuat
                $('#lecturerTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.master_data.tabler.lecturer.index') }}",
                    columns: [{
                            data: "DT_RowIndex",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nip',
                            name: 'nip'
                        },
                        {
                            data: 'lecturer_name',
                            name: 'lecturer_name'
                        },
                        {
                            data: 'lecturer_email',
                            name: 'lecturer_email'
                        },
                        {
                            data: 'lecturer_code',
                            name: 'lecturer_code'
                        },
                        {
                            data: 'actions',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    paging: true,
                    searching: true,
                    ordering: true,
                    serverSide: true,
                    drawCallback: function(settings) {
                        $('#studentTab').attr('disabled', false)
                    }
                });

                // Inisialisasi tabel mahasiswa hanya saat tab atau tombol diklik
                let studentTableInitialized = false;

                $('#studentTab').on('click', function() {
                    if (!studentTableInitialized) {
                        $('#studentTable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('admin.master_data.tabler.student.index') }}",
                            columns: [{
                                    data: "DT_RowIndex",
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'nim',
                                    name: 'nim'
                                },
                                {
                                    data: 'student_name',
                                    name: 'student_name'
                                },
                                {
                                    data: 'student_email',
                                    name: 'student_email'
                                },
                                {
                                    data: 'class',
                                    name: 'class'
                                },
                                {
                                    data: 'actions',
                                    orderable: false,
                                    searchable: false
                                }
                            ],
                            paging: true,
                            searching: true,
                            ordering: true,
                            serverSide: true,
                        });
                        studentTableInitialized = true; // Set flag menjadi true agar tidak inisialisasi ulang
                    }
                });

                // Status and Delete button handling
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
</x-admin-layout>

<nav class="bg-white shadow-sm navbar navbar-expand-lg fixed-top">
    <div class="px-2 container-fluid">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Tel-U Timetable" style="height: 40px; margin-right: 20px;">
        </a>

        <!-- Toggle Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-3">
                @hasrole('Super Admin')
                    <li class="nav-item me-4">
                        <a class="nav-link {{ request()->is('admin/master-data/student-class*') ? 'active-link' : '' }}"
                            href="{{ route('admin.master_data.student_class.index') }}">Kelola Kelas</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link {{ request()->is('admin/master-data/room*') ? 'active-link' : '' }}"
                            href="{{ route('admin.master_data.room.index') }}">Kelola Ruangan</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link {{ request()->is('admin/master-data/course*') ? 'active-link' : '' }}"
                            href="{{ route('admin.master_data.course.index') }}">Kelola Mata Kuliah</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link {{ request()->is('admin/master-data/tabler*') ? 'active-link' : '' }}"
                            href="{{ route('admin.master_data.tabler.lecturer.index') }}">Kelola Dosen & Mahasiswa</a>
                    </li>
                    <li class="nav-item me-4">
                        <a class="nav-link {{ request()->is('admin/master-data/schedule*') ? 'active-link' : '' }}"
                            href="{{ route('admin.master_data.schedule.index') }}">Kelola Jadwal</a>
                    </li>
                @endhasrole
                @hasrole('Lecturer')
                <li class="nav-item me-4">
                    <a class="nav-link {{ request()->is('lecturer/tasks/tasks-today') ? 'active-link' : '' }}"
                        href="{{ route('lecturer.task.tasks_today') }}">Beranda</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link {{ request()->is('lecturer/tasks*') ? 'active-link' : '' }}"
                        href="{{ route('lecturer.task.index') }}">Kelola Tugas</a>
                </li>
                @endhasrole
            </ul>
            <!-- User Dropdown -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ url('/profile') }}">Profile</a>
                        <a class="dropdown-item" href="{{ url('/logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

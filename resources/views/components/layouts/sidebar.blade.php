<div class="main-sidebar sidebar-style-2">
<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
    <a href="/">E-Kinerja</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
    <a href="/">EK</a>
    </div>
    <ul class="sidebar-menu">
    @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('penilai'))
    <li class="menu-header">Dashboard</li>
    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="fa-solid fa-fire"></i><span>Dashboard</span>
        </a>
    </li>
    @endif

    @if (Auth::user()->hasRole('admin'))
    <li class="menu-header">Manajemen User</li>
    <li class="nav-item dropdown {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
            <i class="fa-solid fa-users"></i><span>User</span>
        </a>
        <ul class="dropdown-menu">
            <li class="{{ request()->routeIs('admin.user.profile') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.user.profile') }}">Profil</a>
            </li>
            <li class="{{ request()->routeIs('admin.user.list') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.user.list') }}">Daftar User</a>
            </li>
        </ul>
    </li>

    <li class="menu-header">Karyawan</li>
    <li class="nav-item dropdown {{ request()->routeIs('admin.karyawan.*') || request()->routeIs('admin.jabatan.*') || request()->routeIs('admin.pendidikan.*') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
            <i class="fa-solid fa-briefcase"></i><span>Karyawan</span>
        </a>
        <ul class="dropdown-menu">
            <li class="{{ request()->routeIs('admin.jabatan.list') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.jabatan.list') }}">Jabatan</a>
            </li>
            <li class="{{ request()->routeIs('admin.pendidikan.list') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.pendidikan.list') }}">Pendidikan</a>
            </li>
            <li class="{{ request()->routeIs('admin.karyawan.list') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.karyawan.list') }}">Daftar Karyawan</a>
            </li>
        </ul>
    </li>
    @endif

    @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('penilai'))
    <li class="menu-header">Evaluasi</li>
    <li class="nav-item dropdown {{ request()->routeIs('admin.evaluasi-kriteria.*') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
            <i class="fa-solid fa-check-circle"></i><span>Evaluasi</span>
        </a>
        <ul class="dropdown-menu">
            <li class="{{ request()->routeIs('admin.evaluasi-kriteria.list') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.evaluasi-kriteria.list') }}">Daftar Kriteria</a>
            </li>
            <li class="{{ request()->routeIs('admin.evaluasi-kriteria.nilai') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.evaluasi-kriteria.nilai') }}">Daftar Nilai</a>
            </li>
        </ul>
    </li>
    @endif

    @if (Auth::user()->hasRole('karyawan'))
    <li class="menu-header">Nilai</li>
    <li class="{{ request()->routeIs('admin.evaluasi-kriteria.nilai-karyawan') ? 'active' : '' }}">
        <a href="{{ route('admin.evaluasi-kriteria.nilai-karyawan') }}" class="nav-link">
            <i class="fa-solid fa-check-circle"></i><span>Lihat Nilai</span>
        </a>
    </li>
    @endif

    </ul>

    {{-- <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
        <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
        <i class="fa-solid fa-rocket"></i> Watermark
        </a>
    </div> --}}
</aside>
</div>
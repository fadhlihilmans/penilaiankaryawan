<!DOCTYPE html>
<html lang="en">
<head>
  @livewireStyles

  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  {{-- <title> &mdash; {{ config('app.name') }}</title> --}}
  <title> {{ config('app.name') }}</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="/assets/etc/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/assets/etc/datatables.net-select-bs4/css/select.bootstrap4.min.css">
  <link rel="stylesheet" href="/assets/etc/summernote/dist/summernote-bs4.css">
  <link rel="stylesheet" href="/assets/etc/selectric/public/selectric.css">
  <link rel="stylesheet" href="/assets/etc/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
  <link rel="stylesheet" href="/assets/etc/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="/assets/etc/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="/assets/etc/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="/assets/etc/selectric/public/selectric.css">
  <link rel="stylesheet" href="/assets/etc/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="/assets/etc/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
  {{-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"> --}}
  <link rel="stylesheet" type="text/css" href="/assets/toastify/toastify.min.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/components.css">
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
            <ul class="navbar-nav mr-3">
                <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <a href="/admin/user/profile" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profile
              </a>
              <div class="dropdown-divider"></div>
              {{-- <a href="/logout"    
                 onclick="return confirm('Apakah Anda yakin ingin logout?')" 
                 class="dropdown-item has-icon text-danger">
                  <i class="fa-solid fa-sign-out-alt"></i> Logout
              </a> --}}
              <a href="#" data-toggle="modal" data-target="#logoutModal" class="dropdown-item has-icon text-danger">
                <i class="fa-solid fa-sign-out-alt"></i> Logout
              </a>
            
            </div>
          </li>
        </ul>
      </nav>

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

      <!-- Main Content -->
      <div class="main-content">
       {{ $slot }}
      </div>

      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; {{ date('Y') }} <div class="bullet"></div>
        </div>
        <div class="footer-right">
          2.3.0
        </div>
      </footer>
    </div>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Konfirmasi Logout</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  Apakah Anda yakin ingin logout?
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                  <a href="{{ route('logout') }}" class="btn btn-danger">Ya, Logout</a>
              </div>
          </div>
      </div>
  </div>
  
  </div>

  <!-- General JS Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="/assets/js/stisla.js"></script>

  <!-- JS Libraies -->
  <script src="/assets/etc/datatables/media/js/jquery.dataTables.min.js"></script>
  <script src="/assets/etc/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="/assets/etc/datatables.net-select-bs4/js/select.bootstrap4.min.js"></script>
  <script src="/assets/etc/summernote/dist/summernote-bs4.js"></script>
  <script src="/assets/etc/selectric/public/jquery.selectric.min.js"></script>
  <script src="/assets/etc/jquery_upload_preview/assets/js/jquery.uploadPreview.min.js"></script>
  <script src="/assets/etc/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
  <script src="/assets/etc/cleave.js/dist/cleave.min.js"></script>
  <script src="/assets/etc/cleave.js/dist/addons/cleave-phone.us.js"></script>
  <script src="/assets/etc/jquery-pwstrength/jquery.pwstrength.min.js"></script>
  <script src="/assets/etc/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="/assets/etc/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
  <script src="/assets/etc/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
  <script src="/assets/etc/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
  <script src="/assets/etc/select2/dist/js/select2.full.min.js"></script>
  <script src="/assets/etc/selectric/public/jquery.selectric.min.js"></script>


  <!-- Template JS File -->
  <script src="/assets/js/scripts.js"></script>
  <script src="/assets/js/custom.js"></script>

  <!-- Page Specific JS File -->
  <script src="/assets/js/page/modules-datatables.js"></script>
  <script src="/assets/js/page/features-post-create.js"></script>
  <script src="/assets/js/page/forms-advanced-forms.js"></script>
  <script src="/assets/js/page/index-0.js"></script>


  {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script> --}}
  <script type="text/javascript" src="/assets/toastify/toastify.min.js"></script>
  <!-- toast -->
  <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('success-message', (event) => {
                Toastify({
                    text: event,
                    className: "info",
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)",
                    }
                }).showToast();
            });

            Livewire.on('failed-message', (event) => {
                Toastify({
                    text: event,
                    className: "info",
                    duration: 3000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    style: {
                        background: "linear-gradient(to right, #9d1f2c, #DA4453)",
                    }
                }).showToast();
            });
        });
  </script>
  @if (session('success-message'))
        <script>
            Toastify({
                text: '{{ session('success-message') }}',
                className: "info",
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                }
            }).showToast();
        </script>
  @endif 
  @if (session('failed-message'))
        <script>
            Toastify({
                text: '{{ session('failed-message') }}',
                className: "info",
                duration: 3000,
                newWindow: true,
                close: true,
                gravity: "top",
                style: {
                    background: "linear-gradient(to right, #9d1f2c, #DA4453)",
                }
            }).showToast();
        </script>
  @endif

  @livewireScripts

  @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  {{-- <title> &mdash; {{ config('app.name') }}</title> --}}
  <title> {{ config('app.name') }}</title>

  <x-layouts.css />

</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>

      <x-layouts.navbar />
      <x-layouts.sidebar />

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

  <x-layouts.scripts />
  <x-layouts.toast />

  @stack('scripts')
</body>
</html>

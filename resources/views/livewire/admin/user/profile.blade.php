<div>
    <section class="section">
        <div class="section-header">
            <h1>Profil Pengguna</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </div>
                <div class="breadcrumb-item">Profil</div>
            </div>
        </div>
    
        <div class="section-body">
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card profile-widget">
                        <div class="profile-widget-header">
                            <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle profile-widget-picture">
                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Role</div>
                                    <div class="profile-widget-item-value">{!! $user->roleBadge !!}</div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-widget-description">
                            <div class="profile-widget-name">
                                {{ $user->name }}
                                <div class="text-muted d-inline font-weight-normal">
                                    <div class="slash"></div> {{ $user->email }}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <div class="mb-2">
                                <button class="btn btn-primary" wire:click="openPasswordModal">
                                    <i class="fas fa-key"></i> Ganti Password
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($isEmployee)
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Karyawan</h4>
                        </div>
                        <div class="card-body">
                            <!-- NIP -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">NIP</label>
                                <div class="col-sm-12 col-md-9">
                                    <p class="form-control-plaintext">{{ $user->employees->nip ?? '-' }}</p>
                                </div>
                            </div>
    
                            <!-- Tempat Lahir -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tempat Lahir</label>
                                <div class="col-sm-12 col-md-9">
                                    <p class="form-control-plaintext">{{ $user->employees->birth_place ?? '-' }}</p>
                                </div>
                            </div>
    
                            <!-- Tanggal Lahir -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tanggal Lahir</label>
                                <div class="col-sm-12 col-md-9">
                                    <p class="form-control-plaintext">
                                        @if($user->employees->birth_date)
                                            {{ \Carbon\Carbon::parse($user->employees->birth_date)->format('d F Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
    
                            <!-- Gender -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Gender</label>
                                <div class="col-sm-12 col-md-9">
                                    <p class="form-control-plaintext">
                                        @if($user->employees->gender == 'male')
                                            Laki-laki
                                        @elseif($user->employees->gender == 'female')
                                            Perempuan
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Pendidikan Terakhir -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Pendidikan Terakhir</label>
                                <div class="col-sm-12 col-md-9">
                                    <p class="form-control-plaintext">{{ $user->employees->education->name ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <!-- Jabatan -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jabatan</label>
                                <div class="col-sm-12 col-md-9">
                                    <p class="form-control-plaintext">{{ $user->employees->position->name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    
    <!-- Modal Ganti Password -->
    @if($showPasswordModal)
    <div class="modal fade show" tabindex="-1" role="dialog" style="display: block; padding-right: 17px;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ganti Password</h5>
                    <button type="button" class="close" wire:click="closePasswordModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" wire:model="password" placeholder="Masukkan password baru">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" wire:model="password_confirmation" placeholder="Konfirmasi password baru">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" wire:click="closePasswordModal">Batal</button>
                    <button type="button" class="btn btn-primary" wire:click="updatePassword">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
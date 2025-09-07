<div>
@if ($formAdd || $formEdit)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{ $formAdd ? 'Tambah User' : 'Edit User' }}</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Form User</h4></div>
                    <div class="card-body">
                        <!-- Nama -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" wire:model.defer="name">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="email" class="form-control" wire:model.defer="email">
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="form-control selectric" wire:model="roleIds.0">
                                    <option value="">-- Pilih Role --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('roleIds') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        
                        @if ($formEdit)
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Password</label>
                            <div class="col-sm-12 col-md-7">
                                <button wire:click="confirmResetPassword" class="btn btn-primary">Reset Password</button>
                            </div>
                        </div>
                        @endif

                        <!-- Tombol -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button wire:loading.remove wire:target="addUser,updateUser" wire:click="{{ $formAdd ? 'addUser' : 'updateUser' }}" type="submit" class="btn btn-primary">
                                    {{ $formAdd ? 'Tambah User' : 'Update User' }}
                                </button>
                                <button wire:loading wire:target="addUser,updateUser" class="btn btn-primary">
                                    Loading ...
                                </button>
                                <button wire:click="resetForm" type="button" class="btn btn-secondary">Kembali</button>
                            </div>
                        </div>

                        <!-- Info password -->
                        @if ($formAdd)
                            <div class="form-group row mb-2">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <p class="text-muted">Password default: <code>12345678</code></p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@if (!$formAdd && !$formEdit)
<section class="section">
    <div class="section-header">
        <h1>Data User</h1>
    </div>

    <div class="section-body">
        <div class="row">
        <div class="col-12">
            <div class="card">
            <div class="card-header">
                <h4>List User</h4>
                <div class="card-header-action">
                    <button wire:click="$set('formAdd', true)" class="btn btn-primary">Tambah Data</button>
                </div>
            </div>
            <div class="card-body">
                <div class="float-right">
                    <div class="d-flex jutstify-content-end">
                        <div class="input-group">
                            <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="cari ...">
                            <div class="input-group-append">
                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="clearfix mb-3"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="text-center">
                                #
                            </th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach ($user->roles as $role )
                                        {{ $role->name }}
                                    @endforeach
                                </td> 
                                <td>
                                    <button wire:click="edit({{ $user->id }})" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></button>
                                    <button wire:click="confirmDelete({{ $user->id }})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $users->links() }}
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</section>
@endif

<!-- Modal Konfirmasi Hapus -->
@if ($confirmingDelete)
<div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user ini?</p>
            </div>
            <div class="modal-footer">
                <button wire:click="deleteConfirmed" class="btn btn-danger">Ya, Hapus</button>
                <button wire:click="$set('confirmingDelete', false)" class="btn btn-secondary">Batal</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal Konfirmasi Reset Password -->
@if ($confirmingResetPassword)
<div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Reset Password</h5>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin me-reset password user ini ke <code>12345678</code>?</p>
            </div>
            <div class="modal-footer">
                <button wire:click="resetPasswordConfirmed" class="btn btn-warning">Ya, Reset</button>
                <button wire:click="$set('confirmingResetPassword', false)" class="btn btn-secondary">Batal</button>
            </div>
        </div>
    </div>
</div>
@endif


</div>


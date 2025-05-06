<div>
@if ($formAdd || $formEdit)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{ $formAdd ? 'Tambah Karyawan' : 'Edit Karyawan' }}</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Form Karyawan</h4></div>
                    <div class="card-body">
                        <!-- Foto -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Foto</label>
                            <div class="col-sm-12 col-md-7">
                                <div id="image-preview" class="image-preview" style="cursor: pointer !important; background-image: url({{ $image ? $image->temporaryUrl() : ($oldImage ? '/images/employee/' . $oldImage : '/assets/img/avatar/avatar-3.png') }}); background-size: cover;">
                                    <label for="image-upload" id="image-label">Pilih File</label>
                                    <input type="file" id="image-upload" wire:model.defer="image" />
                                </div>
                                @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

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

                        <!-- NIP -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">NIP</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" wire:model.defer="nip">
                                @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <!-- Tempat Lahir -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tempat Lahir</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="string" class="form-control" wire:model.defer="birth_place">
                                @error('birth_place') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tempat Lahir</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="date" class="form-control" wire:model.defer="birth_date">
                                @error('birth_date') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Gender</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="form-control selectric" wire:model.defer="gender">
                                    <option value="">-- Pilih Gender --</option>
                                        <option value="male">Laki-laki</option>
                                        <option value="female">Perempuan</option>
                                </select>
                                @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="form-control selectric" wire:model.defer="employment_status">
                                    <option value="">-- Pilih Status Kepegawaian --</option>
                                        <option value="permanent">Tetap</option>
                                        <option value="non-permanent">Tidak Tetap</option>
                                </select>
                                @error('employment_status') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        
                        <!-- Pendidikan Terakhir -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Pendidikan Terakhir</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="form-control selectric" wire:model.defer="education_id">
                                    <option value="">-- Pilih Pendidikan Terakhir --</option>
                                    @foreach ($educations as $education)
                                        <option value="{{ $education->id }}">{{ $education->name }}</option>
                                    @endforeach
                                </select>
                                @error('education_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        
                        <!-- Jabatan -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jabatan</label>
                            <div class="col-sm-12 col-md-7">
                                <select class="form-control selectric" wire:model.defer="position_id">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                                    @endforeach
                                </select>
                                @error('position_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        
                        <!-- Tombol -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button wire:loading.remove wire:target="add,update" wire:click="{{ $formAdd ? 'add' : 'update' }}" type="submit" class="btn btn-primary">
                                    {{ $formAdd ? 'Tambah Karyawan' : 'Update Karyawan' }}
                                </button>
                                <button wire:loading wire:target="add,update" class="btn btn-primary">
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

@if (!$formAdd && !$formEdit && !$detailOpen)
<section class="section">
    <div class="section-header">
        <h1>Data Karayawan</h1>
    </div>

    <div class="section-body">
        <div class="row">
        <div class="col-12">
            <div class="card">
            <div class="card-header">
                <h4>List Karyawan</h4>
                <div class="card-header-action">
                    <button wire:click="$set('formAdd', true)" class="btn btn-primary">Tambah Data</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                        <tr>
                            <th class="text-center">
                                #
                            </th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Email</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    @if ($employee->image == null)
                                        <img alt="image" src="/assets/img/avatar/avatar-3.png" class="rounded-circle" width="35">
                                    @else
                                        <img alt="image" src="/images/employee/{{ $employee->image }}" class="rounded-circle" width="35">
                                    @endif
                                    <div class="d-inline-block ml-1">{{ $employee->user->name }}</div>
                                </td>
                                <td>{{ $employee->nip }}</td>
                                <td>{{ $employee->user->email }}</td>
                                <td>{{ $employee->position->name }}</td>
                                <td>{!! $employee->status !!}</td>
                                <td>
                                    {!! $employee->user->roleBadge !!}
                                </td> 
                                <td>
                                    <button wire:click="detail({{ $employee->id }})" class="btn btn-info"><i class="fa-solid fa-eye"></i></button>
                                    <button wire:click="edit({{ $employee->id }})" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></button>
                                    <button wire:click="confirmDelete({{ $employee->id }})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
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
                <p>Apakah Anda yakin ingin menghapus Karyawan ini?</p>
            </div>
            <div class="modal-footer">
                <button wire:click="deleteConfirmed" class="btn btn-danger">Ya, Hapus</button>
                <button wire:click="$set('confirmingDelete', false)" class="btn btn-secondary">Batal</button>
            </div>
        </div>
    </div>
</div>
@endif

@if ($detailOpen)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Karyawan</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Detil Karyawan</h4></div>
                    <div class="card-body">
                        <!-- Foto -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Foto</label>
                            <div class="col-sm-12 col-md-7">
                                <div id="image-preview" class="image-preview" style="background-image: url({{($selectedEmployee->image ? '/images/employee/' . $selectedEmployee->image : '/assets/img/avatar/avatar-3.png') }}); background-size: cover;">
                                </div>
                            </div>
                        </div>

                        <!-- Nama -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama</label>
                            <div class="col-sm-12 col-md-7">
                                <p class="form-control-plaintext">{{ $selectedEmployee->user->name }}</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                            <div class="col-sm-12 col-md-7">
                                <p class="form-control-plaintext">{{ $selectedEmployee->user->email }}</p>
                            </div>
                        </div>

                        <!-- NIP -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">NIP</label>
                            <div class="col-sm-12 col-md-7">
                                <p class="form-control-plaintext">{{ $selectedEmployee->nip }}</p>
                            </div>
                        </div>

                        <!-- Tempat Lahir -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tempat Lahir</label>
                            <div class="col-sm-12 col-md-7">
                                <p class="form-control-plaintext">{{ $selectedEmployee->birth_place }}</p>
                            </div>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tempat Lahir</label>
                            <div class="col-sm-12 col-md-7">
                                <p class="form-control-plaintext">{{ $selectedEmployee->birth_date }}</p>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Gender</label>
                            <div class="col-sm-12 col-md-7">
                                <p class="form-control-plaintext">{{ $selectedEmployee->genderText }}</p>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                            <div class="col-sm-12 col-md-7">
                                <p class="form-control-plaintext">{!! $selectedEmployee->status !!}</p>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role</label>
                            <div class="col-sm-12 col-md-7">
                                <p class="form-control-plaintext">{!! $selectedEmployee->user->roleBadge !!}</p>
                            </div>
                        </div>
                        
                        <!-- Pendidikan Terakhir -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Pendidikan Terakhir</label>
                            <div class="col-sm-12 col-md-7">
                                <p class="form-control-plaintext">{{ $selectedEmployee->education->name }}</p>
                            </div>
                        </div>
                        
                        <!-- Jabatan -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jabatan</label>
                            <div class="col-sm-12 col-md-7">
                                <p class="form-control-plaintext">{{ $selectedEmployee->position->name }}</p>
                            </div>
                        </div>
                        
                        <!-- Tombol -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button wire:click="resetForm" type="button" class="btn btn-secondary">Kembali</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


</div>

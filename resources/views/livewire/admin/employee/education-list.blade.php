<div>

@if ($formAdd || $formEdit)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{ $formAdd ? 'Tambah Pendidikan' : 'Edit Pendidikan' }}</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Form Pendidikan</h4></div>
                    <div class="card-body">
                        <!-- Nama -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Pendidikan</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="text" class="form-control" wire:model.defer="name">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button wire:loading.remove wire:target="add,update" wire:click="{{ $formAdd ? 'add' : 'update' }}" type="submit" class="btn btn-primary">
                                    {{ $formAdd ? 'Tambah' : 'Update' }}
                                </button>
                                <button wire:loading wire:target="add,update" class="btn btn-primary">
                                    Loading ...
                                </button>
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
    

@if (!$formAdd && !$formEdit)
<section class="section">
    <div class="section-header">
        <h1>Data Pendidikan</h1>
    </div>

    <div class="section-body">
        <div class="row">
        <div class="col-12">
            <div class="card">
            <div class="card-header">
                <h4>List Pendidikan</h4>
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
                            <th></th>
                            <th class="d-none"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($educations as $education)
                            <tr>
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>
                                <td>{{ $education->name }}</td>
                                <td>
                                    <button wire:click="edit({{ $education->id }})" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></button>
                                    <button wire:click="confirmDelete({{ $education->id }})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                </td>
                                <td class="d-none"></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data</td>
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
                <p>Apakah Anda yakin ingin menghapus Pendidikan ini?</p>
            </div>
            <div class="modal-footer">
                <button wire:click="deleteConfirmed" class="btn btn-danger">Ya, Hapus</button>
                <button wire:click="$set('confirmingDelete', false)" class="btn btn-secondary">Batal</button>
            </div>
        </div>
    </div>
</div>
@endif

</div>

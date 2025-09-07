<div>
    @if($selectingPeriod)
    <section class="section">
    <div class="section-header">
        <h1>Hasil Penilaian Karyawan</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Pilih Periode Penilaian</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Bulan</label>
                            <div class="col-sm-12 col-md-7">
                                <!-- Remove selectric class -->
                                <select class="form-control selectric" wire:model="month">
                                    <option value="">-- Pilih Bulan --</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option>
                                    <option value="3">Maret</option>
                                    <option value="4">April</option>
                                    <option value="5">Mei</option>
                                    <option value="6">Juni</option>
                                    <option value="7">Juli</option>
                                    <option value="8">Agustus</option>
                                    <option value="9">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                @error('month') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tahun</label>
                            <div class="col-sm-12 col-md-7">
                                <input type="number" class="form-control" wire:model="year" min="2000" max="2100">
                                @error('year') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                            <div class="col-sm-12 col-md-7">
                                <button type="button" wire:click="goToViewScores" class="btn btn-primary">Lihat Hasil Penilaian</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    @endif

    @if($viewScores)
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a wire:click="resetForm" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Hasil Penilaian - Periode {{ $this->monthName }} {{ $this->year }}</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Ringkasan Penilaian</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    @php $employee = $employees->first(); @endphp
                                    @if($employee && $employee->image == null)
                                        <img alt="image" src="/assets/img/avatar/avatar-3.png" class="rounded-circle img-fluid">
                                    @elseif($employee)
                                        <img alt="image" src="/images/employee/{{ $employee->image }}" class="rounded-circle img-fluid">
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <h5>{{ $employee->user->name ?? 'Unknown' }}</h5>
                                    <p class="mb-1">NIP: {{ $employee->nip ?? 'Unknown' }}</p>
                                    <p class="mb-1">Jabatan: {{ $employee->position->name ?? 'Unknown' }}</p>
                                    <p class="mb-1">Status: {!! $employee->status ?? 'Unknown' !!}</p>
                                    <p class="mb-1">Periode Penilaian: {{ $this->monthName }} {{ $year }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4>Nilai Akhir</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                <h1 class="display-4">{{ number_format($totalScore, 2) }}</h1>
                                                <div class="mt-2">
                                                    @if($totalScore >= 90)
                                                        <span class="badge badge-success">Sangat Baik</span>
                                                    @elseif($totalScore >= 80)
                                                        <span class="badge badge-primary">Baik</span>
                                                    @elseif($totalScore >= 70)
                                                        <span class="badge badge-info">Cukup</span>
                                                    @elseif($totalScore >= 60)
                                                        <span class="badge badge-warning">Kurang</span>
                                                    @else
                                                        <span class="badge badge-danger">Sangat Kurang</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4>Nilai Per Kriteria</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Kriteria</th>
                                                            <th>Bobot</th>
                                                            <th>Nilai</th>
                                                            <th>Kontribusi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($criteriaScores as $criteriaId => $criteriaData)
                                                        <tr>
                                                            <td>{{ $criteriaData['name'] }}</td>
                                                            <td>{{ $criteriaData['weight'] * 100 }}%</td>
                                                            <td>{{ number_format($criteriaData['score'], 2) }}</td>
                                                            <td>{{ number_format($criteriaData['weighted_score'], 2) }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mt-4">
                                <div class="col-12">
                                    <button wire:click="viewScoreDetails" type="button" class="btn btn-primary">Lihat Detail Penilaian</button>
                                    <button wire:click="resetForm" type="button" class="btn btn-secondary">Kembali ke Pilih Periode</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($scoreDetails)
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a wire:click="backToScores" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Detail Penilaian - Periode {{ $this->monthName }} {{ $this->year }}</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Detail Penilaian</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-2">
                                    @php $employee = $employees->first(); @endphp
                                    @if($employee && $employee->image == null)
                                        <img alt="image" src="/assets/img/avatar/avatar-3.png" class="rounded-circle img-fluid">
                                    @elseif($employee)
                                        <img alt="image" src="/images/employee/{{ $employee->image }}" class="rounded-circle img-fluid">
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <h5>{{ $employee->user->name ?? 'Unknown' }}</h5>
                                    <p class="mb-1">NIP: {{ $employee->nip ?? 'Unknown' }}</p>
                                    <p class="mb-1">Jabatan: {{ $employee->position->name ?? 'Unknown' }}</p>
                                    <p class="mb-1">Status: {!! $employee->status ?? 'Unknown' !!}</p>
                                    <p class="mb-1">Periode Penilaian: {{ $this->monthName }} {{ $year }}</p>
                                </div>
                            </div>

                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4>Nilai Total: {{ number_format($totalScore, 2) }}</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kriteria</th>
                                                    <th>Bobot</th>
                                                    <th>Detail Kriteria</th>
                                                    <th>Bobot Detail</th>
                                                    <th>Nilai</th>
                                                    <th>Komentar</th>
                                                    <th>Penilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach($detailScores as $detailId => $detail)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $detail['criteria_name'] }}</td>
                                                    <td>{{ $detail['criteria_weight'] }}%</td>
                                                    <td>{{ $detail['name'] }}</td>
                                                    <td>{{ $detail['weight'] }}%</td>
                                                    <td>{{ $detail['score'] }}</td>
                                                    <td>{{ $detail['comment'] ?? '-' }}</td>
                                                    <td>{{ $detail['evaluator'] ?? '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mt-4">
                                <div class="col-12">
                                    <button wire:click="backToScores" type="button" class="btn btn-primary">Kembali ke Ringkasan</button>
                                    <button wire:click="resetForm" type="button" class="btn btn-secondary">Kembali ke Pilih Periode</button>
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
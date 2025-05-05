<div>
    @if ($formAdd || $formEdit)
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a wire:click="resetForm" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>{{ $formAdd ? 'Tambah Penilaian' : 'Edit Penilaian' }}</h1>
        </div>
    
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h4>Form Penilaian Karyawan</h4></div>
                        <div class="card-body">
                            <!-- Penilai -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Penilai</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control" wire:model="evaluator_user_id">
                                        <option value="">-- Pilih Penilai --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('evaluator_user_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
    
                            <!-- Karyawan yang Dinilai -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Karyawan yang Dinilai</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control" wire:model="evaluated_employee_id">
                                        <option value="">-- Pilih Karyawan --</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('evaluated_employee_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
    
                            <!-- Kriteria Utama -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Kriteria Utama</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control" wire:model="selectedCriteria">
                                        <option value="">-- Pilih Kriteria Utama --</option>
                                        @foreach ($criterias as $criteria)
                                            <option value="{{ $criteria->id }}">{{ $criteria->name }} ({{ $criteria->weight }}%)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
    
                            <!-- Detail Kriteria -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Detail Kriteria</label>
                                <div class="col-sm-12 col-md-7">
                                    <select class="form-control" wire:model="evaluation_criteria_detail_id">
                                        <option value="">-- Pilih Detail Kriteria --</option>
                                        @foreach ($criteriaDetails as $detail)
                                            <option value="{{ $detail->id }}">{{ $detail->name }} ({{ $detail->weight }}%)</option>
                                        @endforeach
                                    </select>
                                    @error('evaluation_criteria_detail_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
    
                            <!-- Tanggal Penilaian -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tanggal Penilaian</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="date" class="form-control" wire:model="date">
                                    @error('date') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
    
                            <!-- Nilai -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nilai (0-100)</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="number" min="0" max="100" class="form-control" wire:model="weight">
                                    @error('weight') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
    
                            <!-- Komentar -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Komentar</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea class="form-control" wire:model="comment" rows="4"></textarea>
                                    @error('comment') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            
                            <!-- Tombol -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button wire:loading.remove wire:target="add,update" wire:click="{{ $formAdd ? 'add' : 'update' }}" type="submit" class="btn btn-primary">
                                        {{ $formAdd ? 'Tambah Penilaian' : 'Update Penilaian' }}
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
    
    @if (!$formAdd && !$formEdit && !$detailOpen)
    <section class="section">
        <div class="section-header">
            <h1>Data Penilaian Karyawan</h1>
        </div>
    
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter Penilaian</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bulan</label>
                                        <select class="form-control" wire:model="selectedMonth" wire:change="filterByMonth">
                                            <option value="">Semua Bulan</option>
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
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <select class="form-control" wire:model="selectedYear" wire:change="filterByMonth">
                                            <option value="">Semua Tahun</option>
                                            @for ($i = date('Y'); $i >= date('Y')-5; $i--)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Penilaian Karyawan</h4>
                            <div class="card-header-action">
                                <button wire:click="$set('formAdd', true)" class="btn btn-primary">Tambah Penilaian</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Penilai</th>
                                        <th>Karyawan</th>
                                        <th>Kriteria</th>
                                        <th>Detail Kriteria</th>
                                        <th>Tanggal</th>
                                        <th>Nilai</th>
                                        <th>Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($scores as $score)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $score->evaluator->name }}</td>
                                            <td>{{ $score->evaluated->user->name }}</td>
                                            <td>{{ $score->criteriaDetail->criteria->name }}</td>
                                            <td>{{ $score->criteriaDetail->name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($score->date)->format('d/m/Y') }}</td>
                                            <td>{{ $score->weight }}</td>
                                            <td>
                                                <button wire:click="edit({{ $score->id }})" class="btn btn-warning"><i class="fa-solid fa-pencil"></i></button>
                                                <button wire:click="confirmingDelete({{ $score->id }})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Ringkasan Penilaian Karyawan -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Ringkasan Penilaian Karyawan</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Karyawan</th>
                                        <th>Total Nilai</th>
                                        <th>Grade</th>
                                        <th>Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $employeeScores = []; @endphp
                                    @foreach ($employees as $index => $employee)
                                        @php 
                                            $scoreData = $this->getEmployeeScoreSummary($employee->id);
                                            $totalScore = is_array($scoreData) ? $scoreData['total_score'] : $scoreData;
                                            
                                            // Determine grade based on score
                                            $grade = '';
                                            if ($totalScore >= 90) $grade = 'A';
                                            elseif ($totalScore >= 80) $grade = 'B';
                                            elseif ($totalScore >= 70) $grade = 'C';
                                            elseif ($totalScore >= 60) $grade = 'D';
                                            elseif ($totalScore > 0) $grade = 'E';
                                            else $grade = '-';
                                            
                                            // Store employee score for later use if needed
                                            $employeeScores[$employee->id] = [
                                                'score' => $totalScore,
                                                'grade' => $grade
                                            ];
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $employee->user->name }}</td>
                                            <td>{{ $totalScore }}</td>
                                            <td>
                                                @if ($grade == 'A')
                                                    <span class="badge badge-success">A</span>
                                                @elseif ($grade == 'B')
                                                    <span class="badge badge-info">B</span>
                                                @elseif ($grade == 'C')
                                                    <span class="badge badge-warning">C</span>
                                                @elseif ($grade == 'D')
                                                    <span class="badge badge-danger">D</span>
                                                @elseif ($grade == 'E')
                                                    <span class="badge badge-dark">E</span>
                                                @else
                                                    <span class="badge badge-light">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button wire:click="viewDetail({{ $employee->id }})" class="btn btn-info"><i class="fa-solid fa-eye"></i> Detail</button>
                                            </td>
                                        </tr>
                                    @endforeach
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
    
    <!-- Detail Penilaian Karyawan -->
    @if ($detailOpen && $selectedEmployee)
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a wire:click="resetForm" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Detail Penilaian Karyawan</h1>
        </div>
    
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi Karyawan</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nama Karyawan</label>
                                        <p class="form-control-plaintext">{{ $selectedEmployee->user->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NIP</label>
                                        <p class="form-control-plaintext">{{ $selectedEmployee->nip ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Periode Penilaian</label>
                                        <p class="form-control-plaintext">
                                            @php
                                                $monthNames = [
                                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 
                                                    4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                                    7 => 'Juli', 8 => 'Agustus', 9 => 'September', 
                                                    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                                ];
                                                $monthName = $selectedMonth ? $monthNames[$selectedMonth] : 'Semua Bulan';
                                                $yearValue = $selectedYear ?: date('Y');
                                                echo "$monthName $yearValue";
                                            @endphp
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            @php
                $scoreDetails = $this->getEmployeeScoreSummary($selectedEmployee->id);
                $finalScore = $scoreDetails['total_score'] ?? 0;
                $detailedScores = $scoreDetails['detailed_scores'] ?? [];
            @endphp
    
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Rincian Nilai Per Kriteria</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Kriteria Utama</th>
                                        <th>Bobot</th>
                                        <th>Detail Kriteria</th>
                                        <th>Bobot Detail</th>
                                        <th>Nilai</th>
                                        <th>Skor</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($detailedScores as $criteriaId => $criteriaData)
                                        <tr class="bg-light">
                                            <td><strong>{{ $criteriaData['name'] }}</strong></td>
                                            <td><strong>{{ $criteriaData['weight'] }}%</strong></td>
                                            <td colspan="3"></td>
                                            <td><strong>{{ number_format($criteriaData['weighted_score'], 2) }}</strong></td>
                                        </tr>
                                        @foreach ($criteriaData['details'] as $detailId => $detailData)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>{{ $detailData['name'] }}</td>
                                                <td>{{ $detailData['weight'] }}%</td>
                                                <td>{{ $detailData['score'] }}</td>
                                                <td>{{ number_format($detailData['score'] * $detailData['weight'] / 100, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-primary text-white">
                                            <th colspan="5" class="text-right">Total Nilai:</th>
                                            <th>{{ number_format($finalScore, 2) }}</th>
                                        </tr>
                                        <tr class="bg-info text-white">
                                            <th colspan="5" class="text-right">Grade:</th>
                                            <th>
                                                @php
                                                    $grade = '';
                                                    if ($finalScore >= 90) $grade = 'A';
                                                    elseif ($finalScore >= 80) $grade = 'B';
                                                    elseif ($finalScore >= 70) $grade = 'C';
                                                    elseif ($finalScore >= 60) $grade = 'D';
                                                    elseif ($finalScore > 0) $grade = 'E';
                                                    else $grade = '-';
                                                @endphp
                                                {{ $grade }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List of Individual Scores -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Rincian Penilaian Individual</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Tanggal</th>
                                        <th>Penilai</th>
                                        <th>Kriteria Utama</th>
                                        <th>Detail Kriteria</th>
                                        <th>Nilai</th>
                                        <th>Komentar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $employeeScores = \App\Models\EvaluationScore::with('evaluator', 'criteriaDetail.criteria')
                                            ->where('evaluated_employee_id', $selectedEmployee->id)
                                            ->when($selectedMonth, function($query) {
                                                return $query->whereMonth('date', $this->selectedMonth);
                                            })
                                            ->when($selectedYear, function($query) {
                                                return $query->whereYear('date', $this->selectedYear);
                                            })
                                            ->orderBy('date', 'desc')
                                            ->get();
                                    @endphp
                                    
                                    @forelse ($employeeScores as $index => $score)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($score->date)->format('d/m/Y') }}</td>
                                            <td>{{ $score->evaluator->name }}</td>
                                            <td>{{ $score->criteriaDetail->criteria->name }}</td>
                                            <td>{{ $score->criteriaDetail->name }}</td>
                                            <td>{{ $score->weight }}</td>
                                            <td>{{ $score->comment ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data penilaian</td>
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
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="delete-modal" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button wire:click="deleteConfirmed" type="button" class="btn btn-danger">
                        <span wire:loading.remove wire:target="deleteConfirmed">Hapus</span>
                        <span wire:loading wire:target="deleteConfirmed">Loading...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        window.addEventListener('livewire:initialized', () => {
            Livewire.on('success-message', (message) => {
                iziToast.success({
                    title: 'Berhasil',
                    message: message,
                    position: 'topRight'
                });
            });
            
            Livewire.on('failed-message', (message) => {
                iziToast.error({
                    title: 'Gagal',
                    message: message,
                    position: 'topRight'
                });
            });
        });
        
        // Show delete confirmation modal
        document.addEventListener('DOMContentLoaded', function() {
            window.addEventListener('livewire:initialized', () => {
                Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                    succeed(({ snapshot, effect }) => {
                        if (component.get('confirmingDelete')) {
                            $('#delete-modal').modal('show');
                        } else {
                            $('#delete-modal').modal('hide');
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</div>
```
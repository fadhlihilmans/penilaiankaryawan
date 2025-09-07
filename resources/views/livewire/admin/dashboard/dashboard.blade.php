<div>
<section class="section">
    <div class="section-header">
        <h1>Dashboard</h1>
    </div>

    <div class="row d-flex justify-content-center">
        <div class="col-lg-8 col-md-8 col-sm-12 col-12">
            <div class="card card-primary">
                <div class="card-wrap">
                <div class="card-header">
                    {{-- <h4>Selamat Datang, @if  {{ auth()->user()->name }} 👋</h4> --}}
                </div>
                <div class="card-body">
                    <p style="font-size: 16px">
                        Selamat datang di Sistem Penilaian Kinerja Karyawan. Platform ini membantu Anda mengelola dan memantau perkembangan kinerja seluruh karyawan dengan efektif dan transparan!
                    </p>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
            <i class="far fa-user"></i>
            </div>
            <div class="card-wrap">
            <div class="card-header">
                <h4>Total Karyawan</h4>
            </div>
            <div class="card-body">
                {{ $totalEmployee }}
            </div>
            </div>
        </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
            <i class="fas fa-briefcase"></i>
            </div>
            <div class="card-wrap">
            <div class="card-header">
                <h4>Total Jabatan</h4>
            </div>
            <div class="card-body">
                {{ $totalPosition }}
            </div>
            </div>
        </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
            <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="card-wrap">
            <div class="card-header">
                <h4>Total Kriteria Penilaian</h4>
            </div>
            <div class="card-body">
                {{ $totalEvaluationCriteria }}
            </div>
            </div>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12 col-sm-12">
        <div class="card">
            <div class="card-header">
            <h4>Penilaian Terakhir</h4>
            <div class="card-header-action">
                <a href="{{ route('admin.evaluasi-kriteria.nilai')}}" class="btn btn-primary">Nilai Karyawan</a>
            </div>
            </div>
            <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                <thead>
                    <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Tanggal</th>
                    <th>Penilaian Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                    <tr>
                        <td>{{ $employee->user->name ?? 'Data tidak tersedia' }}</td>
                        <td>{{ $employee->position->name ?? 'Data tidak tersedia' }}</td>
                        <td>
                            @if($employee->evaluated->isNotEmpty())
                                {{ $employee->evaluated->first()->created_at->format('d M Y') }}
                            @else
                                Belum ada penilaian
                            @endif
                        </td>
                        <td>
                            @if($employee->evaluated->isNotEmpty())
                                @php
                                    $totalScore = $employee->evaluated->avg('score') ?? 0;
                                @endphp
                                {{ number_format($totalScore, 1) }}
                            @else
                                Belum dinilai
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data karyawan</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
            </div>
        </div>
        </div>
    </div>
    </section>
</div>
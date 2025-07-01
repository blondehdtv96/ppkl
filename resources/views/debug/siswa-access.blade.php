@extends('layouts.app')

@section('title', 'Debug Akses Siswa')

@section('page-title', 'Debug Akses Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Wali Kelas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>Role:</strong> {{ auth()->user()->role }}</p>
                        <p><strong>Kelas Diampu (Legacy):</strong> Removed - using custom_kelas_diampu only</p>
                        <p><strong>Custom Kelas Diampu:</strong> {{ auth()->user()->custom_kelas_diampu ?? 'Tidak ada' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Legacy kelas_diampu fields removed</strong></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Test Akses Siswa</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Can View</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $allSiswa = App\Models\User::where('role', 'siswa')->get();
                                $user = auth()->user();
                            @endphp
                            @foreach($allSiswa as $siswa)
                            @php
                                $canView = $user->canViewSiswa($siswa);
                                $shouldView = false;
                                
                                // Tentukan apakah seharusnya bisa melihat berdasarkan custom_kelas_diampu
                                if ($user->custom_kelas_diampu) {
                                    $kelasArray = array_map('trim', explode(',', $user->custom_kelas_diampu));
                                    $shouldView = in_array($siswa->kelas, $kelasArray);
                                }
                                
                                $isCorrect = $canView === $shouldView;
                            @endphp
                            <tr class="{{ $isCorrect ? '' : 'table-danger' }}">
                                <td>{{ $siswa->id }}</td>
                                <td>{{ $siswa->name }}</td>
                                <td>{{ $siswa->kelas }}</td>
                                <td>{{ $siswa->jurusan }}</td>
                                <td>
                                    <span class="badge bg-{{ $canView ? 'success' : 'danger' }}">
                                        {{ $canView ? 'Ya' : 'Tidak' }}
                                    </span>
                                </td>
                                <td>
                                    @if($isCorrect)
                                        <span class="badge bg-success">✓ Benar</span>
                                    @else
                                        <span class="badge bg-danger">✗ Salah</span>
                                        <small class="text-muted">(Seharusnya: {{ $shouldView ? 'Ya' : 'Tidak' }})</small>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Siswa yang Ditampilkan di Index</h5>
            </div>
            <div class="card-body">
                @php
                    $query = App\Models\User::where('role', 'siswa');
                    
                    if ($user->custom_kelas_diampu) {
                        $kelasArray = array_map('trim', explode(',', $user->custom_kelas_diampu));
                        $query->whereIn('kelas', $kelasArray);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                    
                    $filteredSiswa = $query->get();
                @endphp
                
                <p><strong>Total siswa yang ditampilkan:</strong> {{ $filteredSiswa->count() }}</p>
                
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($filteredSiswa as $siswa)
                            <tr>
                                <td>{{ $siswa->name }}</td>
                                <td>{{ $siswa->kelas }}</td>
                                <td>{{ $siswa->jurusan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
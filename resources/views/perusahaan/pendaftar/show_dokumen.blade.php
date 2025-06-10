@ -0,0 +1,123 @@
{{-- resources/views/perusahaan/pendaftar/show_dokumen.blade.php --}}

@extends('perusahaan.template.layout') {{-- Assuming your company layout --}}

@section('title', 'Dokumen Pendaftar: ' . ($pendaftar->user->name ?? 'N/A'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dokumen Pendaftar: {{ $pendaftar->user->name ?? 'N/A' }} (Lowongan: {{ $pendaftar->lowongan->judul ?? 'N/A' }})</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="alert alert-info">
                            {{ session('info') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4>Status Lamaran Saat Ini:
                            <span class="badge {{
                                $pendaftar->status_lamaran == 'Pending' ? 'bg-warning' :
                                ($pendaftar->status_lamaran == 'Ditinjau' ? 'bg-info' :
                                ($pendaftar->status_lamaran == 'Wawancara' ? 'bg-primary' :
                                ($pendaftar->status_lamaran == 'Diterima' ? 'bg-success' : 'bg-danger')))
                            }}">
                                {{ $pendaftar->status_lamaran }}
                            </span>
                        </h4>
                        {{-- LINK UPDATED TO 'company.pendaftar.show' --}}
                        <p>Anda dapat mengubah status lamaran dari halaman <a href="{{ route('company.pendaftar.show', $pendaftar->id) }}">Detail Pendaftar</a>.</p> {{-- --}}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Dokumen</th>
                                    <th>Tipe File</th>
                                    <th>Status Validasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendaftar->dokumenPendaftars as $dokumen)
                                    <tr>
                                        <td>{{ $dokumen->nama_dokumen }}</td>
                                        <td>{{ strtoupper($dokumen->tipe_file) }}</td>
                                        <td>
                                            <span class="badge {{
                                                $dokumen->status_validasi == 'Valid' ? 'bg-success' :
                                                ($dokumen->status_validasi == 'Belum Diverifikasi' ? 'bg-secondary' :
                                                ($dokumen->status_validasi == 'Perlu Revisi' ? 'bg-warning' : 'bg-danger'))
                                            }}">
                                                {{ $dokumen->status_validasi }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ Storage::url($dokumen->file_path) }}" target="_blank" class="btn btn-sm btn-primary">Lihat Dokumen</a>

                                            {{-- Form to update document status --}}
                                            {{-- ACTION UPDATED TO 'company.pendaftar.updateStatusDokumen' --}}
                                            <form action="{{ route('company.pendaftar.updateStatusDokumen', [$pendaftar->id, $dokumen->id]) }}" method="POST" class="d-inline ml-2"> {{-- --}}
                                                @csrf
                                                @method('POST') {{-- Using POST for simplicity, can be PUT/PATCH if preferred for route --}}
                                                <div class="input-group input-group-sm">
                                                    <select name="status_validasi" class="form-control form-control-sm">
                                                        <option value="Belum Diverifikasi" {{ $dokumen->status_validasi == 'Belum Diverifikasi' ? 'selected' : '' }}>Belum Diverifikasi</option>
                                                        <option value="Valid" {{ $dokumen->status_validasi == 'Valid' ? 'selected' : '' }}>Valid</option>
                                                        <option value="Tidak Valid" {{ $dokumen->status_validasi == 'Tidak Valid' ? 'selected' : '' }}>Tidak Valid</option>
                                                        <option value="Perlu Revisi" {{ $dokumen->status_validasi == 'Perlu Revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-success btn-sm">Update</button>
                                                    </div>
                                                </div>
                                                @error('status_validasi', 'updateStatusDokumenError_' . $dokumen->id)
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada dokumen yang diunggah oleh pendaftar ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{-- LINK UPDATED TO 'company.pendaftar.index' --}}
                        <a href="{{ route('company.pendaftar.index') }}" class="btn btn-secondary">Kembali ke Daftar Pendaftar</a> {{-- --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
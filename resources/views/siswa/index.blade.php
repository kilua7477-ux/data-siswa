@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">

        {{-- Kartu Utama --}}
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary bg-gradient text-white p-3 rounded-top-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-people-fill me-2"></i>Data Siswa SMK Fatahillah Cileungsi
                </h5>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <form action="{{ route('siswa.index') }}" method="GET" class="d-flex">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control rounded-start-pill" placeholder="Cari nama atau NISN..." value="{{ request('search') }}">
                            <button class="btn btn-light rounded-end-pill px-3" type="submit" title="Cari">
                                <i class="bi bi-search text-primary"></i>
                            </button>
                        </div>
                    </form>
                    <button type="button" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                        <i class="bi bi-file-earmark-excel-fill me-1"></i> Import Excel
                    </button>
                    <a href="{{ route('siswa.create') }}" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm fw-semibold">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Siswa
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center px-3" style="width: 5%;">No</th>
                                <th>NISN</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th class="text-center" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataSiswa as $index => $siswa)
                                <tr>
                                    <td class="text-center fw-medium text-muted">
                                        {{ method_exists($dataSiswa, 'firstItem') && $dataSiswa->firstItem() ? ($dataSiswa->firstItem() + $index) : ($index + 1) }}
                                    </td>
                                    <td class="fw-semibold">{{ $siswa->nisn }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                            {{ $siswa->kelas }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($siswa->jenis_kelamin == 'L')
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-3">
                                                <i class="bi bi-gender-male me-1"></i> Laki-laki
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle rounded-pill px-3">
                                                <i class="bi bi-gender-female me-1"></i> Perempuan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-secondary small">{{ $siswa->alamat ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-outline-warning" title="Edit Data">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus Data">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                        Belum ada data siswa yang tersimpan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if (method_exists($dataSiswa, 'hasPages') && $dataSiswa->hasPages())
                <div class="card-footer bg-white border-0 py-3 rounded-bottom-4">
                    {{ $dataSiswa->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-success bg-gradient text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="importExcelModalLabel">
                    <i class="bi bi-file-earmark-excel-fill me-2"></i>Import Data Siswa dari Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 d-flex align-items-start gap-2 mb-3 rounded-3">
                        <i class="bi bi-info-circle-fill fs-5 mt-1 text-primary"></i>
                        <div>
                            <strong>Langkah Mudah:</strong>
                            <ol class="mb-1 ps-3 small">
                                <li>Unduh template Excel yang sudah disiapkan.</li>
                                <li>Isi data siswa sesuai kolom yang ada.</li>
                                <li>Simpan lalu unggah file Excel di bawah ini.</li>
                            </ol>
                            <a href="{{ route('siswa.template') }}" class="btn btn-sm btn-outline-primary mt-1 rounded-pill">
                                <i class="bi bi-download me-1"></i> Unduh Format Template Excel (.xlsx)
                            </a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="file_excel" class="form-label fw-semibold">Pilih File Excel / CSV</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx, .xls, .csv" required>
                        <div class="form-text">Format yang didukung: <code>.xlsx</code>, <code>.xls</code>, <code>.csv</code> (Maks. 5MB)</div>
                    </div>

                    <div class="card bg-light border-0 p-3 rounded-3">
                        <span class="fw-semibold small mb-1 text-secondary"><i class="bi bi-lightbulb me-1 text-warning"></i>Tips Pengisian:</span>
                        <ul class="mb-0 small text-muted ps-3">
                            <li><strong>NISN:</strong> Angka unik siswa (contoh: 1002345678).</li>
                            <li><strong>Jenis Kelamin:</strong> Isi dengan <code>L</code> (Laki-laki) atau <code>P</code> (Perempuan).</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success bg-gradient rounded-pill px-4">
                        <i class="bi bi-upload me-1"></i> Mulai Unggah & Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

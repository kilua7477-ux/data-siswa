@extends('layouts.app')

@content
@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h4 class="fw-bold mb-0 text-slate-800">Daftar Data Siswa</h4>
        <p class="text-muted small mb-0">Kelola informasi data siswa sekolah di sini.</p>
    </div>
    <div class="col-md-6 d-flex justify-content-md-end gap-2 mt-3 mt-md-0">
        <form action="{{ route('siswa.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari nama atau NISN..." value="{{ request('search') }}">
            <button class="btn btn-sm btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
        <button type="button" class="btn btn-sm btn-success d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#importExcelModal">
            <i class="bi bi-file-earmark-excel-fill"></i> Import Excel
        </button>
        <a href="{{ route('siswa.create') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <i class="bi bi-plus-lg"></i> Tambah Siswa
        </a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswas as $index => $item)
                        <tr>
                            <td class="text-center text-muted">{{ $siswas->firstItem() + $index }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $item->nisn }}</span></td>
                            <td class="fw-semibold">{{ $item->nama }}</td>
                            <td>{{ $item->kelas }}</td>
                            <td>
                                @if($item->jenis_kelamin == 'L')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">Laki-laki</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Perempuan</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $item->alamat ?? '-' }}</td>
                            <td class="text-center">
                                <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?');" action="{{ route('siswa.destroy', $item->id) }}" method="POST">
                                    <a href="{{ route('siswa.edit', $item->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                Data siswa belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($siswas->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $siswas->withQueryString()->links() }}
        </div>
    @endif
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold" id="importExcelModalLabel">
                    <i class="bi bi-file-earmark-excel-fill me-2"></i>Import Data Siswa dari Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-info-circle-fill fs-5 mt-1 text-primary"></i>
                        <div>
                            <strong>Langkah Mudah:</strong>
                            <ol class="mb-1 ps-3 small">
                                <li>Unduh template Excel yang sudah disiapkan.</li>
                                <li>Isi data siswa sesuai kolom yang ada.</li>
                                <li>Simpan lalu unggah file Excel di bawah ini.</li>
                            </ol>
                            <a href="{{ route('siswa.template') }}" class="btn btn-sm btn-outline-primary mt-1">
                                <i class="bi bi-download me-1"></i> Unduh Format Template Excel (.xlsx)
                            </a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="file_excel" class="form-label fw-semibold">Pilih File Excel / CSV</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx, .xls, .csv" required>
                        <div class="form-text">Format yang didukung: <code>.xlsx</code>, <code>.xls</code>, <code>.csv</code> (Maks. 5MB)</div>
                    </div>

                    <div class="card bg-light border-0 p-3">
                        <span class="fw-semibold small mb-1 text-secondary"><i class="bi bi-lightbulb me-1 text-warning"></i>Tips Pengisian:</span>
                        <ul class="mb-0 small text-muted ps-3">
                            <li><strong>NISN:</strong> Angka unik siswa (contoh: 1002345678).</li>
                            <li><strong>Jenis Kelamin:</strong> Isi dengan <code>L</code> (Laki-laki) atau <code>P</code> (Perempuan).</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-upload me-1"></i> Mulai Unggah & Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
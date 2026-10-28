@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Detail Data Siswa</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr><th>NISN</th><td>{{ $siswa->nisn }}</td></tr>
                        <tr><th>Nama Lengkap</th><td>{{ $siswa->nama }}</td></tr>
                        <tr><th>Jenis Kelamin</th><td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                        <tr><th>Kelas</th><td>{{ $siswa->kelas }}</td></tr>
                        <tr><th>Alamat</th><td>{{ $siswa->alamat }}</td></tr>
                    </table>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-warning text-white">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

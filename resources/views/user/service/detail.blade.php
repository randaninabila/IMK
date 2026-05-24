@extends('user.app')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-[#FFE4E6] to-white">
    <h1 class="text-4xl font-bold text-[#3E382D]">
        Detail Layanan {{ $layanan->nama_layanan }}
    </h1>
</div>

@endsection

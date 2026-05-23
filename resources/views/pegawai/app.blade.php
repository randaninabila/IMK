<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dina Salon Muslimah</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-b from-[#FFE4E6] via-[#FFF1F2] to-white font-sans min-h-screen">

    {{-- TOP NAVBAR --}}
@include('pegawai.layouts.navbar', ['user' => auth()->user()])

    {{-- SIDEBAR (Fixed) --}}
    @include('pegawai.layouts.sidebar')

    {{-- CONTENT: Pakai inline style untuk pastikan padding atas bekerja --}}
<main style="margin-left: 300px; padding-top: 75px;" class="px-8 pb-15">
    @yield('content')
</main>

</body>
</html>
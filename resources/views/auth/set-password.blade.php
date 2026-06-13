<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password — Dina Salon Muslimah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeLeft {
            from { opacity: 0; transform: translateX(-24px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .anim-left { animation: fadeLeft 0.65s ease forwards; }
        .anim-card { animation: fadeUp  0.65s ease 0.1s forwards; opacity: 0; }

        .form-input { transition: all 0.25s ease; }
        .form-input:focus {
            background: rgba(255,255,255,0.65) !important;
            box-shadow: 0 0 0 3px rgba(61,53,47,0.12);
        }

        .strength-bar { transition: width 0.4s ease, background-color 0.4s ease; }
        .rule-item { transition: color 0.25s ease; }
        .rule-item.met { color: #6b9e7a; }
        .rule-item.met .rule-icon { color: #6b9e7a; }

        .blob {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,173,178,0.18);
            filter: blur(32px);
            pointer-events: none;
        }

        .email-badge {
            background: rgba(61,53,47,0.07);
            border: 1px solid rgba(61,53,47,0.15);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center py-10 px-6
    bg-[radial-gradient(circle_at_top_left,_#FFADB2_0%,_transparent_40%),radial-gradient(circle_at_bottom_right,_#FFADB2_0%,_transparent_40%),linear-gradient(to_bottom,_#FFE4E6_0%,_#ffffff_100%)]">

<div class="w-full max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

    <!-- ========================= -->
    <!-- LEFT                      -->
    <!-- ========================= -->
    <div class="anim-left">

        <div class="inline-flex items-center gap-2 border border-[#3d352f]/20 rounded-full px-4 py-1.5 mb-6">
            <div class="w-2 h-2 rounded-full bg-[#c4a89a]"></div>
            <span class="text-[#3d352f] text-xs font-medium tracking-wide">Langkah Terakhir</span>
        </div>

        <h1 class="text-5xl md:text-6xl font-bold text-[#3d352f] leading-tight">
            Dina <span class="italic">Salon</span><br>
            Muslimah
        </h1>

        <p class="mt-5 text-[#5a4e47] text-base leading-relaxed max-w-sm">
            Kecantikan alami dimulai dari perawatan terbaik.
            Kami hadir untuk membuat Anda tampil lebih percaya diri setiap hari.
        </p>

        <!-- Progress steps -->
        <div class="mt-10 space-y-3 max-w-xs">
            <p class="text-xs text-[#3d352f]/50 uppercase tracking-widest font-medium mb-4">Proses Registrasi</p>

            @foreach([
                ['num' => '1', 'label' => 'Daftar Akun',        'state' => 'done'],
                ['num' => '2', 'label' => 'Verifikasi Email',    'state' => 'done'],
                ['num' => '3', 'label' => 'Buat Password',       'state' => 'active'],
            ] as $step)
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                    {{ $step['state'] === 'active' ? 'bg-[#3d352f] text-white shadow-md'
                      : ($step['state'] === 'done'   ? 'bg-[#3d352f]/40 text-white'
                      : 'bg-[#3d352f]/15 text-[#3d352f]/50') }}">
                    @if($step['state'] === 'done')
                        <i data-feather="check" class="w-3 h-3"></i>
                    @else
                        {{ $step['num'] }}
                    @endif
                </div>
                <span class="text-sm {{ $step['state'] === 'active' ? 'text-[#3d352f] font-semibold' : 'text-[#3d352f]/45' }}">
                    {{ $step['label'] }}
                </span>
            </div>
            @endforeach
        </div>

        <!-- Tips -->
        <div class="mt-8 max-w-xs bg-[#3d352f]/05 border border-[#3d352f]/12 rounded-2xl p-4 space-y-2">
            <p class="text-xs font-semibold text-[#3d352f] mb-2 flex items-center gap-1.5">
                <i data-feather="shield" class="w-3.5 h-3.5"></i>
                Tips Password Aman
            </p>
            <p class="text-xs text-[#6b5b4d] leading-relaxed">• Gunakan minimal <strong>8 karakter</strong> untuk keamanan lebih baik.</p>
            <p class="text-xs text-[#6b5b4d] leading-relaxed">• Kombinasikan huruf besar, angka, dan simbol.</p>
            <p class="text-xs text-[#6b5b4d] leading-relaxed">• Jangan gunakan tanggal lahir atau nama sebagai password.</p>
        </div>
    </div>

    <!-- ========================= -->
    <!-- CARD                      -->
    <!-- ========================= -->
    <div class="anim-card relative">

        <div class="blob w-48 h-48 -top-10 -right-10"></div>
        <div class="blob w-32 h-32 -bottom-8 -left-8"></div>

        <div class="relative bg-white/35 backdrop-blur-2xl border border-[#3D352F]/35
            rounded-[40px] p-8 md:p-10
            shadow-[0_12px_40px_rgba(61,53,47,0.20)]">

            <!-- Header -->
            <div class="text-center mb-7">
                <div class="w-16 h-16 rounded-2xl bg-[#3d352f]/08 border border-[#3d352f]/15 flex items-center justify-center mx-auto mb-4">
                    <i data-feather="lock" class="w-7 h-7 text-[#3d352f]"></i>
                </div>
                <h2 class="text-3xl font-bold text-[#3d352f]">Buat Password</h2>
                <p class="text-[#7a6d65] text-sm mt-2 leading-relaxed">
                    Halo, <strong>{{ auth()->user()->nama }}</strong>! Satu langkah lagi untuk masuk.
                </p>
                <div class="email-badge inline-flex items-center gap-2 rounded-full px-4 py-1.5 mt-3">
                    <i data-feather="mail" class="w-3.5 h-3.5 text-[#6b5b4d]"></i>
                    <span class="text-sm font-semibold text-[#3d352f]">{{ auth()->user()->email }}</span>
                </div>
            </div>

            {{-- SUCCESS --}}
            @if (session('success'))
            <div data-alert class="mb-5 flex items-start gap-3 bg-green-50/70 border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-sm">
                <i data-feather="check-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            {{-- ERROR --}}
            @if ($errors->any())
            <div data-alert class="mb-5 flex items-start gap-3 bg-red-50/70 border border-red-200 text-red-600 px-4 py-3 rounded-2xl text-sm">
                <i data-feather="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('register.set-password') }}" id="setPasswordForm">
                @csrf

                <!-- PASSWORD -->
                <div class="mb-4">
                    <label class="block mb-1.5 text-[#3d352f] text-[15px] font-semibold">
                        Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50">
                            <i data-feather="lock" class="w-4 h-4"></i>
                        </span>
                        <input id="password" name="password" type="password"
                            placeholder="Minimal 6 karakter"
                            autocomplete="new-password"
                            oninput="checkStrength(this.value)"
                            class="form-input w-full pl-11 pr-12 py-3 rounded-xl
                                border border-[#3D352F]/50
                                bg-white/40 backdrop-blur-md
                                text-[#3d352f] placeholder-[#3d352f]/40
                                focus:outline-none text-sm">
                        <button type="button" onclick="toggleVis('password','eyePass')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50 hover:text-[#3d352f] transition-colors">
                            <i id="eyePass" data-feather="eye" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="mt-2 h-1.5 bg-[#3d352f]/10 rounded-full overflow-hidden">
                        <div id="strengthBar" class="strength-bar h-full w-0 rounded-full bg-red-400"></div>
                    </div>
                    <p id="strengthLabel" class="text-xs mt-1 text-[#3d352f]/50">Masukkan password</p>

                    <ul class="mt-2 space-y-0.5 text-xs text-[#3d352f]/50">
                        <li class="rule-item flex items-center gap-1.5" id="rule-len">
                            <i data-feather="minus" class="rule-icon w-3 h-3"></i> Minimal 6 karakter
                        </li>
                        <li class="rule-item flex items-center gap-1.5" id="rule-num">
                            <i data-feather="minus" class="rule-icon w-3 h-3"></i> Mengandung angka
                        </li>
                        <li class="rule-item flex items-center gap-1.5" id="rule-upper">
                            <i data-feather="minus" class="rule-icon w-3 h-3"></i> Mengandung huruf kapital
                        </li>
                    </ul>

                    <p class="text-red-500 text-xs mt-1 hidden" id="passwordError">
                        <i data-feather="alert-circle" class="w-3 h-3 inline"></i> Password minimal 6 karakter
                    </p>
                </div>

                <!-- KONFIRMASI PASSWORD -->
                <div class="mb-5">
                    <label class="block mb-1.5 text-[#3d352f] text-[15px] font-semibold">
                        Konfirmasi Password <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50">
                            <i data-feather="check-circle" class="w-4 h-4"></i>
                        </span>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            placeholder="Ulangi password Anda"
                            autocomplete="new-password"
                            oninput="checkMatch()"
                            class="form-input w-full pl-11 pr-12 py-3 rounded-xl
                                border border-[#3D352F]/50
                                bg-white/40 backdrop-blur-md
                                text-[#3d352f] placeholder-[#3d352f]/40
                                focus:outline-none text-sm">
                        <button type="button" onclick="toggleVis('password_confirmation','eyeConfirm')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-[#3d352f]/50 hover:text-[#3d352f] transition-colors">
                            <i id="eyeConfirm" data-feather="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <p class="text-red-500 text-xs mt-1 hidden" id="confirmError">
                        <i data-feather="alert-circle" class="w-3 h-3 inline"></i> Password tidak cocok
                    </p>
                    <p class="text-green-600 text-xs mt-1 hidden" id="matchMsg">
                        <i data-feather="check" class="w-3 h-3 inline"></i> Password cocok!
                    </p>
                </div>

                <!-- SYARAT & KETENTUAN -->
                <div class="mb-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" id="agree" name="agree"
                            class="w-4 h-4 mt-0.5 accent-[#3d352f] flex-shrink-0 cursor-pointer">
                        <span class="text-sm text-[#3d352f]/80 leading-relaxed">
                            Saya menyetujui
                            <button type="button" onclick="openModal('modalTerms')"
                                class="underline underline-offset-1 font-semibold text-[#3d352f] hover:text-[#6b5b4d] transition-colors">
                                Syarat & Ketentuan
                            </button>
                            serta
                            <button type="button" onclick="openModal('modalPrivacy')"
                                class="underline underline-offset-1 font-semibold text-[#3d352f] hover:text-[#6b5b4d] transition-colors">
                                Kebijakan Privasi
                            </button>
                            Dina Salon Muslimah
                        </span>
                    </label>
                    <p class="text-red-500 text-xs mt-1 hidden" id="agreeError">
                        <i data-feather="alert-circle" class="w-3 h-3 inline"></i> Kamu harus menyetujui syarat & ketentuan
                    </p>
                </div>

                <!-- SUBMIT -->
                <button type="submit" id="submitBtn"
                    class="w-full py-4 rounded-2xl text-white text-base font-semibold
                        bg-gradient-to-r from-[#3d352f] to-[#6b5b4d]
                        hover:scale-[1.02] active:scale-[0.98] transition duration-300
                        shadow-md flex items-center justify-center gap-2.5">
                    <i data-feather="user-check" class="w-4 h-4"></i>
                    Selesai & Masuk
                </button>
            </form>

        </div>
    </div>
</div>

<!-- MODAL SYARAT & KETENTUAN -->
<div id="modalTerms" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
    onclick="closeOnBackdrop(event, 'modalTerms')">
    <div class="absolute inset-0 bg-[#3d352f]/40 backdrop-blur-sm"></div>
    <div class="relative w-full max-w-lg max-h-[85vh] flex flex-col bg-white/80 backdrop-blur-2xl
        border border-[#3D352F]/30 rounded-[32px] shadow-[0_16px_48px_rgba(61,53,47,0.3)] modal-panel">
        <div class="flex items-center justify-between px-8 pt-7 pb-4 border-b border-[#3d352f]/10 flex-shrink-0">
            <div>
                <h3 class="text-2xl font-bold text-[#3d352f]" style="font-family:'Playfair Display',serif;">Syarat &amp; Ketentuan</h3>
                <p class="text-xs text-[#3d352f]/50 mt-0.5">Berlaku sejak 1 Januari 2025</p>
            </div>
            <button onclick="closeModal('modalTerms')" class="w-9 h-9 rounded-full bg-[#3d352f]/10 hover:bg-[#3d352f]/20 flex items-center justify-center transition-colors flex-shrink-0">
                <i data-feather="x" class="w-4 h-4 text-[#3d352f]"></i>
            </button>
        </div>
        <div class="overflow-y-auto px-8 py-5 space-y-5 text-sm text-[#3d352f]/80 leading-relaxed">
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">1. Penerimaan Syarat</h4><p>Dengan mendaftar dan menggunakan layanan Dina Salon Muslimah, Anda dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku. Jika Anda tidak setuju, harap tidak melanjutkan proses pendaftaran.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">2. Akun Pengguna</h4><p>Anda bertanggung jawab penuh atas kerahasiaan akun dan kata sandi Anda. Segala aktivitas yang terjadi melalui akun Anda adalah tanggung jawab Anda sepenuhnya. Segera hubungi kami jika Anda menduga terjadi penggunaan akun tanpa izin.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">3. Pemesanan & Pembatalan</h4><p>Booking yang telah dikonfirmasi dapat dibatalkan maksimal <strong>2 jam sebelum</strong> jadwal layanan. Pembatalan mendadak tanpa pemberitahuan dapat memengaruhi prioritas jadwal Anda di masa mendatang.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">4. Pembayaran</h4><p>Dina Salon Muslimah menerima pembayaran secara tunai (cash) dan QRIS. Bukti pembayaran wajib disimpan hingga layanan selesai.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">5. Hak & Kewajiban Pelanggan</h4><p>Pelanggan berhak mendapatkan layanan sesuai yang telah dipesan. Keterlambatan lebih dari 15 menit dapat menyebabkan jadwal dialihkan.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">6. Larangan</h4><p>Pengguna dilarang menyalahgunakan sistem booking atau memberikan informasi palsu. Pelanggaran dapat mengakibatkan penonaktifan akun.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">7. Perubahan Syarat</h4><p>Kami berhak mengubah syarat dan ketentuan ini sewaktu-waktu. Perubahan akan diberitahukan melalui aplikasi atau email terdaftar Anda.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">8. Hubungi Kami</h4><p>📍 Jl. Tuasan No.76, Medan Tembung<br>📱 WhatsApp resmi Dina Salon Muslimah</p></section>
        </div>
        <div class="px-8 py-5 border-t border-[#3d352f]/10 flex-shrink-0">
            <button onclick="acceptAndClose('modalTerms')" class="w-full py-3 rounded-2xl text-white font-semibold text-sm bg-gradient-to-r from-[#3d352f] to-[#6b5b4d] hover:scale-[1.02] transition duration-300">
                Saya Mengerti &amp; Setuju
            </button>
        </div>
    </div>
</div>

<!-- MODAL KEBIJAKAN PRIVASI -->
<div id="modalPrivacy" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
    onclick="closeOnBackdrop(event, 'modalPrivacy')">
    <div class="absolute inset-0 bg-[#3d352f]/40 backdrop-blur-sm"></div>
    <div class="relative w-full max-w-lg max-h-[85vh] flex flex-col bg-white/80 backdrop-blur-2xl
        border border-[#3D352F]/30 rounded-[32px] shadow-[0_16px_48px_rgba(61,53,47,0.3)] modal-panel">
        <div class="flex items-center justify-between px-8 pt-7 pb-4 border-b border-[#3d352f]/10 flex-shrink-0">
            <div>
                <h3 class="text-2xl font-bold text-[#3d352f]" style="font-family:'Playfair Display',serif;">Kebijakan Privasi</h3>
                <p class="text-xs text-[#3d352f]/50 mt-0.5">Berlaku sejak 1 Januari 2025</p>
            </div>
            <button onclick="closeModal('modalPrivacy')" class="w-9 h-9 rounded-full bg-[#3d352f]/10 hover:bg-[#3d352f]/20 flex items-center justify-center transition-colors flex-shrink-0">
                <i data-feather="x" class="w-4 h-4 text-[#3d352f]"></i>
            </button>
        </div>
        <div class="overflow-y-auto px-8 py-5 space-y-5 text-sm text-[#3d352f]/80 leading-relaxed">
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">1. Data yang Kami Kumpulkan</h4><p>Kami mengumpulkan data yang Anda berikan saat mendaftar, meliputi: nama lengkap, alamat email, nomor WhatsApp, dan data terkait riwayat booking layanan Anda.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">2. Tujuan Penggunaan Data</h4><p>Data Anda kami gunakan untuk mengelola akun dan riwayat booking Anda, mengirimkan konfirmasi dan pengingat jadwal, serta meningkatkan kualitas layanan kami.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">3. Keamanan Data</h4><p>Kami menerapkan enkripsi dan langkah-langkah keamanan standar industri untuk melindungi data Anda. Kata sandi Anda disimpan dalam bentuk terenkripsi dan tidak dapat dibaca oleh siapapun, termasuk tim kami.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">4. Berbagi Data dengan Pihak Ketiga</h4><p>Kami <strong>tidak menjual</strong> data pribadi Anda kepada pihak manapun. Data hanya dibagikan kepada pihak ketiga yang diperlukan untuk operasional layanan, dengan perjanjian kerahasiaan yang ketat.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">5. Hak Anda</h4><p>Anda berhak untuk mengakses, memperbarui, atau menghapus data pribadi Anda kapan saja. Hubungi kami melalui WhatsApp atau email untuk mengajukan permintaan tersebut.</p></section>
            <section><h4 class="font-bold text-[#3d352f] mb-1.5">6. Kontak Privasi</h4><p>📍 Jl. Tuasan No.76, Medan Tembung<br>📱 WhatsApp resmi Dina Salon Muslimah</p></section>
        </div>
        <div class="px-8 py-5 border-t border-[#3d352f]/10 flex-shrink-0">
            <button onclick="acceptAndClose('modalPrivacy')" class="w-full py-3 rounded-2xl text-white font-semibold text-sm bg-gradient-to-r from-[#3d352f] to-[#6b5b4d] hover:scale-[1.02] transition duration-300">
                Saya Mengerti &amp; Setuju
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.94) translateY(16px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-panel { animation: modalIn 0.3s ease forwards; }
    body.modal-open { overflow: hidden; }
</style>

<script>
feather.replace();

document.querySelectorAll('[data-alert]').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        el.style.opacity = '0';
        el.style.transform = 'translateY(-8px)';
        setTimeout(() => el.remove(), 500);
    }, 4000);
});

function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.body.classList.add('modal-open');
    feather.replace();
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.classList.remove('modal-open');
}
function closeOnBackdrop(event, id) {
    if (event.target === document.getElementById(id) ||
        event.target === document.getElementById(id).querySelector('.absolute')) {
        closeModal(id);
    }
}
function acceptAndClose(id) {
    closeModal(id);
    document.getElementById('agree').checked = true;
    document.getElementById('agreeError').classList.add('hidden');
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeModal('modalTerms'); closeModal('modalPrivacy'); }
});

function toggleVis(inputId, iconId) {
    const inp  = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.setAttribute('data-feather', 'eye-off');
    } else {
        inp.type = 'password';
        icon.setAttribute('data-feather', 'eye');
    }
    feather.replace();
}

function checkStrength(val) {
    const bar        = document.getElementById('strengthBar');
    const label      = document.getElementById('strengthLabel');
    const hasLen     = val.length >= 6;
    const hasNum     = /\d/.test(val);
    const hasUpper   = /[A-Z]/.test(val);
    const hasSpecial = /[^A-Za-z0-9]/.test(val);
    updateRule('rule-len',   hasLen);
    updateRule('rule-num',   hasNum);
    updateRule('rule-upper', hasUpper);
    let score = 0;
    if (hasLen)     score++;
    if (hasNum)     score++;
    if (hasUpper)   score++;
    if (hasSpecial) score++;
    const map = [
        { w: '0%',   color: 'bg-red-400',    text: 'Masukkan password' },
        { w: '25%',  color: 'bg-red-400',    text: 'Lemah' },
        { w: '50%',  color: 'bg-orange-400', text: 'Cukup' },
        { w: '75%',  color: 'bg-yellow-400', text: 'Kuat' },
        { w: '100%', color: 'bg-green-500',  text: 'Sangat Kuat 💪' },
    ];
    const entry = val.length === 0 ? map[0] : map[score];
    bar.style.width   = entry.w;
    bar.className     = `strength-bar h-full rounded-full ${entry.color}`;
    label.textContent = entry.text;
    label.className   = `text-xs mt-1 ${score >= 3 ? 'text-green-600' : score >= 2 ? 'text-orange-500' : 'text-red-400'}`;
}

function updateRule(id, met) {
    const el     = document.getElementById(id);
    const iconEl = el.querySelector('.rule-icon');
    el.classList.toggle('met', met);
    iconEl.setAttribute('data-feather', met ? 'check' : 'minus');
    feather.replace();
}

function checkMatch() {
    const pw = document.getElementById('password').value;
    const cf = document.getElementById('password_confirmation').value;
    if (cf === '') {
        document.getElementById('confirmError').classList.add('hidden');
        document.getElementById('matchMsg').classList.add('hidden');
        return;
    }
    if (pw === cf) {
        document.getElementById('confirmError').classList.add('hidden');
        document.getElementById('matchMsg').classList.remove('hidden');
        document.getElementById('password_confirmation').classList.remove('border-red-400');
    } else {
        document.getElementById('matchMsg').classList.add('hidden');
        document.getElementById('confirmError').classList.remove('hidden');
        feather.replace();
    }
}

document.getElementById('setPasswordForm').addEventListener('submit', function(e) {
    let valid = true;
    const pw    = document.getElementById('password').value;
    const cf    = document.getElementById('password_confirmation').value;
    const agree = document.getElementById('agree').checked;

    if (pw.length < 6) {
        document.getElementById('passwordError').classList.remove('hidden');
        document.getElementById('password').classList.add('border-red-400');
        feather.replace(); valid = false;
    } else {
        document.getElementById('passwordError').classList.add('hidden');
        document.getElementById('password').classList.remove('border-red-400');
    }

    if (pw !== cf || cf === '') {
        document.getElementById('confirmError').classList.remove('hidden');
        document.getElementById('matchMsg').classList.add('hidden');
        feather.replace(); valid = false;
    } else {
        document.getElementById('confirmError').classList.add('hidden');
    }

    if (!agree) {
        document.getElementById('agreeError').classList.remove('hidden');
        feather.replace(); valid = false;
    } else {
        document.getElementById('agreeError').classList.add('hidden');
    }

    if (!valid) { e.preventDefault(); return; }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
    </svg> Menyimpan...`;
});

document.getElementById('password').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); document.getElementById('password_confirmation').focus(); }
});
</script>

</body>
</html>
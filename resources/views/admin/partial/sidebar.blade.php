@php
    $sidebarLogoPath = 'assets/dashboard/hero.jpg';
    $sidebarLogoExists = file_exists(public_path($sidebarLogoPath));
@endphp

<aside class="fixed left-0 top-0 z-40 h-screen w-[235px] bg-white shadow-[4px_0_18px_rgba(0,0,0,0.08)]">
    <div class="flex h-full flex-col px-5 py-6">

        {{-- LOGO --}}
        <div class="mb-8 flex items-center gap-3">
            <div class="relative flex h-11 w-11 items-center justify-center overflow-hidden rounded-full bg-[#F4A1AC] shadow-sm">
                @if($sidebarLogoExists)
                    <img src="{{ asset($sidebarLogoPath) }}"
                         alt="Logo Salon Dina Muslimah"
                         class="absolute left-1/2 top-1/2 h-[138px] w-[138px] max-w-none -translate-x-1/2 -translate-y-1/2 object-cover object-center">
                @else
                    <span class="text-xl font-bold text-white">D</span>
                @endif
            </div>

            <div>
                <h1 class="text-[17px] font-bold leading-tight text-[#4B3A36]">
                    Salon <span class="italic">Dina</span>
                </h1>
                <h2 class="text-[17px] font-bold leading-tight text-[#4B3A36]">
                    Muslimah
                </h2>
            </div>
        </div>

        {{-- BADGE --}}
        <div class="mb-7 inline-flex w-fit rounded-full bg-[#F8E8E5] px-4 py-1.5">
            <span class="text-[11px] font-semibold text-[#6B4D46]">
                Salon Khusus Wanita
            </span>
        </div>

        {{-- LINK KE HALAMAN PUBLIK --}}
        <a href="{{ url('/') }}"
        class="mb-4 flex items-center gap-2 rounded-[8px] px-4 py-2.5 text-[13px] font-semibold text-[#6B4D46] hover:bg-[#FFF1F1] transition border border-[#F1E1DF]">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12H9m0 0l3-3m-3 3l3 3M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/>
            </svg>
            Lihat Halaman Publik
        </a>

        {{-- MENU --}}
        <nav class="flex flex-1 flex-col gap-1.5">
            @php
                $menus = [
                    [
                        'name' => 'Dashboard',
                        'url'  => '/admin/dashboard',
                        'active' => request()->is('admin/dashboard') || request()->is('dashboard'),
                    ],
                    [
                        'name' => 'Penjadwalan',
                        'url'  => '/admin/penjadwalan',
                        'active' => request()->is('admin/penjadwalan*'),
                    ],
                    [
                        'name' => 'Pegawai',
                        'url'  => '/admin/pegawai',
                        'active' => request()->is('admin/pegawai*'),
                    ],
                    [
                        'name' => 'Pelanggan',
                        'url'  => '/admin/pelanggan',
                        'active' => request()->is('admin/pelanggan*'),
                    ],
                    [
                        'name' => 'Layanan',
                        'url'  => '/admin/layanan',
                        'active' => request()->is('admin/layanan*'),
                    ],
                    [
                        'name' => 'Ulasan & Saran',
                        'url'  => '/admin/ulasan-saran',
                        'active' => request()->is('admin/ulasan-saran*'),
                    ],
                    [
                        'name' => 'Pengaturan',
                        'url'  => '/admin/pengaturan',
                        'active' => request()->is('admin/pengaturan*'),
                    ],
                    [
                        'name' => 'Promo',
                        'url'  => '/admin/input-promo',
                        'active' => request()->is('admin/input-promo*'),
                    ],
                ];
            @endphp

            @foreach ($menus as $menu)
                <a href="{{ $menu['url'] === '#' ? '#' : url($menu['url']) }}"
                   class="group flex items-center rounded-[8px] px-4 py-3 text-[15px] font-semibold transition-all duration-200
                   {{ $menu['active']
                        ? 'bg-[#F9DFDF] text-[#4B3A36]'
                        : 'text-[#5B4A45] hover:bg-[#FFF1F1] hover:text-[#4B3A36]'
                   }}">
                    {{ $menu['name'] }}
                </a>
            @endforeach
        </nav>

        {{-- LOGOUT --}}
        <div class="mt-6 border-t border-[#F1E1DF] pt-5">
            <button type="button"
                    onclick="openAdminLogoutModal()"
                    class="flex w-full items-center gap-3 rounded-[8px] px-4 py-3 text-[15px] font-semibold text-[#B85C6A] transition-all duration-200 hover:bg-[#FFF1F1]">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>

                <span>Keluar</span>
            </button>
        </div>

    </div>
</aside>

{{-- LOGOUT CONFIRMATION MODAL --}}
<div id="adminLogoutModal"
     class="hidden fixed inset-0 z-[9999] items-center justify-center bg-black/30 px-6">

    <div class="w-full max-w-[380px] rounded-[18px] bg-white p-6 shadow-2xl border border-[#F1E1DF]">

        <div class="flex items-start gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#FFF1F1] text-[#B85C6A]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2.2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </div>

            <div>
                <h2 class="text-[18px] font-extrabold text-[#3F3838] leading-tight">
                    Keluar dari akun?
                </h2>
                <p class="mt-2 text-[13px] leading-relaxed text-[#6F5E5E]">
                    Kamu akan keluar dari akun admin dan diarahkan ke halaman login.
                </p>
            </div>
        </div>

        <form method="POST"
              action="{{ route('logout') }}"
              class="mt-6 flex items-center justify-end gap-3">
            @csrf
            <button type="button"
                    onclick="closeAdminLogoutModal()"
                    class="rounded-[8px] bg-[#F1E8E8] px-5 py-2 text-[13px] font-bold text-[#4B4242] transition hover:bg-[#E8DCDC]">
                Batal
            </button>
            <button type="submit"
                    class="rounded-[8px] bg-[#B85C6A] px-5 py-2 text-[13px] font-bold text-white transition hover:bg-[#A94F5D]">
                Ya, Keluar
            </button>
        </form>
    </div>
</div>

<script>
    window.openAdminLogoutModal = function () {
        const modal = document.getElementById('adminLogoutModal');
        if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
    }
    window.closeAdminLogoutModal = function () {
        const modal = document.getElementById('adminLogoutModal');
        if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    }
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('adminLogoutModal');
        if (event.target === modal) closeAdminLogoutModal();
    });
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') closeAdminLogoutModal();
    });
</script>
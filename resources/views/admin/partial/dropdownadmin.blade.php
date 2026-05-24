@php
    $adminUser = auth()->user();

    $adminName = $adminUser->nama
        ?? $adminUser->name
        ?? 'Admin Salon';

    $adminRole = strtoupper($adminUser->role ?? 'ADMIN');

    $adminInitials = collect(explode(' ', $adminName))
        ->filter()
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->take(2)
        ->implode('');

    $adminPhoto = $adminUser->foto_profile
        ?? $adminUser->foto
        ?? null;

    $adminPhotoUrl = $adminPhoto
        ? (str_starts_with($adminPhoto, 'http') ? $adminPhoto : asset($adminPhoto))
        : null;
@endphp

<div class="relative">
    {{-- PROFILE BUTTON --}}
    <button type="button"
            onclick="toggleAdminDropdown()"
            class="flex items-center gap-[14px] rounded-full transition hover:opacity-90">

        <div class="flex h-[58px] w-[58px] items-center justify-center overflow-hidden rounded-full bg-white shadow-sm border border-[#F1D9DD]">
            @if($adminPhotoUrl)
                <img src="{{ $adminPhotoUrl }}"
                     alt="{{ $adminName }}"
                     class="h-full w-full object-cover"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                <span class="hidden h-full w-full items-center justify-center bg-[#F8D8DE] text-[16px] font-extrabold text-[#7A4B55]">
                    {{ $adminInitials ?: 'A' }}
                </span>
            @else
                <span class="flex h-full w-full items-center justify-center bg-[#F8D8DE] text-[16px] font-extrabold text-[#7A4B55]">
                    {{ $adminInitials ?: 'A' }}
                </span>
            @endif
        </div>

        <svg class="h-[22px] w-[22px]" viewBox="0 0 24 24" fill="none">
            <path d="M6 9L12 15L18 9"
                  stroke="#4B3A36"
                  stroke-width="3"
                  stroke-linecap="round"
                  stroke-linejoin="round"/>
        </svg>
    </button>

    {{-- DROPDOWN MENU --}}
    <div id="adminProfileDropdown"
         class="hidden absolute right-0 top-[70px] z-[999] w-[205px] overflow-hidden rounded-[14px] border border-[#F1D9DD] bg-white shadow-[0_18px_38px_rgba(58,55,46,0.16)]">

        <div class="border-b border-[#F1E1DF] px-4 py-4">
            <p class="text-[14px] font-extrabold leading-tight text-[#3F3838]">
                {{ $adminName }}
            </p>

            <p class="mt-1 text-[11px] font-bold tracking-[0.08em] text-[#B85C6A]">
                {{ $adminRole }}
            </p>
        </div>

        <a href="{{ route('admin.pengaturan') }}"
           class="block w-full px-4 py-3 text-left text-[14px] font-bold text-[#4B3A36] transition hover:bg-[#FFF1F1]">
            Profile Admin
        </a>

        <button type="button"
                onclick="openDropdownLogoutModal()"
                class="block w-full px-4 py-3 text-left text-[14px] font-bold text-[#B85C6A] transition hover:bg-[#FFF1F1]">
            Keluar
        </button>
    </div>
</div>

{{-- LOGOUT CONFIRMATION MODAL --}}
<div id="dropdownLogoutModal"
     class="hidden fixed inset-0 z-[10000] items-center justify-center bg-black/30 px-6">

    <div class="w-full max-w-[380px] rounded-[18px] border border-[#F1E1DF] bg-white p-6 shadow-2xl">
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
                <h2 class="text-[18px] font-extrabold leading-tight text-[#3F3838]">
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
                    onclick="closeDropdownLogoutModal()"
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
    window.toggleAdminDropdown = function () {
        const dropdown = document.getElementById('adminProfileDropdown');

        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    window.closeAdminDropdown = function () {
        const dropdown = document.getElementById('adminProfileDropdown');

        if (dropdown) {
            dropdown.classList.add('hidden');
        }
    }

    window.openDropdownLogoutModal = function () {
        closeAdminDropdown();

        const modal = document.getElementById('dropdownLogoutModal');

        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    window.closeDropdownLogoutModal = function () {
        const modal = document.getElementById('dropdownLogoutModal');

        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('adminProfileDropdown');
        const dropdownWrapper = event.target.closest('#adminProfileDropdown');
        const dropdownButton = event.target.closest('button[onclick="toggleAdminDropdown()"]');
        const logoutModal = document.getElementById('dropdownLogoutModal');

        if (dropdown && !dropdownWrapper && !dropdownButton) {
            dropdown.classList.add('hidden');
        }

        if (event.target === logoutModal) {
            closeDropdownLogoutModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAdminDropdown();
            closeDropdownLogoutModal();
        }
    });
</script>
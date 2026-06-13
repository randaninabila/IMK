<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pegawai - Dina Salon Muslimah</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            overflow-x: hidden;
        }

        .soft-card {
            box-shadow: 0 10px 24px rgba(58, 55, 46, 0.10);
        }

        .modal-bg {
            background: rgba(0, 0, 0, 0.28);
        }

        .soft-shadow {
            box-shadow: 0 8px 18px rgba(58, 55, 46, 0.10);
        }
    </style>
</head>

<body class="bg-[#FFF3F5] text-[#4B4242]">

@php
    $employees = $employees ?? collect();
    $branches = $branches ?? collect();
    $selectedCabangId = $selectedCabangId ?? null;
    $selectedBranch = $selectedBranch ?? null;

    $branchButtonText = $selectedBranch
        ? ($selectedBranch->label ?? $selectedBranch->nama_cabang)
        : 'Semua Cabang';
@endphp

<div class="min-h-screen flex">

    @include('admin.partial.sidebar')

    <main class="ml-[235px] w-[calc(100%-235px)] min-h-screen bg-gradient-to-b from-white via-[#FFF7F8] to-[#FDE7EC]">

        <header class="h-[92px] px-[58px] flex items-center justify-between">

            <h2 class="text-[22px] font-extrabold text-[#3F3838] tracking-[-0.03em]">
                Halo, <span class="italic">Admin</span> Salon Dina Muslimah 👋
            </h2>

            <div class="flex items-center gap-[22px]">

                <div class="relative">
                    <button type="button"
                            onclick="toggleDropdown('branchDropdown')"
                            class="h-[50px] min-w-[202px] bg-[#E8A9B4] text-white rounded-[7px] px-[12px] flex items-center justify-between gap-[12px] font-extrabold hover:bg-[#D995A1] transition">
                        <span class="flex items-center gap-[8px]">
                            <svg class="w-[22px] h-[22px]" viewBox="0 0 24 24" fill="none">
                                <path d="M12 21S5 14.7 5 8.8C5 4.9 8.1 2 12 2C15.9 2 19 4.9 19 8.8C19 14.7 12 21 12 21Z" stroke="white" stroke-width="2"/>
                                <circle cx="12" cy="8.8" r="2.5" stroke="white" stroke-width="2"/>
                            </svg>

                            <span id="branchText" class="text-[13px]">
                                {{ $branchButtonText }}
                            </span>
                        </span>

                        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9L12 15L18 9" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <div id="branchDropdown"
                         class="hidden absolute top-[58px] left-0 w-full bg-white rounded-[12px] shadow-xl border border-[#F1D9DD] overflow-hidden z-50">
                        <a href="{{ route('admin.pegawai') }}"
                           class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36] {{ !$selectedCabangId ? 'bg-[#FFF0F2]' : '' }}">
                            Semua Cabang
                        </a>

                        @forelse($branches as $branch)
                            <a href="{{ route('admin.pegawai', ['cabang_id' => $branch->cabang_id]) }}"
                               class="block w-full text-left px-4 py-3 hover:bg-[#FFF0F2] text-sm font-bold text-[#4B3A36] {{ (int) $selectedCabangId === (int) $branch->cabang_id ? 'bg-[#FFF0F2]' : '' }}">
                                {{ $branch->label ?? $branch->nama_cabang }}
                            </a>
                        @empty
                            <div class="px-4 py-3 text-sm font-bold text-[#8B7777]">
                                Belum ada cabang
                            </div>
                        @endforelse
                    </div>
                </div>


                {{-- PROFILE DROPDOWN PARTIAL --}}
                <div class="relative flex items-center">
                    @include('admin.partial.dropdownadmin')
                </div>

            </div>
        </header>

        @if(session('success'))
            <div class="mx-[42px] mt-[8px] rounded-[12px] bg-green-100 text-green-700 px-5 py-3 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mx-[42px] mt-[8px] rounded-[12px] bg-red-100 text-red-600 px-5 py-3 text-sm font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="px-[42px] mt-[14px] pb-[45px]">

            <div class="bg-[#FDE7EC] rounded-[18px] soft-card min-h-[872px] px-[26px] pt-[22px] pb-[36px]">

                <div class="flex items-center justify-between mb-[26px]">
                    <div>
                        <h1 class="text-[22px] font-extrabold text-[#3F3838]">
                            Pegawai Tersedia
                        </h1>

                        <p class="mt-[4px] text-[13px] font-semibold text-[#7B6A62]">
                            Data yang tampil hanya akun dengan role pegawai.
                        </p>
                    </div>

                    <button type="button"
                            onclick="openCreateEmployeeModal()"
                            class="h-[34px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white rounded-[8px] px-[14px] flex items-center gap-[8px] text-[13px] font-extrabold transition">
                        Tambah Pegawai
                        <span class="text-[22px] leading-none">+</span>
                    </button>
                </div>

                <div class="mb-[18px] bg-white rounded-[14px] px-[18px] py-[15px] soft-shadow">
                    <div class="flex items-center justify-between gap-[16px]">
                        <div>
                            <h3 class="text-[15px] font-extrabold text-[#3F3838]">
                                Cari Pegawai
                            </h3>
                            <p class="mt-[3px] text-[12px] font-semibold text-[#8B7777]">
                                Ketik nama pegawai atau specialist untuk memfilter data pada tabel.
                            </p>
                        </div>

                        <div class="relative w-[520px] max-w-full">
                            <input id="employeeSearchInput"
                                   type="text"
                                   oninput="searchEmployeesByName()"
                                   placeholder="Cari berdasarkan nama pegawai..."
                                   class="w-full h-[44px] rounded-[10px] border border-[#F1D9DD] bg-[#FFF8F9] pl-[44px] pr-[96px] text-[14px] font-semibold text-[#4B4242] outline-none focus:ring-2 focus:ring-[#E8A9B4]">

                            <svg class="absolute left-[15px] top-1/2 -translate-y-1/2 w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none">
                                <circle cx="11" cy="11" r="7" stroke="#9A7B7B" stroke-width="2"/>
                                <path d="M16.5 16.5L21 21" stroke="#9A7B7B" stroke-width="2" stroke-linecap="round"/>
                            </svg>

                            <button type="button"
                                    onclick="resetEmployeeSearch()"
                                    class="absolute right-[8px] top-1/2 -translate-y-1/2 h-[30px] rounded-[8px] bg-[#EFE4E4] px-[12px] text-[12px] font-extrabold text-[#6B5A55] hover:bg-[#E8D1D5] transition">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[12px] overflow-hidden soft-shadow">
                    <table class="w-full text-center">
                        <thead>
                        <tr class="h-[45px] text-[16px] font-extrabold text-[#4B4242] border-b border-[#F1C7CE] bg-[#FFF8F9]">
                            <th class="w-[70px]">Id</th>
                            <th>Nama Pegawai</th>
                            <th class="w-[190px]">Cabang</th>
                            <th class="w-[150px]">Status</th>
                            <th class="w-[230px]">Detail</th>
                            <th class="w-[140px]">Aksi</th>
                        </tr>
                        </thead>

                        <tbody id="employeeTable">
                        @forelse($employees as $employee)
                            @php
                                $statusKerja = $employee->status_kerja ?? 'aktif';
                                $namaCabang = $employee->nama_cabang ?? '-';
                                $jabatan = $employee->jabatan ?? '-';
                                $deskripsi = $employee->deskripsi ?? '-';
                                $noHp = $employee->no_hp ?? '-';
                                $email = $employee->email ?? '-';
                            @endphp

                            <tr class="employee-row h-[54px] border-b border-[#F6E0E4] hover:bg-[#FFF8F9] transition"
                                data-id="{{ $employee->pegawai_id }}"
                                data-name="{{ e($employee->nama ?? '-') }}"
                                data-role="{{ e($jabatan) }}"
                                data-phone="{{ e($noHp) }}"
                                data-email="{{ e($email) }}"
                                data-status="{{ e($statusKerja) }}"
                                data-branch-id="{{ e($employee->cabang_id ?? '') }}"
                                data-branch="{{ e($namaCabang) }}"
                                data-description="{{ e($deskripsi) }}">
                                <td class="text-[13px] font-extrabold">
                                    {{ $employee->pegawai_id }}
                                </td>

                                <td class="text-[13px] font-extrabold">
                                    {{ $employee->nama ?? '-' }}
                                </td>

                                <td class="text-[12px] font-bold px-2">
                                    {{ $namaCabang }}
                                </td>

                                <td>
                                    @if($statusKerja === 'aktif')
                                        <span class="inline-flex bg-[#A8BD8C] text-white px-[13px] py-[5px] rounded-[8px] text-[12px] font-extrabold">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex bg-[#E8C6CC] text-[#8A4357] px-[12px] py-[5px] rounded-[8px] text-[12px] font-extrabold">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <button type="button"
                                            onclick="openDetailEmployee(this)"
                                            class="bg-[#E8A9B4] hover:bg-[#D995A1] text-white rounded-[8px] px-[13px] py-[6px] text-[12px] font-extrabold transition">
                                        Detail Pegawai
                                    </button>
                                </td>

                                <td>
                                    <div class="flex items-center justify-center gap-[8px]">
                                        <button type="button"
                                                onclick="openEditEmployee(this)"
                                                class="w-[26px] h-[26px] rounded-[7px] bg-[#F6DFA8] flex items-center justify-center hover:opacity-80 transition">
                                            <svg class="w-[15px] h-[15px]" viewBox="0 0 24 24" fill="none">
                                                <path d="M4 20H8L19 9L15 5L4 16V20Z" stroke="#6B4D46" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>

                                        <button type="button"
                                                onclick="openDeleteEmployeeModal(this)"
                                                class="w-[26px] h-[26px] rounded-[7px] bg-[#B85C6A] flex items-center justify-center hover:opacity-80 transition">
                                            <svg class="w-[15px] h-[15px]" viewBox="0 0 24 24" fill="none">
                                                <path d="M5 7H19M10 11V17M14 11V17M8 7L9 4H15L16 7M7 7L8 20H16L17 7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-sm font-semibold text-[#8B7777]">
                                    Belum ada data pegawai dengan role pegawai di database.
                                </td>
                            </tr>
                        @endforelse

                            <tr id="employeeNoSearchResult" class="hidden">
                                <td colspan="6" class="py-10 text-sm font-semibold text-[#8B7777]">
                                    Nama pegawai yang dicari tidak ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </section>

    </main>
</div>

<div id="employeeModal" class="hidden fixed inset-0 z-[999] modal-bg items-center justify-center px-6">
    <form id="employeeForm"
          method="POST"
          action="{{ route('admin.pegawai.store') }}"
          class="w-full max-w-[560px] bg-white rounded-[18px] shadow-2xl p-[24px]">
        @csrf

        <input type="hidden" name="_method" id="employeeFormMethod" value="PUT" disabled>

        <div class="flex items-center justify-between mb-[18px]">
            <h2 id="employeeModalTitle" class="text-[24px] font-extrabold text-[#3F3838]">
                Tambah Pegawai
            </h2>

            <button type="button"
                    onclick="closeEmployeeModal()"
                    class="w-[32px] h-[32px] bg-[#3F372E] text-white rounded-full text-[22px] leading-none">
                ×
            </button>
        </div>

        <div class="space-y-[13px]">
            <div>
                <label class="text-[13px] font-extrabold">Nama Pegawai</label>
                <input id="employeeNameInput"
                       name="nama"
                       type="text"
                       placeholder="Masukkan nama pegawai"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] outline-none"
                       required>
            </div>

            <div>
                <label class="text-[13px] font-extrabold">Jabatan / Keahlian</label>
                <input id="employeeRoleInput"
                       name="jabatan"
                       type="text"
                       placeholder="Contoh: Hair Specialist"
                       class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] outline-none">
            </div>

            <div class="grid grid-cols-2 gap-[12px]">
                <div>
                    <label class="text-[13px] font-extrabold">No. HP</label>
                    <input id="employeePhoneInput"
                           name="no_hp"
                           type="text"
                           placeholder="0812..."
                           class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] outline-none">
                </div>

                <div>
                    <label class="text-[13px] font-extrabold">Status</label>
                    <select id="employeeStatusInput"
                            name="status_kerja"
                            class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] outline-none">
                        <option value="aktif">Aktif</option>
                        <option value="cuti">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-[12px]">
                <div>
                    <label class="text-[13px] font-extrabold">Email</label>
                    <input id="employeeEmailInput"
                           name="email"
                           type="email"
                           placeholder="pegawai@salon.com"
                           class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] outline-none"
                           required>
                </div>

                <div>
                    <label class="text-[13px] font-extrabold">Cabang</label>
                    <select id="employeeBranchInput"
                            name="cabang_id"
                            class="mt-[6px] w-full h-[42px] rounded-[8px] bg-[#FFF0F2] px-[13px] outline-none"
                            required>
                        <option value="">Pilih Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->cabang_id }}">
                                {{ $branch->label ?? $branch->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="text-[13px] font-extrabold">Deskripsi</label>
                <textarea id="employeeDescriptionInput"
                          name="deskripsi"
                          placeholder="Deskripsi singkat pegawai"
                          class="mt-[6px] w-full min-h-[82px] rounded-[8px] bg-[#FFF0F2] px-[13px] py-[10px] outline-none resize-none"></textarea>
            </div>
        </div>

        <button id="employeeSubmitBtn"
                type="submit"
                class="mt-[22px] w-full h-[44px] rounded-[8px] bg-[#E8A9B4] hover:bg-[#D995A1] text-white font-extrabold transition">
            Simpan Pegawai
        </button>
    </form>
</div>

<div id="deleteEmployeeModal" class="hidden fixed inset-0 z-[1000] modal-bg items-center justify-center px-6">
    <div class="w-full max-w-[430px] bg-white rounded-[18px] shadow-2xl p-[26px] text-center">
        <div class="mx-auto w-[70px] h-[70px] rounded-full bg-[#FFF0F2] flex items-center justify-center text-[#B85C6A] text-[38px] font-black mb-[16px]">
            !
        </div>

        <h2 class="text-[23px] font-extrabold text-[#3F3838] leading-tight">
            Yakin untuk menghapus pegawai ini?
        </h2>

        <p class="mt-[12px] text-[14px] text-[#6F5E5E] leading-relaxed">
            Pegawai <span id="deleteEmployeeName" class="font-extrabold text-[#3F3838]">ini</span> akan dinonaktifkan dan dihapus dari daftar pegawai aktif.
        </p>

        <form id="deleteEmployeeForm" method="POST" action="#" class="mt-[24px] flex items-center justify-center gap-[12px]">
            @csrf
            @method('DELETE')

            <button type="button"
                    onclick="closeDeleteEmployeeModal()"
                    class="h-[40px] min-w-[120px] rounded-[8px] bg-[#EFE4E4] text-[#4B4242] font-extrabold">
                Batal
            </button>

            <button type="submit"
                    class="h-[40px] min-w-[120px] rounded-[8px] bg-[#B85C6A] text-white font-extrabold">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

<script>
    function toggleDropdown(id) {
        const target = document.getElementById(id);
        const dropdowns = ['branchDropdown'];

        dropdowns.forEach((dropdownId) => {
            const dropdown = document.getElementById(dropdownId);

            if (dropdown && dropdownId !== id) {
                dropdown.classList.add('hidden');
            }
        });

        if (target) {
            target.classList.toggle('hidden');
        }
    }

    function setFormReadonly(isReadonly) {
        const fields = [
            'employeeNameInput',
            'employeeRoleInput',
            'employeePhoneInput',
            'employeeEmailInput',
            'employeeStatusInput',
            'employeeBranchInput',
            'employeeDescriptionInput'
        ];

        fields.forEach((id) => {
            const field = document.getElementById(id);

            if (field) {
                field.disabled = isReadonly;
            }
        });

        document.getElementById('employeeSubmitBtn').classList.toggle('hidden', isReadonly);
    }

    function resetEmployeeForm() {
        const form = document.getElementById('employeeForm');
        const methodInput = document.getElementById('employeeFormMethod');

        form.reset();
        form.action = "{{ route('admin.pegawai.store') }}";

        methodInput.value = 'PUT';
        methodInput.disabled = true;

        setFormReadonly(false);
    }

    function openCreateEmployeeModal() {
        resetEmployeeForm();

        document.getElementById('employeeModalTitle').textContent = 'Tambah Pegawai';
        document.getElementById('employeeSubmitBtn').textContent = 'Simpan Pegawai';

        document.getElementById('employeeModal').classList.remove('hidden');
        document.getElementById('employeeModal').classList.add('flex');
    }

    function fillEmployeeFormFromRow(row) {
        document.getElementById('employeeNameInput').value = row.dataset.name || '';
        document.getElementById('employeeRoleInput').value = row.dataset.role || '';
        document.getElementById('employeePhoneInput').value = row.dataset.phone || '';
        document.getElementById('employeeEmailInput').value = row.dataset.email || '';
        document.getElementById('employeeStatusInput').value = row.dataset.status === 'aktif' ? 'aktif' : 'cuti';
        document.getElementById('employeeBranchInput').value = row.dataset.branchId || '';
        document.getElementById('employeeDescriptionInput').value = row.dataset.description || '';
    }

    function openEditEmployee(button) {
        const row = button.closest('tr');
        const employeeId = row.dataset.id;
        const methodInput = document.getElementById('employeeFormMethod');

        resetEmployeeForm();
        fillEmployeeFormFromRow(row);
        setFormReadonly(false);

        document.getElementById('employeeModalTitle').textContent = 'Edit Pegawai';
        document.getElementById('employeeSubmitBtn').textContent = 'Update Pegawai';

        document.getElementById('employeeForm').action = "{{ url('/admin/pegawai') }}/" + employeeId;

        methodInput.value = 'PUT';
        methodInput.disabled = false;

        document.getElementById('employeeModal').classList.remove('hidden');
        document.getElementById('employeeModal').classList.add('flex');
    }

    function openDetailEmployee(button) {
        const row = button.closest('tr');

        resetEmployeeForm();
        fillEmployeeFormFromRow(row);
        setFormReadonly(true);

        document.getElementById('employeeModalTitle').textContent = 'Detail Pegawai';

        document.getElementById('employeeModal').classList.remove('hidden');
        document.getElementById('employeeModal').classList.add('flex');
    }

    function closeEmployeeModal() {
        document.getElementById('employeeModal').classList.add('hidden');
        document.getElementById('employeeModal').classList.remove('flex');
        setFormReadonly(false);
    }

    function openDeleteEmployeeModal(button) {
        const row = button.closest('tr');
        const employeeId = row.dataset.id;
        const employeeName = row.dataset.name || 'pegawai ini';

        document.getElementById('deleteEmployeeName').textContent = employeeName;
        document.getElementById('deleteEmployeeForm').action = "{{ url('/admin/pegawai') }}/" + employeeId;

        document.getElementById('deleteEmployeeModal').classList.remove('hidden');
        document.getElementById('deleteEmployeeModal').classList.add('flex');
    }

    function closeDeleteEmployeeModal() {
        document.getElementById('deleteEmployeeModal').classList.add('hidden');
        document.getElementById('deleteEmployeeModal').classList.remove('flex');
    }

    function searchEmployeesByName() {
        const input = document.getElementById('employeeSearchInput');
        const keyword = (input?.value || '').toLowerCase().trim();
        const rows = document.querySelectorAll('.employee-row');
        const noResultRow = document.getElementById('employeeNoSearchResult');
        let visibleCount = 0;

        rows.forEach((row) => {
            const name = (row.dataset.name || '').toLowerCase();
            const isMatch = name.includes(keyword);

            row.classList.toggle('hidden', !isMatch);

            if (isMatch) {
                visibleCount++;
            }
        });

        if (noResultRow) {
            noResultRow.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
        }
    }

    function resetEmployeeSearch() {
        const input = document.getElementById('employeeSearchInput');

        if (input) {
            input.value = '';
        }

        searchEmployeesByName();
    }

    document.addEventListener('click', function(event) {
        const insideDropdown = event.target.closest('#branchDropdown');
        const dropdownButton = event.target.closest('button[onclick^="toggleDropdown"]');
        const employeeModal = document.getElementById('employeeModal');
        const deleteEmployeeModal = document.getElementById('deleteEmployeeModal');

        if (!insideDropdown && !dropdownButton) {
            document.getElementById('branchDropdown')?.classList.add('hidden');
        }

        if (event.target === employeeModal) {
            closeEmployeeModal();
        }

        if (event.target === deleteEmployeeModal) {
            closeDeleteEmployeeModal();
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.bg-green-100, .bg-red-100').forEach(function (el) {
            setTimeout(function () {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(function () { el.remove(); }, 500);
            }, 3000);
        });
    });
</script>

</body>
</html>
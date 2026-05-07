@extends('owner.app')

@section('content')

<div x-data="{ openModal:false }"
     class="pt-28 px-10 bg-[#f4e6e6] min-h-screen">

    {{-- BACK --}}
    <a href="/employee"
       class="inline-flex items-center gap-2 bg-[#c98f8f] text-white px-6 py-3 rounded-full text-sm mb-10 hover:opacity-90 transition">
        ← Back
    </a>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">

        @foreach([
            ['title'=>'Total Employees','val'=>32],
            ['title'=>'Active Today','val'=>30],
            ['title'=>'Cabang Laudendang','val'=>32],
            ['title'=>'Cabang Tuasan','val'=>32],
        ] as $item)

        <div class="bg-[#f0eded] rounded-[30px] px-8 py-6">
            <p class="text-lg font-semibold text-[#3e382d]">{{ $item['title'] }}</p>
            <h2 class="text-3xl font-bold text-[#e11d48] mt-2">{{ $item['val'] }}</h2>
        </div>

        @endforeach

    </div>

    {{-- MAIN CARD --}}
    <div class="bg-[#efe3e3] rounded-[35px] px-10 py-8">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-4xl font-bold text-[#3e382d]">
                Staff Directory
            </h2>

            <button class="bg-[#f45b69] text-white px-5 py-2 rounded-full text-sm">
                Seluruh Cabang ▼
            </button>
        </div>

        {{-- SEARCH + ADD --}}
        <div class="flex justify-between items-center mb-6">

            {{-- SEARCH --}}
            <div class="relative w-full max-w-xs">
                <input 
                    type="text" 
                    placeholder="Search..." 
                    class="w-full pl-6 pr-12 py-3 bg-white border-2 border-[#E99688] rounded-2xl text-[#9CA3AF] placeholder-[#9CA3AF] outline-none transition-all focus:ring-2 focus:ring-[#f5c6be]"
                >
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#E99688]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            {{-- ADD BUTTON --}}
            <button @click="openModal = true"
                    class="bg-[#e7bcbc] text-[#b91c1c] px-5 py-2 rounded-full text-sm
                           hover:bg-[#f45b69] hover:text-white transition">
                + Add Employee
            </button>

        </div>

        {{-- TABLE --}}
        <table class="w-full text-sm">

            <thead class="text-[#9f1d2c] text-base">
                <tr>
                    <th class="py-4 text-left">No</th>
                    <th>Specialist</th>
                    <th>Role</th>
                    <th>Cabang</th>
                    <th>Status</th>
                    <th>Clients</th>
                    <th>Since</th>
                    <th>Rating</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody class="text-[#3e382d]">

                @foreach(range(1,5) as $i)

                <tr class="border-t hover:bg-white/30 transition">

                    <td class="py-4">{{ $i }}</td>

                    <td class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
                        Putri Amelia
                    </td>

                    <td>Specialist</td>
                    <td>Tuasan</td>

                    <td class="text-green-500">Active</td>

                    <td>200</td>
                    <td>January 2020</td>
                    <td>4.9</td>

                    <td class="flex gap-3">
                        <button class="hover:scale-110">✏️</button>
                        <button class="hover:scale-110">🗑️</button>
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    {{-- ================= MODAL ================= --}}
    <div x-show="openModal"
         x-cloak
         x-transition.opacity
         class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        {{-- BOX --}}
        <div @click.outside="openModal=false"
             class="bg-[#f3eded] w-full max-w-xl rounded-[40px] px-10 py-10">

            {{-- TITLE --}}
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold text-[#3e382d]">
                    Add New Team
                </h2>
                <p class="text-gray-500 mt-2">
                    Add a new specialist or admin to the team.
                </p>
            </div>

            {{-- FORM --}}
            <form class="space-y-6">

                <div>
                    <label class="text-xl font-semibold">Full Name</label>
                    <input type="text"
                           class="w-full mt-2 bg-[#e7cfcf] px-5 py-3 rounded-xl">
                </div>

                <div>
                    <label class="text-xl font-semibold">Phone Number</label>
                    <input type="text"
                           class="w-full mt-2 bg-[#e7cfcf] px-5 py-3 rounded-xl">
                </div>

                <div class="grid grid-cols-2 gap-6">

                    <div>
                        <label class="text-xl font-semibold">Role</label>
                        <select class="w-full mt-2 bg-[#e7cfcf] px-5 py-3 rounded-xl">
                            <option>Specialist</option>
                            <option>Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xl font-semibold">Branch</label>
                        <select class="w-full mt-2 bg-[#e7cfcf] px-5 py-3 rounded-xl">
                            <option>Tuasan</option>
                            <option>Laudendang</option>
                        </select>
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-4 mt-6">

                    <button type="button"
                            @click="openModal=false"
                            class="bg-gray-300 px-6 py-2 rounded-full">
                        Cancel
                    </button>

                    <button type="submit"
                            class="bg-[#ea868f] text-white px-6 py-2 rounded-full">
                        Add Team
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
<script>
// =========================
// SEARCH ONLY (SEPARATE)
// =========================
const searchInput = document.getElementById('searchInput');

searchInput.addEventListener('input', function () {
    const query = this.value.toLowerCase();

    const items = document.querySelectorAll('.gallery-item');

    items.forEach(item => {
        const title = item.querySelector('h3').innerText.toLowerCase();
        const desc = item.querySelector('p').innerText.toLowerCase();

        if (title.includes(query) || desc.includes(query)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>
@endsection
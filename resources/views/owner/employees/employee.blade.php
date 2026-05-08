@extends('owner.app')

@section('content')

<div class="pt-24 px-8 bg-[#f6eaea] min-h-screen">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-5xl font-bold text-[#2d2a26]">Team Performance</h1>
            <p class="text-gray-500 mt-2">
                Overview of specialist efficiency across your luxury network.
            </p>
        </div>

        <div class="flex gap-3">
            <button class="bg-[#f45b69] text-white px-4 py-2 rounded-full text-sm">
                Seluruh Cabang ▼
            </button>
            <button class="bg-[#f8cdd0] text-[#2d2a26] px-4 py-2 rounded-full text-sm">
                Mei 2026 ▼
            </button>
        </div>
    </div>

    {{-- TOP PERFORMERS --}}
    <h2 class="text-2xl font-semibold text-[#2d2a26] mb-4">Top Performers</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        @foreach([
            ['branch'=>'Cabang Tuasan','name'=>'Amelia Putri','total'=>100],
            ['branch'=>'Cabang Laudendang','name'=>'Amelia Putri','total'=>96],
            ['branch'=>'Cabang Tuasan','name'=>'Amelia Putri','total'=>97],
        ] as $item)

        <div class="bg-white rounded-3xl p-6 flex items-center gap-6 shadow-md">

            {{-- FOTO --}}
            <div class="w-20 h-24 bg-[#f45b69] rounded-2xl"></div>

            <div>
                <span class="bg-[#f8cdd0] text-sm px-3 py-1 rounded-full">
                    {{ $item['branch'] }}
                </span>

                <h3 class="text-lg font-semibold mt-2">
                    {{ $item['name'] }}
                </h3>

                <p class="mt-1 text-lg font-bold text-red-500">
                    {{ $item['total'] }}
                    <span class="text-gray-500 text-sm font-normal">
                        klien bln ini
                    </span>
                </p>
            </div>

        </div>

        @endforeach

    </div>

    {{-- TABLE --}}
    <div class="bg-[#eadede] p-6 rounded-3xl">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-[#2d2a26]">
                Specialist Efficiency
            </h2>
            <button 
    class="text-sm text-[#b04a4a] btn-edit"
    data-url="{{ route('employee.edit') }}">
    Edit ✏️
</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="text-left text-[#b04a4a]">
                    <tr>
                        <th class="py-3">No</th>
                        <th>Specialist</th>
                        <th>Cabang</th>
                        <th>Clients</th>
                        <th>Since</th>
                        <th>Rating</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">

                    @foreach([
                        ['name'=>'Putri Amelia','cabang'=>'Tuasan','clients'=>200,'since'=>'January 2020','rating'=>4.9],
                        ['name'=>'Putri Amelia','cabang'=>'Tuasan','clients'=>200,'since'=>'December 2021','rating'=>4.8],
                        ['name'=>'Inai','cabang'=>'Laudendang','clients'=>198,'since'=>'November 2025','rating'=>4.9],
                        ['name'=>'Inai','cabang'=>'Laudendang','clients'=>197,'since'=>'September 2024','rating'=>4.9],
                        ['name'=>'Inai','cabang'=>'Tuasan','clients'=>197,'since'=>'February 2020','rating'=>4.8],
                    ] as $i => $row)

                    <tr class="border-t">
                        <td class="py-3">{{ $i+1 }}</td>

                        <td class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-gray-300 rounded-full"></div>
                            {{ $row['name'] }}
                        </td>

                        <td>{{ $row['cabang'] }}</td>
                        <td>{{ $row['clients'] }}</td>
                        <td>{{ $row['since'] }}</td>
                        <td>{{ $row['rating'] }}</td>
                    </tr>

                    @endforeach

                </tbody>

            </table>
        </div>

    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.btn-edit');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
@endsection
<div
    x-show="openModal"
    x-cloak
    x-transition.opacity
    class="
        fixed inset-0
        bg-black/40
        flex items-center justify-center
        z-50
        px-4
    "
>

    {{-- BOX --}}
    <div
        @click.outside="openModal = false"
        class="
            bg-[#f6eeee]
            w-full max-w-2xl
            rounded-[32px]
            px-8 py-8
            shadow-2xl
        "
    >

        {{-- HEADER --}}
        <div class="text-center mb-7">

            <h2 class="
                text-5xl
                font-bold
                text-[#3e382d]
                tracking-tight
            ">
                Add New Employee
            </h2>

            <p class="text-gray-500 mt-2 text-sm">
                Add a new specialist or admin to the team.
            </p>

        </div>

        @if ($errors->any())

        <div class="
            mb-5
            bg-red-100
            border border-red-200
            text-red-600
            px-5 py-4
            rounded-2xl
        ">

            <ul class="space-y-1 text-sm">

                @foreach ($errors->all() as $error)

                <li>
                    • {{ $error }}
                </li>

                @endforeach

            </ul>

        </div>

        @endif

        {{-- FORM --}}
        <form
            x-data="{ loading:false }"
            action="{{ route('owner.employee.store') }}"
            method="POST"
            class="space-y-5"
            @submit="loading = true"
        >
            @csrf

            {{-- FULL NAME --}}
            <div>

                <label 
                for="nama"
                class="
                    text-base
                    font-semibold
                    text-[#2d2a26]
                ">
                    Full Name
                </label>

                <input
                    id="nama"
                    type="text"
                    name="nama"
                    value="{{ old('nama') }}"
                    required
                    class="
                        w-full mt-2
                        bg-[#e8d2d2]
                        border border-transparent
                        focus:border-[#f45b69]
                        focus:ring-2 focus:ring-[#ffd5d8]
                        outline-none
                        px-5 py-3
                        rounded-2xl
                        transition
                    "
                >

            </div>

            {{-- EMAIL --}}
            <div>

                <label 
                for="email"
                class="
                    text-base
                    font-semibold
                    text-[#2d2a26]
                ">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="
                        w-full mt-2
                        bg-[#e8d2d2]
                        border border-transparent
                        focus:border-[#f45b69]
                        focus:ring-2 focus:ring-[#ffd5d8]
                        outline-none
                        px-5 py-3
                        rounded-2xl
                        transition
                    "
                >

            </div>

            {{-- PHONE --}}
            <div>

                <label 
                for="no_hp"
                class="
                    text-base
                    font-semibold
                    text-[#2d2a26]
                ">
                    Phone Number
                </label>

                <input
                    id="no_hp"
                    type="text"
                    name="no_hp"
                    value="{{ old('no_hp') }}"
                    required
                    class="
                        w-full mt-2
                        bg-[#e8d2d2]
                        border border-transparent
                        focus:border-[#f45b69]
                        focus:ring-2 focus:ring-[#ffd5d8]
                        outline-none
                        px-5 py-3
                        rounded-2xl
                        transition
                    "
                >

            </div>

            {{-- ROLE + BRANCH --}}
            <div class="grid grid-cols-2 gap-5">

                {{-- ROLE --}}
                <div>

                    <label 
                    for="role"
                    class="
                        text-base
                        font-semibold
                        text-[#2d2a26]
                    ">
                        Role
                    </label>

                    <select
                        id="role"
                        name="role"
                        required
                        class="
                            w-full mt-2
                            bg-[#e8d2d2]
                            border border-transparent
                            focus:border-[#f45b69]
                            focus:ring-2 focus:ring-[#ffd5d8]
                            outline-none
                            px-5 py-3
                            rounded-2xl
                            transition
                        "
                    >
                        <option value="pegawai" {{ old('role') == 'pegawai' ? 'selected' : '' }}>
                            Pegawai
                        </option>

                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                    </select>

                </div>

                {{-- BRANCH --}}
                <div>

                    <label 
                    for="cabang_id"
                    class="
                        text-base
                        font-semibold
                        text-[#2d2a26]
                    ">
                        Branch
                    </label>

                    <select
                        id="cabang_id"
                        name="cabang_id"
                        required
                        class="
                            w-full mt-2
                            bg-[#e8d2d2]
                            border border-transparent
                            focus:border-[#f45b69]
                            focus:ring-2 focus:ring-[#ffd5d8]
                            outline-none
                            px-5 py-3
                            rounded-2xl
                            transition
                        "
                    >

                        @foreach($cabangs as $cabang)

                        <option
                            value="{{ $cabang->cabang_id }}"
                            {{
                                old('cabang_id') == $cabang->cabang_id
                                ? 'selected'
                                : ''
                            }}
                        >
                            {{ $cabang->nama_cabang }}
                        </option>

                        @endforeach

                    </select>

                </div>

            </div>

            {{-- ACTION --}}
            <div class="
                flex justify-end gap-3
                pt-3
            ">

                {{-- CANCEL --}}
                <button
                    type="button"
                    @click="openModal = false"
                    class="
                        px-6 py-2.5
                        rounded-full
                        bg-gray-200
                        text-[#3e382d]
                        text-sm font-medium
                        hover:bg-gray-300
                        transition
                    "
                >
                    Cancel
                </button>

                {{-- SUBMIT --}}
                <button
                    type="submit"
                    :disabled="loading"
                    x-text="loading ? 'Adding...' : 'Add Employee'"
                    class="
                        px-6 py-2.5
                        rounded-full
                        bg-[#ea868f]
                        text-white
                        text-sm font-medium
                        hover:bg-[#f45b69]
                        transition

                        disabled:opacity-60
                        disabled:cursor-not-allowed
                    "
                >
                </button>

            </div>

        </form>

    </div>

</div>
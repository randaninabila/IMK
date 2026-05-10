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
                Add New Team
            </h2>

            <p class="text-gray-500 mt-2 text-sm">
                Add a new specialist or admin to the team.
            </p>

        </div>

        {{-- FORM --}}
        <form class="space-y-5">

            {{-- FULL NAME --}}
            <div>

                <label class="
                    text-base
                    font-semibold
                    text-[#2d2a26]
                ">
                    Full Name
                </label>

                <input
                    type="text"
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

                <label class="
                    text-base
                    font-semibold
                    text-[#2d2a26]
                ">
                    Phone Number
                </label>

                <input
                    type="text"
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

                    <label class="
                        text-base
                        font-semibold
                        text-[#2d2a26]
                    ">
                        Role
                    </label>

                    <select
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
                        <option value="pegawai">
                            Pegawai
                        </option>

                        <option value="admin">
                            Admin
                        </option>

                    </select>

                </div>

                {{-- BRANCH --}}
                <div>

                    <label class="
                        text-base
                        font-semibold
                        text-[#2d2a26]
                    ">
                        Branch
                    </label>

                    <select
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

                        <option value="{{ $cabang->cabang_id }}">
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
                    class="
                        px-6 py-2.5
                        rounded-full
                        bg-[#ea868f]
                        text-white
                        text-sm font-medium
                        hover:bg-[#f45b69]
                        transition
                    "
                >
                    Add Team
                </button>

            </div>

        </form>

    </div>

</div>
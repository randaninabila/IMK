@extends('owner.app')

@section('content')
<div class="pt-24 px-8 pb-8 bg-[#f6eaea] min-h-screen">
    <a href="{{ route('owner.employee.edit') }}" class="inline-flex items-center gap-2 bg-white border border-[#f1dede] px-5 py-2.5 rounded-full text-sm font-medium text-[#b04a4a] shadow-sm hover:bg-pink-50 transition mb-8">
        ← Back to Employees
    </a>

    <div class="bg-white rounded-[35px] px-10 py-12 max-w-2xl mx-auto">
        <h1 class="text-4xl font-bold text-[#2d2a26] text-center mb-8">Edit Employee</h1>

        @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-200 text-red-600 px-5 py-4 rounded-2xl">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('owner.employee.update', $pegawai->pegawai_id) }}" method="POST" class="space-y-6">
            @csrf @method('PATCH')

            <div>
                <label class="block text-base font-semibold text-[#2d2a26] mb-2">Full Name</label>
                <input type="text" name="nama" value="{{ old('nama', $pegawai->nama) }}" required
                    class="w-full bg-[#e8d2d2] border border-transparent focus:border-[#f45b69] focus:ring-2 focus:ring-[#ffd5d8] outline-none px-5 py-3 rounded-2xl transition">
            </div>

            <div>
                <label class="block text-base font-semibold text-[#2d2a26] mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $pegawai->email) }}" required
                    class="w-full bg-[#e8d2d2] border border-transparent focus:border-[#f45b69] focus:ring-2 focus:ring-[#ffd5d8] outline-none px-5 py-3 rounded-2xl transition">
            </div>

            <div>
                <label class="block text-base font-semibold text-[#2d2a26] mb-2">Phone Number</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}" required
                    class="w-full bg-[#e8d2d2] border border-transparent focus:border-[#f45b69] focus:ring-2 focus:ring-[#ffd5d8] outline-none px-5 py-3 rounded-2xl transition">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-base font-semibold text-[#2d2a26] mb-2">Role</label>
                    <select name="role" required class="w-full bg-[#e8d2d2] border border-transparent focus:border-[#f45b69] focus:ring-2 focus:ring-[#ffd5d8] outline-none px-5 py-3 rounded-2xl transition">
                        <option value="pegawai" {{ old('role', $pegawai->role) == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                        <option value="admin" {{ old('role', $pegawai->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div>
                    <label class="block text-base font-semibold text-[#2d2a26] mb-2">New Password (optional)</label>
                    <input type="password" name="password" placeholder="Leave empty to keep current"
                        class="w-full bg-[#e8d2d2] border border-transparent focus:border-[#f45b69] focus:ring-2 focus:ring-[#ffd5d8] outline-none px-5 py-3 rounded-2xl transition">
                    <input type="password" name="password_confirmation" placeholder="Confirm password"
                        class="w-full mt-2 bg-[#e8d2d2] border border-transparent focus:border-[#f45b69] focus:ring-2 focus:ring-[#ffd5d8] outline-none px-5 py-3 rounded-2xl transition">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6">
                <a href="{{ route('owner.employee.edit') }}" class="px-8 py-3 rounded-full bg-gray-200 text-[#3e382d] text-sm font-medium hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 rounded-full bg-[#ea868f] text-white text-sm font-medium hover:bg-[#f45b69] transition">
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
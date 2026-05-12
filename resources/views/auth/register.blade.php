<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-pink-100">

    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md">

        <h1 class="text-3xl font-bold mb-6 text-center">
            Register
        </h1>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-600 p-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf

            {{-- NAME --}}
            <div class="mb-4">
                <label>Name</label>
                <input type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border p-3 rounded-lg">
            </div>

            {{-- EMAIL --}}
            <div class="mb-4">
                <label>Email</label>
                <input type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border p-3 rounded-lg">
            </div>

            {{-- PHONE --}}
            <div class="mb-4">
                <label>Phone</label>
                <input type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full border p-3 rounded-lg">
            </div>

            {{-- PASSWORD --}}
            <div class="mb-4">
                <label>Password</label>
                <input type="password"
                    name="password"
                    class="w-full border p-3 rounded-lg">
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div class="mb-6">
                <label>Confirm Password</label>
                <input type="password"
                    name="password_confirmation"
                    class="w-full border p-3 rounded-lg">
            </div>

            <button type="submit"
                class="w-full bg-pink-500 text-white py-3 rounded-lg">
                Register
            </button>

        </form>

    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PISBOOK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-white shadow-md py-4 px-6 mb-6">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="/posts" class="text-2xl font-bold text-indigo-600 hover:text-indigo-800"> PISBOOK</a>

            <div class="flex items-center gap-4">
                <span class="text-gray-800 font-semibold">
                    Hi, {{ Auth::user()->name }} 👋
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4">
        @yield('content')
    </main>

</body>
</html>

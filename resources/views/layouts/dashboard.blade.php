<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside class="w-64 bg-purple-700 text-white flex flex-col">
        <div class="p-6 text-center text-2xl font-bold border-b border-purple-800">
            Tuition Dashboard
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="/admin_dashboard" class="block py-2 px-4 rounded hover:bg-purple-800">Home</a>
            <a href="#" class="block py-2 px-4 rounded hover:bg-purple-800">Students</a>
            <a href="#" class="block py-2 px-4 rounded hover:bg-purple-800">Classes</a>
            <a href="#" class="block py-2 px-4 rounded hover:bg-purple-800">Schedule</a>
            <a href="#" class="block py-2 px-4 rounded hover:bg-purple-800">Payments</a>
            <a href="/logout" class="block py-2 px-4 rounded hover:bg-purple-800 mt-auto bg-purple-900 text-center">Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-auto">
        <h1 class="text-3xl font-bold text-purple-700 mb-4">@yield('page-title', 'Dashboard')</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @yield('content')
        </div>
    </main>

</body>
</html>

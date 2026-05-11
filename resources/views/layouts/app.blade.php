<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Commerce</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- 🔥 Alpine (المكان الصح) -->
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
</head>

<body class="bg-gray-100">

    <!-- 🔝 Navbar -->
    @include('partials.navbar')

    <!-- 📦 Content -->
    <main class="py-10">
        {{ $slot }}
    </main>

    <!-- 🔻 Footer -->
    @include('partials.footer')

</body>
</html>
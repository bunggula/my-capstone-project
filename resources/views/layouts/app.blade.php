<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ABC System')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ✅ Alpine.js (only one!) -->
    <script src="https://unpkg.com/alpinejs@3.x.x" defer></script>

    <!-- ✅ Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- ✅ Lightbox2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">

    <script>
        lightbox.option({
            'alwaysShowNavOnTouchDevices': true,
            'wrapAround': true,
            'fadeDuration': 300,
            'resizeDuration': 300
        });

        document.addEventListener('DOMContentLoaded', function () {
            const observer = new MutationObserver(() => {
                const closeBtn = document.querySelector('.lb-close');
                if (closeBtn) {
                    closeBtn.style.display = 'block';
                    closeBtn.style.opacity = '1';
                    closeBtn.style.zIndex = '1001';
                    closeBtn.style.position = 'fixed';
                    closeBtn.style.top = '10px';
                    closeBtn.style.right = '10px';
                    closeBtn.style.fontSize = '30px';
                    closeBtn.style.color = 'white';
                }
            });

            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
</head>


<body class="min-h-screen w-screen bg-blue-900 overflow-auto">
    <main class="w-full">
        @yield('content')
    </main>

<!-- ✅ Stack scripts (for page-specific scripts like CKEditor) -->
@stack('scripts')
    <!-- ✅ SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ✅ Lightbox2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/alpinejs" defer></script>

</body>
</html>

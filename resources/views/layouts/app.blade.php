<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- External CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/theme.css', 'resources/js/app.js'])

    {{-- External JS (jika tidak di-bundle melalui Vite) --}}
    {{-- Jika Bootstrap JS dan AlpineJS diimpor dalam resources/js/app.js, baris berikut bisa dihapus --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body x-data="{ darkMode: localStorage.getItem('theme') === 'dark' ? true : false }"
      x-init="$watch('darkMode', value => {
          if (value) {
              document.body.classList.add('dark-mode');
              localStorage.setItem('theme', 'dark');
          } else {
              document.body.classList.remove('dark-mode');
              localStorage.setItem('theme', 'light');
          }
      });
      // Apply initial theme on load
      if (localStorage.getItem('theme') === 'dark') {
          document.body.classList.add('dark-mode');
      } else {
          document.body.classList.remove('dark-mode');
      }"
      class="font-sans"> {{-- font-sans dari Tailwind, mungkin Anda ingin class lain jika tidak pakai Tailwind --}}

    <div class="main-content">
        @include('layouts.navigation')

        @isset($header)
            <header class="header-custom py-4">
                <div class="container">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Modal Konfirmasi Global --}}
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content"> {{-- Warna akan diatur oleh JS dan CSS Variables --}}
                    <div class="modal-header bg-primary text-white"> {{-- Kelas bg-primary text-white sbg fallback light mode, akan di-override JS utk dark mode --}}
                        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Aksi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="confirmationMessage"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmActionButton">Konfirmasi</button>
                    </div>
                </div>
            </div>
        </div>

        <main class="container py-4">
            @yield('content')
        </main>
    </div>

    {{-- Tidak ada blok <script> kustom panjang di sini lagi --}}
</body>
</html>
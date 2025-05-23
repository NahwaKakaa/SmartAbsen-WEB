<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | SmartAbsen</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/theme.css', 'resources/css/login.css', 'resources/js/app.js'])

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
      if (localStorage.getItem('theme') === 'dark') {
          document.body.classList.add('dark-mode');
      } else {
          document.body.classList.remove('dark-mode');
      }"
      class="login-page-body">

    <div class="login-card">
        <button class="login-theme-toggle-button" @click="darkMode = !darkMode">
            <span x-show="!darkMode"><i class="bi bi-moon-fill"></i></span>
            <span x-show="darkMode"><i class="bi bi-sun-fill"></i></span>
        </button>

        <div class="login-form-title">
            <i class="bi bi-person-circle"></i> SmartAbsen
        </div>

        @if (session('status'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if ($errors->has('email') && !session('status'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ID Pegawai atau Password salah.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">ID Pegawai</label>
                <input type="text" name="email" id="email" value="{{ old('email') }}" required autofocus class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan ID Pegawai">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" required class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password">
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn login-btn-primary">Masuk</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container px-4 px-lg-8">
        <a href="{{ session('role') === 'admin' ? route('admin.dashboard') : route('pegawai.dashboard') }}" class="navbar-brand-custom">
            SmartAbsen
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- ms-auto pada ul.navbar-nav sudah cukup untuk meratakan ke kanan pada layar besar --}}
        {{-- justify-content-end pada navbar-collapse juga membantu untuk layar besar --}}
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a href="{{ session('role') === 'admin' ? route('admin.dashboard') : route('pegawai.dashboard') }}"
                       class="nav-link nav-link-custom {{ request()->routeIs(session('role') === 'admin' ? 'admin.dashboard' : 'pegawai.dashboard') ? 'active' : '' }}">
                        {{ __('Dashboard') }}
                    </a>
                </li>

                @if (session('role') === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.pegawai.index') }}"
                           class="nav-link nav-link-custom {{ request()->routeIs('admin.pegawai.index') ? 'active' : '' }}">
                            {{ __('Manage Pegawai') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.laporan.index') }}"
                           class="nav-link nav-link-custom {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">
                            {{ __('Absensi Pegawai') }}
                        </a>
                    </li>
                @elseif (session('role') === 'pegawai')
                    <li class="nav-item">
                        <a href="{{ route('pegawai.absensi.index') }}"
                           class="nav-link nav-link-custom {{ request()->routeIs('pegawai.absensi.index') ? 'active' : '' }}">
                            {{ __('Absensi Saya') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pegawai.absensi.history') }}"
                           class="nav-link nav-link-custom {{ request()->routeIs('pegawai.absensi.history') ? 'active' : '' }}">
                            {{ __('Riwayat Absensi') }}
                        </a>
                    </li>
                @endif

                <li class="nav-item dropdown">
                    <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="navbarDropdownProfile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::check() ? (session('role') === 'admin' ? Auth::guard('admin')->user()->name : Auth::guard('pegawai')->user()->name) : 'Pengguna' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownProfile">
                        @if(session('role') === 'admin')
                            <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}"><i class="bi bi-person-circle"></i> Profil</a></li>
                        @elseif(session('role') === 'pegawai')
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-circle"></i> Profil</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>

                <li class="nav-item ms-lg-3">
                    <button class="theme-toggle-button" @click="darkMode = !darkMode">
                        <span x-show="!darkMode"><i class="bi bi-moon-fill"></i></span>
                        <span x-show="darkMode"><i class="bi bi-sun-fill"></i></span>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

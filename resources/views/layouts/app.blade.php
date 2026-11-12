<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIMS') ? Student Information Management</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    @stack('styles')
    <script>
        document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    </script>
</head>
<body class="bg-body-tertiary">
<style>
    .app-sidebar {
        --bs-offcanvas-width: 17rem;
    }

    .app-sidebar .nav-link:not(.active):hover {
        background-color: var(--bs-tertiary-bg);
    }
</style>
<nav class="navbar bg-body border-bottom sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ auth()->check() ? (auth()->user()->isStudent() ? route('student.dashboard') : route('dashboard')) : route('login') }}">SIMS</a>
        @auth
            <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar" aria-label="Open navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="ms-auto d-flex align-items-center gap-2">
                <span class="small text-body-secondary d-none d-sm-inline">{{ auth()->user()->name }} <span class="badge text-bg-secondary">{{ auth()->user()->role }}</span></span>
                @unless (auth()->user()->isStudent())
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('password.edit') }}">Change password</a>
                @endunless
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-secondary btn-sm">Logout</button>
                </form>
            </div>
        @endauth
    </div>
</nav>
@auth
    <div class="container-fluid">
        <div class="row">
            <aside class="col-lg-2 p-0">
                <div class="offcanvas-lg offcanvas-start app-sidebar bg-body border-end" tabindex="-1" id="appSidebar" aria-labelledby="appSidebarLabel">
                    <div class="offcanvas-header border-bottom d-lg-none">
                        <h2 class="offcanvas-title h5 mb-0" id="appSidebarLabel">SIMS menu</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Close navigation"></button>
                    </div>
                    <div class="offcanvas-body d-flex flex-column p-3">
                        <nav aria-label="Main navigation">
                            @if (auth()->user()->isStudent())
                                <div class="small text-uppercase text-body-secondary fw-semibold px-3 mb-2">Student portal</div>
                                <ul class="nav nav-pills flex-column gap-1">
                                    <li class="nav-item">
                                        <a class="nav-link rounded {{ request()->routeIs('student.dashboard') ? 'active text-bg-primary fw-semibold' : 'text-body' }}" href="{{ route('student.dashboard') }}" @if (request()->routeIs('student.dashboard')) aria-current="page" @endif>Dashboard</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link rounded {{ request()->routeIs('student.enrollments') ? 'active text-bg-primary fw-semibold' : 'text-body' }}" href="{{ route('student.enrollments') }}" @if (request()->routeIs('student.enrollments')) aria-current="page" @endif>My Enrollments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link rounded {{ request()->routeIs('student.messages*') ? 'active text-bg-primary fw-semibold' : 'text-body' }}" href="{{ route('student.messages') }}" @if (request()->routeIs('student.messages*')) aria-current="page" @endif>Messages to Admin</a>
                                    </li>
                                </ul>
                            @else
                                <div class="small text-uppercase text-body-secondary fw-semibold px-3 mb-2">Workspace</div>
                                <ul class="nav nav-pills flex-column gap-1 mb-4">
                                    <li class="nav-item">
                                        <a class="nav-link rounded {{ request()->routeIs('dashboard') ? 'active text-bg-primary fw-semibold' : 'text-body' }}" href="{{ route('dashboard') }}" @if (request()->routeIs('dashboard')) aria-current="page" @endif>Dashboard</a>
                                    </li>
                                </ul>
                                <div class="small text-uppercase text-body-secondary fw-semibold px-3 mb-2">Academic records</div>
                                <ul class="nav nav-pills flex-column gap-1">
                                    <li class="nav-item">
                                        <a class="nav-link rounded {{ request()->routeIs('students.*') ? 'active text-bg-primary fw-semibold' : 'text-body' }}" href="{{ route('students.index') }}" @if (request()->routeIs('students.*')) aria-current="page" @endif>Students</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link rounded {{ request()->routeIs('subjects.*') ? 'active text-bg-primary fw-semibold' : 'text-body' }}" href="{{ route('subjects.index') }}" @if (request()->routeIs('subjects.*')) aria-current="page" @endif>Classes &amp; subjects</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link rounded {{ request()->routeIs('enrollments.*') ? 'active text-bg-primary fw-semibold' : 'text-body' }}" href="{{ route('enrollments.index') }}" @if (request()->routeIs('enrollments.*')) aria-current="page" @endif>Enrollments</a>
                                    </li>
                                </ul>
                                @if (auth()->user()->isAdmin())
                                    <div class="small text-uppercase text-body-secondary fw-semibold px-3 mt-4 mb-2">Communication</div>
                                    <ul class="nav nav-pills flex-column gap-1">
                                        <li class="nav-item">
                                            <a class="nav-link rounded {{ request()->routeIs('admin.messages.*') ? 'active text-bg-primary fw-semibold' : 'text-body' }}" href="{{ route('admin.messages.index') }}" @if (request()->routeIs('admin.messages.*')) aria-current="page" @endif>Student messages</a>
                                        </li>
                                    </ul>
                                @endif
                            @endif
                            <ul class="nav nav-pills flex-column gap-1 mt-3">
                                <li class="nav-item"><a class="nav-link rounded {{ request()->routeIs('events.*') ? 'active text-bg-primary fw-semibold' : 'text-body' }}" href="{{ route('events.index') }}" @if (request()->routeIs('events.*')) aria-current="page" @endif>School events</a></li>
                                @unless (auth()->user()->isStudent())
                                    <li class="nav-item"><a class="nav-link rounded {{ request()->routeIs('sessions.*') ? 'active text-bg-primary fw-semibold' : 'text-body' }}" href="{{ route('sessions.index') }}" @if (request()->routeIs('sessions.*')) aria-current="page" @endif>Academic sessions</a></li>
                                @endunless
                            </ul>
                        </nav>
                    </div>
                </div>
            </aside>
            <main class="col-lg-10 px-3 px-lg-4 py-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
@else
    <main class="container pb-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </main>
@endauth
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>

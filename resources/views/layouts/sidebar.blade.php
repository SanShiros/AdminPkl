<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr">

<head>
    <title>@yield('title', 'Home') | Datta Able Dashboard Template</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description"
        content="Datta Able is trending dashboard template made using Bootstrap 5 design framework. Datta Able is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies." />
    <meta name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard" />
    <meta name="author" content="CodedThemes" />

    <!-- [Favicon] -->
    <link rel="icon" href="{{ asset('/images/favicon.svg') }}" type="image/x-icon" />

    <!-- [Font] Family -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet" />

    <!-- Tema (dark/light) -->
    <script>
        (function() {
            try {
                var layout = localStorage.getItem('theme') || 'light';
                if (layout !== 'dark' && layout !== 'light') {
                    layout = 'light';
                }
                document.documentElement.setAttribute('data-pc-theme', layout);
            } catch (e) {
                document.documentElement.setAttribute('data-pc-theme', 'light');
            }
        })();
    </script>

    <!-- Icons & CSS -->
    <link rel="stylesheet" href="{{ asset('/fonts/phosphor/duotone/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('/fonts/material.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}" id="main-style-link" />
</head>

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
        <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
            <div
                class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]">
            </div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ Sidebar Menu ] start -->
    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header flex items-center py-4 px-6 h-header-height">
                <a href="{{ url('/') }}" class="b-brand flex items-center gap-3">
                    <!-- Logo -->
                    <img src="{{ asset('images/logo-white.svg') }}" class="img-fluid logo logo-lg" alt="logo" />
                    <img src="{{ asset('images/favicon.svg') }}" class="img-fluid logo logo-sm" alt="logo" />
                </a>
            </div>

            <div class="navbar-content h-[calc(100vh_-_74px)] py-2.5">
                <ul class="pc-navbar">
                    {{-- NAVIGATION --}}
                    <li class="pc-item pc-caption">
                        <label>Navigation</label>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('index') }}" class="pc-link">
                            <span class="pc-micon"><i data-feather="home"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>

                    {{-- MASTER DATA --}}
                    <li class="pc-item pc-caption">
                        <label>Master Data</label>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="{{ route('categories.index') }}" class="pc-link">
                            <span class="pc-micon"><i data-feather="tag"></i></span>
                            <span class="pc-mtext">Kategori</span>
                        </a>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="{{ route('suppliers.index') }}" class="pc-link">
                            <span class="pc-micon"><i data-feather="truck"></i></span>
                            <span class="pc-mtext">Suppliers</span>
                        </a>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="{{ route('products.index') }}" class="pc-link">
                            <span class="pc-micon"><i data-feather="package"></i></span>
                            <span class="pc-mtext">Produk</span>
                        </a>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="{{ route('sales.index') }}" class="pc-link">
                            <span class="pc-micon"><i data-feather="shopping-cart"></i></span>
                            <span class="pc-mtext">Sales</span>
                        </a>
                    </li>

                    {{-- PAGES --}}
                    <li class="pc-item pc-caption">
                        <label>Pages</label>
                        <i data-feather="monitor"></i>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="{{ route('purchase_orders.index') }}" class="pc-link">
                            <span class="pc-micon"><i data-feather="lock"></i></span>
                            <span class="pc-mtext">Purchase Orders</span>
                        </a>
                    </li>
                    <li class="pc-item pc-hasmenu">
                        <a href="{{ route('purchase_order_items.index') }}" class="pc-link">
                            <span class="pc-micon"><i data-feather="user-plus"></i></span>
                            <span class="pc-mtext">Purchase Order Items</span>
                        </a>
                    </li>

                    {{-- OTHER --}}
                    <li class="pc-item pc-caption">
                        <label>Other</label>
                        <i data-feather="sidebar"></i>
                    </li>

                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon"><i data-feather="align-right"></i></span>
                            <span class="pc-mtext">Menu levels</span>
                            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
                        </a>
                        <ul class="pc-submenu">
                            <li class="pc-item"><a class="pc-link" href="#!">Level 2.1</a></li>
                            <li class="pc-item pc-hasmenu">
                                <a href="#!" class="pc-link">
                                    Level 2.2
                                    <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
                                </a>
                                <ul class="pc-submenu">
                                    <li class="pc-item"><a class="pc-link" href="#!">Level 3.1</a></li>
                                    <li class="pc-item"><a class="pc-link" href="#!">Level 3.2</a></li>
                                    <li class="pc-item pc-hasmenu">
                                        <a href="#!" class="pc-link">
                                            Level 3.3
                                            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
                                        </a>
                                        <ul class="pc-submenu">
                                            <li class="pc-item"><a class="pc-link" href="#!">Level 4.1</a></li>
                                            <li class="pc-item"><a class="pc-link" href="#!">Level 4.2</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            <li class="pc-item pc-hasmenu">
                                <a href="#!" class="pc-link">
                                    Level 2.3
                                    <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
                                </a>
                                <ul class="pc-submenu">
                                    <li class="pc-item"><a class="pc-link" href="#!">Level 3.1</a></li>
                                    <li class="pc-item"><a class="pc-link" href="#!">Level 3.2</a></li>
                                    <li class="pc-item pc-hasmenu">
                                        <a href="#!" class="pc-link">
                                            Level 3.3
                                            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
                                        </a>
                                        <ul class="pc-submenu">
                                            <li class="pc-item"><a class="pc-link" href="#!">Level 4.1</a></li>
                                            <li class="pc-item"><a class="pc-link" href="#!">Level 4.2</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="pc-item">
                        <a href="/other/sample-page.html" class="pc-link">
                            <span class="pc-micon"><i data-feather="sidebar"></i></span>
                            <span class="pc-mtext">Sample page</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    <header class="pc-header">
        <div class="header-wrapper flex max-sm:px-[15px] px-[25px] grow">
            <!-- Mobile block -->
            <div class="me-auto pc-mob-drp">
                <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
                    <li class="pc-h-item pc-sidebar-collapse max-lg:hidden lg:inline-flex">
                        <a href="#" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="sidebar-hide">
                            <i data-feather="menu"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup lg:hidden">
                        <a href="#" class="pc-head-link ltr:!ml-0 rtl:!mr-0" id="mobile-collapse">
                            <i data-feather="menu"></i>
                        </a>
                    </li>
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <i data-feather="search"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-2 py-1">
                                <input type="search" class="form-control !border-0 !shadow-none"
                                    placeholder="Search here. . ." />
                            </form>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="ms-auto">
                <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
                    {{-- Theme toggle --}}
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <i data-feather="sun"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
                                <i data-feather="moon"></i> <span>Dark</span>
                            </a>
                            <a href="#!" class="dropdown-item" onclick="layout_change('light')">
                                <i data-feather="sun"></i> <span>Light</span>
                            </a>
                            <a href="#!" class="dropdown-item" onclick="layout_change_default()">
                                <i data-feather="settings"></i> <span>Default</span>
                            </a>
                        </div>
                    </li>

                    {{-- Settings --}}
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <i data-feather="settings"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a href="https://chat.whatsapp.com/BYiNrHnIg7K7elVHvwhrUd?mode=hqrt2"
                                class="dropdown-item" target="_blank">
                                <i class="ti ti-headset"></i><span>Support</span>
                            </a>
                            <a href="{{ route('logout') }}" class="dropdown-item">
                                <i class="ti ti-power"></i><span>Logout</span>
                            </a>
                        </div>
                    </li>

                    {{-- Notifikasi --}}
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <i data-feather="bell"></i>
                            <span
                                class="badge bg-success-500 text-white rounded-full z-10 absolute right-0 top-0">3</span>
                        </a>
                        {{-- Dropdown notifikasi (biarkan seperti asli, sudah oke) --}}
                        {{-- ... (boleh tetap, atau kamu pendekkan kalau mau) --}}
                    </li>

                    {{-- User profile --}}
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-pc-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" data-pc-auto-close="outside"
                            aria-expanded="false">
                            <i data-feather="user"></i>
                        </a>
                        <div
                            class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown p-2 overflow-hidden">
                            <div class="dropdown-header flex items-center justify-between py-4 px-5 bg-primary-500">
                                <div class="flex mb-1 items-center">
                                    <div class="shrink-0">
                                        <img src="/images/user/avatar-2.jpg" alt="user-image"
                                            class="w-10 rounded-full" />
                                    </div>
                                    <div class="grow ms-3">
                                        <h6 class="mb-1 text-white">
                                            {{ session('user.nama') }}
                                        </h6>
                                        <span class="text-white">
                                            {{ session('user.username') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-body py-4 px-5">
                                {{-- isi tambahan kalau perlu --}}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <!-- [ Header Topbar ] end -->

    {{-- MAIN CONTENT --}}
    @yield('content')

    {{-- GLOBAL DELETE MODAL --}}
    <div id="deleteConfirmModal" class="delete-backdrop hidden">
        <div class="delete-dialog">
            <button type="button" id="deleteConfirmClose" class="delete-close">&times;</button>

            <div class="delete-icon-wrap">
                <div class="delete-icon-circle">
                    <span>!</span>
                </div>
            </div>

            <h3 class="delete-title">You are about to delete task</h3>
            <p class="delete-text">
                Are you sure you want to delete this post? This action cannot be undone.
            </p>

            <form id="deleteConfirmForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="delete-btn-row">
                    <button type="button" id="deleteConfirmCancel" class="btn-delete-cancel">
                        Cancel
                    </button>
                    <button type="submit" class="btn-delete-danger">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid mx-10">
            <div class="grid grid-cols-12 gap-1.5">
                <div class="col-span-12 sm:col-span-6 my-1">
                    <p class="m-0">
                        <a href="https://codedthemes.com/"
                            class="text-theme-bodycolor dark:text-themedark-bodycolor hover:text-primary-500 dark:hover:text-primary-500"
                            target="_blank">
                            Dibuat oleh SMKN 2 Kraksaan
                        </a>
                        — Tidak untuk komersial — karya siswa untuk belajar.
                    </p>
                </div>
                <div class="col-span-12 sm:col-span-6 my-1 justify-self-end">
                    <p class="inline-block max-sm:mr-3 sm:ml-2">
                        Dibuat pada <a href="https://themewagon.com" target="_blank">@2025</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Required Js -->
    <script src="/js/plugins/simplebar.min.js"></script>
    <script src="/js/plugins/popper.min.js"></script>
    <script src="/js/icon/custom-icon.js"></script>
    <script src="/js/plugins/feather.min.js"></script>
    <script src="/js/component.js"></script>
    <script src="/js/theme.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/alert.js"></script>

    <div class="floting-button fixed bottom-[50px] right-[30px] z-[1030]">
        {{-- tombol melayang kalau perlu --}}
    </div>
</body>

</html>

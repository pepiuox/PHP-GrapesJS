<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRUD Admin</title>
        <!-- Bootstrap core CSS -->
        <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

        <style>
            :root{
                --header-height: 3rem;
                --nav-width: 68px;
                --first-color: #4723D9;
                --first-color-light: #AFA5D9;
                --white-color: #F7F6FB;
                --body-font: 'Nunito', sans-serif;
                --normal-font-size: 1rem;
                --z-fixed: 100
            }
            *,::before,::after{
                box-sizing: border-box
            }
            body{
                position: relative;
                margin: var(--header-height) 0 0 0;
                padding: 0 1rem;
                font-family: var(--body-font);
                font-size: var(--normal-font-size);
                transition: .5s
            }
            a{
                text-decoration: none
            }
            .header{
                width: 100%;
                height: var(--header-height);
                position: fixed;
                top: 0;
                left: 0;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 1rem;
                background-color: var(--white-color);
                z-index: var(--z-fixed);
                transition: .5s
            }
            .header_toggle{
                color: var(--first-color);
                font-size: 1.5rem;
                cursor: pointer
            }
            .header_img{
                width: 35px;
                height: 35px;
                display: flex;
                justify-content: center;
                border-radius: 50%;
                overflow: hidden
            }
            .header_img img{
                width: 40px
            }
            .l-navbar{
                position: fixed;
                top: 0;
                left: -30%;
                width: var(--nav-width);
                height: 100vh;
                background-color: var(--first-color);
                padding: .5rem 1rem 0 0;
                transition: .5s;
                z-index: var(--z-fixed)
            }
            .nav{
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                overflow: hidden
            }
            .nav_logo, .nav_link{
                display: grid;
                grid-template-columns: max-content max-content;
                align-items: center;
                column-gap: 1rem;
                padding: .5rem 0 .5rem 1.5rem
            }
            .nav_logo{
                margin-bottom: 2rem
            }
            .nav_logo-icon{
                font-size: 1.25rem;
                color: var(--white-color)
            }
            .nav_logo-name{
                color: var(--white-color);
                font-weight: 700
            }
            .nav_link{
                position: relative;
                color: var(--first-color-light);
                margin-bottom: 1.5rem;
                transition: .3s
            }
            .nav_link:hover{
                color: var(--white-color)
            }
            .nav_icon{
                font-size: 1.25rem
            }
            .show{
                left: 0
            }
            .body-pd{
                padding-left: calc(var(--nav-width) + 1rem)
            }
            .active{
                color: var(--white-color)
            }
            .active::before{
                content: '';
                position: absolute;
                left: 0;
                width: 2px;
                height: 32px;
                background-color: var(--white-color)
            }
            .height-100{
                height:100vh
            }
            @media screen and (min-width: 768px){
                body{
                    margin: calc(var(--header-height) + 1rem) 0 0 0;
                    padding-left: calc(var(--nav-width) + 2rem)
                }
                .header{
                    height: calc(var(--header-height) + 1rem);
                    padding: 0 2rem 0 calc(var(--nav-width) + 2rem)
                }
                .header_img{
                    width: 40px;
                    height: 40px
                }
                .header_img img{
                    width: 45px
                }
                .l-navbar{
                    left: 0;
                    padding: 1rem 1rem 0 0
                }
                .show{
                    width: calc(var(--nav-width) + 156px)
                }
                .body-pd{
                    padding-left: calc(var(--nav-width) + 188px)
                }
            }
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }

            .b-example-divider {
                width: 100%;
                height: 3rem;
                background-color: rgba(0, 0, 0, .1);
                border: solid rgba(0, 0, 0, .15);
                border-width: 1px 0;
                box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
            }

            .b-example-vr {
                flex-shrink: 0;
                width: 1.5rem;
                height: 100vh;
            }

            .bi {
                vertical-align: -.125em;
                fill: currentColor;
            }

            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden;
            }

            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }

            .btn-bd-primary {
                --bd-violet-bg: #712cf9;
                --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

                --bs-btn-font-weight: 600;
                --bs-btn-color: var(--bs-white);
                --bs-btn-bg: var(--bd-violet-bg);
                --bs-btn-border-color: var(--bd-violet-bg);
                --bs-btn-hover-color: var(--bs-white);
                --bs-btn-hover-bg: #6528e0;
                --bs-btn-hover-border-color: #6528e0;
                --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
                --bs-btn-active-color: var(--bs-btn-hover-color);
                --bs-btn-active-bg: #5a23c8;
                --bs-btn-active-border-color: #5a23c8;
            }
            .bd-mode-toggle {
                z-index: 1500;
            }

            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
            main {
                height: 100vh;
                height: -webkit-fill-available;
                max-height: 100vh;
                overflow-x: auto;
                overflow-y: hidden;
            }

            .dropdown-toggle {
                outline: 0;
            }

            .btn-toggle {
                padding: .25rem .5rem;
                font-weight: 600;
                color: var(--bs-emphasis-color);
                background-color: transparent;
            }
            .btn-toggle:hover,
            .btn-toggle:focus {
                color: rgba(var(--bs-emphasis-color-rgb), .85);
                background-color: var(--bs-tertiary-bg);
            }

            .btn-toggle::before {
                width: 1.25em;
                line-height: 0;
                content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%280,0,0,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
                transition: transform .35s ease;
                transform-origin: .5em 50%;
            }

            [data-bs-theme="dark"] .btn-toggle::before {
                content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%28255,255,255,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
            }

            .btn-toggle[aria-expanded="true"] {
                color: rgba(var(--bs-emphasis-color-rgb), .85);
            }
            .btn-toggle[aria-expanded="true"]::before {
                transform: rotate(90deg);
            }

            .btn-toggle-nav a {
                padding: .1875rem .5rem;
                margin-top: .125rem;
                margin-left: 1.25rem;
            }
            .btn-toggle-nav a:hover,
            .btn-toggle-nav a:focus {
                background-color: var(--bs-tertiary-bg);
            }

            .scrollarea {
                overflow-y: auto;
            }
        </style>


        <!-- Custom styles for this template -->
        <link href="dashboard.css" rel="stylesheet">
    </head>
    <body id="body-pd">
        <header class="header" id="header">
            <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
            <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
            <div class="navbar-nav">
                <div class="nav-item text-nowrap">
                    <a class="nav-link px-3" href="#">Sign out</a>
                </div>
            </div>
            <div class="header_img"> <img src="https://i.imgur.com/hczKIze.jpg" alt=""> </div>
        </header>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <nav id="sidebarMenu" class="flex-shrink-0 col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                        <div class="flex-shrink-0 p-3">
                            <a href="/" class="d-flex align-items-center pb-3 mb-3 link-body-emphasis text-decoration-none border-bottom">
                                <svg class="bi pe-none me-2" width="30" height="24"><use xlink:href="#bootstrap"/></svg>
                                <span class="fs-5 fw-semibold">CRUD Tables</span>
                            </a>
                            <ul class="list-unstyled ps-0">
                                <li class="mb-1">
                                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                                        Home
                                    </button>
                                    <div class="collapse show" id="home-collapse">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Overview</a></li>
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Updates</a></li>
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Reports</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="mb-1">
                                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                                        Dashboard
                                    </button>
                                    <div class="collapse" id="dashboard-collapse">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Overview</a></li>
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Weekly</a></li>
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Monthly</a></li>
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Annually</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="mb-1">
                                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                                        Orders
                                    </button>
                                    <div class="collapse" id="orders-collapse">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">New</a></li>
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Processed</a></li>
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Shipped</a></li>
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Returned</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="border-top my-3"></li>
                                <li class="mb-1">
                                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                                        Account
                                    </button>
                                    <div class="collapse" id="account-collapse">
                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">New...</a></li>
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Profile</a></li>
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Settings</a></li>
                                            <li><a href="#" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Sign out</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>

                </div>
                <main class="height-100 bg-light col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="container-fluid">
                        <div class="row">
                            <h2> hello content</h2>
                            <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                                Link with href
                            </a>
                        </div>
                    </div>
                </main>
            </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function (event) {

            const showNavbar = (toggleId, navId, bodyId, headerId) => {
                const toggle = document.getElementById(toggleId),
                        nav = document.getElementById(navId),
                        bodypd = document.getElementById(bodyId),
                        headerpd = document.getElementById(headerId);

                // Validate that all variables exist
                if (toggle && nav && bodypd && headerpd) {
                    toggle.addEventListener('click', () => {
                        // show navbar
                        nav.classList.toggle('show');
                        // change icon
                        toggle.classList.toggle('bx-x');
                        // add padding to body
                        bodypd.classList.toggle('body-pd');
                        // add padding to header
                        headerpd.classList.toggle('body-pd');
                    });
                }
            };

            showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

            /*===== LINK ACTIVE =====*/
            const linkColor = document.querySelectorAll('.nav_link');

            function colorLink() {
                if (linkColor) {
                    linkColor.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            }
            linkColor.forEach(l => l.addEventListener('click', colorLink));

            // Your code to run since DOM is loaded and ready
        });
    </script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
</body>
</html>



    <!-- Floating Buttons -->
    <div class="floating-buttons">
        <a href="https://wa.me/8910409128" target="_blank" class="whatsapp-float">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
        <a href="tel:+91 8910409128" class="call-float">
            <i class="fa fa-phone"></i>
        </a>
    </div>
    <!--Floating Ends -->
    <div class="top-header">
        <div class="container text-center">
            <div class="row gx-0">
                <div class="col-lg-10 text-center text-lg-start mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center" style="height: 30px;">
                        <small class="me-3 text-grey"><i class="ri-phone-line  me-2"></i>+91 89104 09128</small>
                        <small class="me-3 text-grey"><i class="ri-phone-line me-2"></i>+033 3572 7130</small>
                    </div>
                </div>
                <div class="col-lg-2 text-center text-lg-end">
                    @if(auth()->guard('web')->check())

                    <div class="btn-group mt-1">
                        <button class="btn btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Welcome {{ auth()->guard('web')->user()->name }}
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="{{ route('patient.profile') }}">My Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('patient.appointment') }}">Appointment History</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <div class="appointment-btn">
                        <a href="{{ route('login') }}"><i class="fa fa-user-circle"></i>&nbsp; &nbsp; Login </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav id="mainNavbar" class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset("assets/frontend/img/logo.png") }}" alt="Logo" width="180px">
            </a>

            <button class="mobile-toggler d-lg-none" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="justify-content-center">
            <ul class="navbar-nav">
                <li><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li><a class="nav-link {{ request()->is('home-collection*') ? 'active' : '' }}" href="{{ route('home.collection') }}">Home Collection</a></li>
                @if($navDepartments->isNotEmpty())
                <li class="nav-item dropdown mega-dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('department*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown"> Find A Doctor </a>
                    <div class="dropdown-menu mega-menu">
                        <div class="mega-menu-content">
                            <div class="mega-menu-grid">
                        @foreach($navDepartments as $department)
                                <a href="{{ route('department', $department->slug_name) }}" class="mega-menu-item">
                                    <div class="mega-menu-icon">
                                        <img src="{{ asset('storage/' . $department->icon_name) }}" alt="{{ $department->department_name }}" width="32" height="32">
                                    </div>
                                    <div class="mega-menu-text">
                                        <span class="mega-menu-title">{{ $department->department_name }}</span>
                                    </div>
                                </a>
                        @endforeach
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                <li><a class="nav-link {{ request()->is('about-special-clinic*') ? 'active' : '' }}" href="{{ route('about.special.clinic') }}">About Special Clinic</a></li>
                <li><a class="nav-link {{ request()->is('about-us') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a></li>
                <li><a class="nav-link {{ request()->is('gallery*') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a></li>
                <li><a class="nav-link {{ request()->is('contact-us*') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>
        </div>
    </nav>

    <!-- Mobile Sidebar Menu -->
    <!-- Custom Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <span class="mobile-close">&times;</span>
        <ul>
            <li><a href="{{ route('home') }}" class="mobile-nav-item">Home</a></li>
            <li><a href="{{ route('home.collection') }}" class="mobile-nav-item">Home Collection</a></li>
            <li class="has-submenu">
                <a href="javascript:void(0);" class="submenu-toggle">Find A Doctor ▾</a>
                @if($navDepartments->isNotEmpty())
                <ul class="submenu">
                    @foreach($navDepartments as $department)
                    <li><a href="{{ route('department', $department->slug_name) }}" class="dropdown-item mobile-submenu-item">{{ $department->department_name }}</a></li>
                    @endforeach
                </ul>
                @endif
            </li>
            <li><a href="{{ route('about.special.clinic') }}" class="mobile-nav-item">About Special Clinic</a></li>
            <li><a href="{{ route('about') }}" class="mobile-nav-item">About Us</a></li>
            <li><a href="{{ route('gallery') }}" class="mobile-nav-item">Gallery</a></li>
            <li><a href="{{ route('contact') }}" class="mobile-nav-item">Contact</a></li>
            @if(auth()->guard('web')->check())
                <li><a href="{{ route('patient.profile') }}" class="mobile-nav-item">My Profile</a></li>
                <li><a href="{{ route('patient.appointment') }}" class="mobile-nav-item">Appointment History</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mobile-nav-item" style="background:none;border:0;padding:0;">Logout</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" class="mobile-nav-item mobile-login-highlight">Patient Login</a></li>
            @endif
        </ul>
    </div>
    <div id="mobileMenuOverlay" class="mobile-menu-overlay"></div>
    <!-- Header Ends -->

    <!-- Enhanced Mobile Menu & Mega Menu JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // MEGA MENU POSITIONING (for fixed position dropdown)
    // ============================================
    const megaDropdown = document.querySelector('.mega-dropdown');
    const megaMenu = document.querySelector('.mega-menu');
    const navbar = document.getElementById('mainNavbar');

    if (megaDropdown && megaMenu && navbar) {
        // Function to position mega menu just below navbar
        function positionMegaMenu() {
            const navbarRect = navbar.getBoundingClientRect();
            megaMenu.style.top = (navbarRect.bottom) + 'px';
        }

        // Bootstrap dropdown instance for programmatic control
        const dropdownToggle = megaDropdown.querySelector('[data-bs-toggle="dropdown"]');
        const bsDropdown = dropdownToggle ? new bootstrap.Dropdown(dropdownToggle, { autoClose: false }) : null;

        // Open on hover immediately after page load
        if (bsDropdown) {
            megaDropdown.addEventListener('mouseenter', function() {
                positionMegaMenu();
                bsDropdown.show();
            });

            // Slight delay to prevent flicker when moving cursor to panel
            let hideTimer;
            megaDropdown.addEventListener('mouseleave', function() {
                hideTimer = setTimeout(function() { bsDropdown.hide(); }, 120);
            });
            megaMenu.addEventListener('mouseenter', function() { if (hideTimer) { clearTimeout(hideTimer); } });
        }

        // Keep position correct when Bootstrap shows the menu via any trigger
        megaDropdown.addEventListener('show.bs.dropdown', function() { positionMegaMenu(); });

        // Reposition on scroll and resize while open
        let ticking = false;
        function updatePosition() {
            if (megaMenu.classList.contains('show')) { positionMegaMenu(); }
            ticking = false;
        }
        window.addEventListener('scroll', function() {
            if (!ticking) { window.requestAnimationFrame(updatePosition); ticking = true; }
        });
        window.addEventListener('resize', function() {
            if (!ticking) { window.requestAnimationFrame(updatePosition); ticking = true; }
        });
    }

    // ============================================
    // MOBILE MENU
    // ============================================
    const mobileToggler = document.querySelector('.mobile-toggler');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileClose = document.querySelector('.mobile-close');
    const mobileOverlay = document.getElementById('mobileMenuOverlay');
    const submenuToggles = document.querySelectorAll('.submenu-toggle');

    // Open mobile menu
    if (mobileToggler) {
        mobileToggler.addEventListener('click', function() {
            mobileMenu.classList.add('active');
            mobileOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close mobile menu
    function closeMobileMenu() {
        mobileMenu.classList.remove('active');
        mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (mobileClose) {
        mobileClose.addEventListener('click', closeMobileMenu);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMobileMenu);
    }

    // Submenu toggles
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = this.nextElementSibling;

            // Toggle active class on the toggle button
            this.classList.toggle('active');

            // Toggle active class on the submenu
            if (submenu && submenu.classList.contains('submenu')) {
                submenu.classList.toggle('active');
            }
        });
    });

    // Close menu on navigation
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-item:not(.submenu-toggle), .mobile-submenu-item');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Don't close if it's a submenu toggle
            if (!this.classList.contains('submenu-toggle')) {
                closeMobileMenu();
            }
        });
    });
});
</script>
<!-- Header Ends -->

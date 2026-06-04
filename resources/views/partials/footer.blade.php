<!-- Footer Start -->
<div class="container-fluid footer position-relative bg-red text-white-50 py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4 pe-lg-5">
                <a href="{{ route('home') }}" class="navbar-brand ">
                    @php
                        $logo = get_setting('SITE_LOGO');
                        $logoPath = $logo ? asset('storage/' . $logo) : asset('assets/images/logo.png');
                    @endphp
                    <img src="{{ $logoPath }}" alt="{{ get_setting('SITE_NAME', config('app.name')) }}" style="padding-bottom:30px;" width="130px">
                </a>
                <div class="footer-text">
                    <p class="mb-4">Pathogenes Polyclinic & Diagnostics provides comprehensive medical care and advanced diagnostic services under one roof. It offers reliable testing, expert consultations, and patient-centered healthcare for individuals and families.</p>
                    <div class="mt-3">
                        <h4 class="mb-3">Clinic Timings</h4>
                        <ul class="list-unstyled m-0">
                            <li class="d-flex align-items-center mb-2"><i class="fa fa-clock me-2"></i>Monday - Saturday: 7:30AM - 08:00PM</li>
                            <li class="d-flex align-items-center"><i class="fa fa-clock me-2"></i>Sunday: 7:30AM - 03:00PM</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 ps-lg-5">
                <h4 class="mb-4">Quick Links</h4>
                <a class="btn btn-link" href="{{ route('home') }}">Home</a>
                <a class="btn btn-link" href="{{ route('home.collection') }}">Home Collection</a>
                <a class="btn btn-link" href="{{ route('about.special.clinic') }}">About Special Clinic</a>
                <a class="btn btn-link" href="{{ route('about') }}">About Us</a>
                <a class="btn btn-link" href="{{ route('gallery') }}">Gallery</a>
                <a class="btn btn-link" href="{{ route('contact') }}">Contact Us</a>
                <a class="btn btn-link" href="{{ route('privacy') }}">Privacy Policy</a>
                <a class="btn btn-link" href="{{ route('terms') }}">Terms & Conditions</a>
                <a class="btn btn-link" href="{{ route('facebook-data-deletion') }}">Data Deletion</a>
            </div>
            <div class="col-lg-4 pe-lg-5 footer-text">
                <h4 class=" mb-4">Get In Touch</h4>
                <p class=""><i class="fa fa-map-marker me-2"></i>140/A, Bangur Ave Block-A Rd, Block C, Block A, Lake Town, Kolkata, West Bengal 700055</p>
                <p class=""><i class="fa fa-phone me-2"></i>+91 89104 09128</p>
                <p class=""><i class="fa fa-phone me-2"></i>033 3572 7130</p>
                <p class=""><i class="fa fa-envelope me-2"></i>info@pathogenesdiagnostics.com/ pathogenesdg@gmail.com</p>
                <div class="d-flex mt-4">
                    <a href="#" target="_blank" class="btn btn-lg-square btn-secondary me-2"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-lg-square btn-secondary me-2" href="#" target="_blank"><i class="fab fa-youtube"></i></a>

                    <a class="btn btn-lg-square btn-secondary me-2" href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-lg-square btn-secondary me-2" href="#" target="_blank"><i class="fab fa-google"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<!-- Footer End -->
<!-- Copyright Start -->
<div class="container-fluid copyright footer-bottom text-white-50 py-2">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 text-white">&copy; <a href="{{ route('home') }}"> Pathogenes Polyclinic & Diagnostics </a>. All Rights Reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 text-white">Designed by <a href="https://digiexweb.com/" target="_blank">Digiex Web</a></p>
            </div>
        </div>
    </div>
</div>
<!-- Copyright End -->

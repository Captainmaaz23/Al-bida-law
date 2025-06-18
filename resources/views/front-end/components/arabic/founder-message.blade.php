<!-- Page banner Area -->
<div class="page-banner bg-1">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="page-content">
                    <h2>رسالة المؤسس</h2>
                    <ul>
                        <li dir="rtl">رسالة المؤسس</li>
                        <li dir="rtl" style="font-size:20px;"><a href="">بيت <i
                                    class="las la-angle-left"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page banner Area -->



<div class="attorney-details pt-100 pb-70" dir="rtl" style="background-color: #052c3f;">
    <div class="container faq-area">
        <!-- Top Message Section -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h3 class="fw-bold" style="color: white;">Dear Clients and Partners,</h3>
                <p style="color: #d1d1d1;">{{ $founder->arabic_message }}</p>
            </div>
        </div>

        <div class="row align-items-center text-end" dir="rtl">
            <!-- Image on the Left -->
            <div class="col-md-6 order-md-1 text-center mt-4 mt-md-0">
                <div style="border: 6px solid #dc3545; padding: 10px; border-radius: 8px; display: inline-block;">
                    <!-- Founder Image -->
                    <img src="{{ url('public/uploads/founders/' . $founder->image) }}" alt="Founder"
                        class="img-fluid rounded" style="max-height: 250px; width: 250px;">

                    <!-- Social Icons Below Image -->
                    <div class="attor-social mt-4">
                        <ul class="list-inline mb-mt-10 d-flex justify-content-center gap-4">
                            <li class="list-inline-item">
                                <a href="https://www.facebook.com/" target="_blank" class="social-icon">
                                    <i class="lab la-facebook-f"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://www.instagram.com/" target="_blank" class="social-icon">
                                    <i class="lab la-instagram"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://www.twitter.com/" target="_blank" class="social-icon">
                                    <i class="lab la-twitter"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://www.linkedin.com/" target="_blank" class="social-icon">
                                    <i class="lab la-linkedin-in"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            <!-- Text on the Right -->
            <div class="col-md-6 order-md-2">
                <h4 class="fw-bold text-white">{{ $founder->arabic_name }}</h4>
                <h6 class="text-light">{{ $founder->arabic_designation }}</h6>
                <h6 class="text-light">مكتب البدع للمحاماة</h6>
                <hr style="border-color: #ffffff3b;">

                <!-- Contact Info -->
                <ul class="list-unstyled text-light">
                    <li class="mb-2">
                        <i class="las la-phone-volume me-2"></i>
                        <a href="tel:+07554332322" style="color: white;">{{ optional($founder->user)->phone }}</a>
                    </li>
                    <li class="mb-2">
                        <i class="las la-envelope me-2"></i>
                        <a href="mailto:{{ optional($founder->user)->email }}" style="color: white;">
                            {{ optional($founder->user)->email }}
                        </a>
                    </li>
                    <li class="mb-2">
                        <i class="las la-map-marker me-2"></i>
                        <span>{{ optional($founder->user)->address }}</span>
                    </li>
                </ul>

                <!-- Working Hours -->
                <div class="mt-4">
                    <h5 class="text-white">ساعات العمل</h5>
                    <div class="d-flex justify-content-between text-light">
                        <ul class="list-unstyled">
                            <li>الإثنين</li>
                            <li>الثلاثاء</li>
                            <li>الأحد</li>
                        </ul>
                        <ul class="list-unstyled">
                            <li>9:00 ص - 8:00 م</li>
                            <li>9:00 ص - 8:00 م</li>
                            <li>9:00 ص - 8:00 م</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>
</div>
<style>
    .social-icon {
        color: rgb(0, 0, 0);
        background: white;
        font-size: 22px;
        transition: all 0.3s ease;
        padding: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .social-icon:hover {
        background-color: #dc3545;
        color: white;
    }

    .attor-social ul {
        padding: 0;
        margin: 0;
    }

    .attor-social ul li {
        display: inline-block;
    }
</style>

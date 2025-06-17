<!-- Heder Area -->
<header class="header-area">
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6 col-sm-6">
                    <ul class="left-info">
                        <li>
                            <a href="">
                                <i class="las la-envelope"></i>
                                <span class="__cf_email__" data-cfemail="">info@albiddalawfirm.com</span>
                            </a>
                        </li>
                        <li>
                            <a href="tel:+823-456-879">
                                <i class="las la-phone"></i>
                                +974 4140 0444
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-6 col-sm-6">
                    <ul class="right-info">
                        <li>
                            <a href="https://www.facebook.com/login/" target="_blank">
                                <i class="lab la-facebook-f"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/i/flow/login" target="_blank">
                                <i class="lab la-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/" target="_blank">
                                <i class="lab la-instagram"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.google.co.uk/" target="_blank">
                                <i class="lab la-google-plus"></i>
                            </a>
                        </li>

                        <li class="heder-btn">
                            <a href="contact.html">احصل على جدول زمني</a>
                        </li>
                        <li class="">
                            <button id="languageToggle" class="btn btn-sm p-1" style="width: 60px;">
                                <img src="https://flagcdn.com/w20/qa.png" alt="Qatar Flag" class="me-1"
                                    style="height: 15px">
                                <span>AR</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Navbar Area -->
    <div class="navbar-area">
        <div class="atorn-responsive-nav">
            <div class="container">
                <div class="atorn-responsive-menu">
                    <div class="logo">
                        <a href="javascript:void(0)">
                            {{-- <img src="{{asset_url('frontend/img/logo-white01.png')}}" class="logo1" alt="logo"> --}}
                            {{-- <img src="{{ asset_url('frontendimg/logo-white01.png') }}" class="logo2" alt="logo"> --}}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="atorn-nav" style="direction: rtl; text-align: right;">
            <div class="container">
                <nav class="navbar navbar-expand-md navbar-light mx-auto px-5">
                    <div class="collapse navbar-collapse mean-menu">
                        <a class="navbar-brand" href="javascript:void(0)">
                            {{-- <img src="{{ asset_url('frontend/img/logo.png') }}" class="logo1" alt="logo"> --}}
                            <img src="{{ url('public/uploads/logo', $logo->image) }}" class="logo2" alt="logo">
                        </a>
                        <ul class="navbar-nav ms-auto" style="margin-right: 350px;">
                            <li class="nav-item">
                                <a href="{{ route('front.arabicPage') }}" class="nav-link active">
                                    بيت
                                </a>

                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0)" class="nav-link">
                                    معلومات عنا <i class="las la-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="nav-item">
                                        <a href="javascript:void(0)" class="nav-link active">مؤسس رسالة</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="javascript:void(0)" class="nav-link">المهمة / الرؤية</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="javascript:void(0)" class="nav-link">الأسئلة الشائعة</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0)" class="nav-link">
                                    خدمات <i class="las la-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="nav-item">
                                        <a href="{{ route('front.arabic_service') }}" class="nav-link">الجميع خدمات</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0)" class="nav-link">قانون الشركات</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0)" class="nav-link">حل النزاعات</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0)" class="nav-link">المعاملات العقارية</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0)" class="nav-link">الملكية الفكرية</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0)" class="nav-link">
                                    دراسة الحالة <i class="las la-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="nav-item">
                                        <a href="javascript:void(0)" class="nav-link">دراسة الحالة</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="javascript:void(0)" class="nav-link">قصص النجاح</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0)" class="nav-link">
                                    فريقنا
                                </a>

                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0)" class="nav-link">
                                    مقالات <i class="las la-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="nav-item">
                                        <a href="{{ route('front.allarabicblogs') }}" class="nav-link">مقالات</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="javascript:void(0)" class="nav-link">بودكاست</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0)" class="nav-link">اتصال</a>
                            </li>
                        </ul>
                    </div>

                </nav>
            </div>
        </div>
    </div>
    <!-- End Navbar Area -->
</header>
<!-- End Heder Area -->

<!-- Search Overlay -->
<div class="search-overlay">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="search-overlay-layer"></div>
            <div class="search-overlay-layer"></div>
            <div class="search-overlay-layer"></div>

            <div class="search-overlay-close">
                <span class="search-overlay-close-line"></span>
                <span class="search-overlay-close-line"></span>
            </div>

            <div class="search-overlay-form">
                <form>
                    <input type="text" class="input-search" placeholder="Search here...">
                    <button type="submit"><i class='las la-search'></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Search Overlay -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const languageToggle = document.getElementById('languageToggle');
        let currentLang = localStorage.getItem('preferredLanguage') || 'en';

        // Update button on load
        updateButton(currentLang);

        // Toggle language on click
        languageToggle.addEventListener('click', function() {
            currentLang = (currentLang === 'en') ? 'ar' : 'en';
            localStorage.setItem('preferredLanguage', currentLang);
            updateButton(currentLang);
            switchLanguage(currentLang);
        });

        function updateButton(lang) {
            const flagImg = languageToggle.querySelector('img');
            const spanText = languageToggle.querySelector('span');

            if (lang === 'ar') {
                flagImg.src = 'https://flagcdn.com/w20/gb.png';
                spanText.textContent = 'EN';
            } else {
                flagImg.src = 'https://flagcdn.com/w20/qa.png';
                spanText.textContent = 'AR';
            }
        }

        function switchLanguage(lang) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/set-language', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        language: lang
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (lang === 'ar') {
                        window.location.href = "{{ route('front.arabicPage') }}";
                    } else {
                        window.location.href = "{{ route('front.home') }}";
                    }
                })
                .catch(error => {
                    console.error('Error switching language:', error);
                    // Fallback to page reload
                    if (lang === 'ar') {
                        window.location.href = "{{ route('front.arabicPage') }}";
                    } else {
                        window.location.href = "{{ route('front.home') }}";
                    }
                });
        }
    });
</script>

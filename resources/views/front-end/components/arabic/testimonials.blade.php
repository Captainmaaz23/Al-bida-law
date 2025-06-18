<!-- Case Study Slider Area -->
<div class="testimonials-area case-study-area ptb-100">
    <div class="container">
        <div class="section-title text-center">
            <span style="font-weight: bold; font-size:24px; color: #8a1538 !important;">شهادات</span>
            <h2 style=" color: #131e3d !important;">هذا ما يقوله <span style="color: #8a1538 !important;">عملاؤنا</span>
                عنا</h2>
        </div>

        <div class="testimonials-slider owl-carousel owl-theme">
            @foreach ($client_review as $client)
                <div class="testimonials-slider-item" style="display: flex; align-items: flex-end; gap: 20px;"
                    dir="rtl">
                    <!-- Image on the right -->
                    <div class="testimonials-img">
                        <img src="{{ url('public/uploads/client_review/' . $client->image) }}" alt="Image"
                            style="border-radius: 6px;" />
                    </div>

                    <!-- Text on the left -->
                    <div class="img-text-ar ">
                        <h3 class="text-end">{{ $client->heading_arabic }}</h3>
                        <p class="text-end" style="color:white !important">{{ $client->summary_arabic }}</p>
                        <a href="javascript:void(0)" class="d-block text-end" style="font-size:16px;">
                            <i class="las la-angle-double-left"></i>
                            اقرأ المزيد
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- End Case Study Slider Area -->

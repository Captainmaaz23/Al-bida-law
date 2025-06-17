<!-- Start Choose Area -->
<div class="choose-area ptb-100 backcolor">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="choose-text">

                    <div class="section-title">
                        <span style="font-weight: bold; font-size: 24px;">لماذا تختارنا؟</span>
                        {{-- <h2>Mastering Legal Excellence Across Diverse Sectors.</h2>
                        <p>At Al-Bidda Law Firm, we pride ourselves on being at the forefront of legal excellence. Our unwavering commitment is to providing unparalleled services, blending traditional values with modern practices to meet your unique legal needs.
                       </p> --}}

                        @foreach ($chooseUs as $choose)
                            <h2 dir="rtl" class="text-end">{{ $choose->arabic_heading }}</h2>
                            <p dir="rtl" class="text-end">{{ $choose->arabic_summary }}
                            </p>
                        @endforeach


                    </div>

                    @foreach ($chooseUs as $choose)
                        @foreach ($choose->details as $detail)
                            <div class="choose-card" dir="rtl">
                                <div class="d-flex align-items-start gap-3">
                                    <!-- Image box with white background -->
                                    <div
                                        style="flex-shrink: 0; background-color: #fff; padding: 10px; border-radius: 5px;">
                                        <img src="{{ url('public/uploads/why-choose/sub_image/' . $detail->sub_image) }}"
                                            alt="Image" style="max-width: 60px; height: auto;" />
                                    </div>

                                    <!-- Text box -->
                                    <div class="flex-grow-1">
                                        <h3 class="text-end">{{ $detail->arabicsub_heading ?? '' }}</h3>
                                        <p class="text-end">{{ $detail->arabicsub_summary ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach


                </div>
            </div>
            {{-- here is the code where show an vedios --}}
            <div class="col-lg-6">
                <div class="choose-img">
                    <div class="d-table">
                        <div class="d-table-cell">
                            <div class="video-box">
                                <a href="javascript:void(0)" class="video-btn popup-youtube">
                                    <i class="las la-play"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- End Choose Area -->


<!-- Fun Fact Area -->
<div class="fun-facts-area pb-70 backcolor">
    <div class="container">
        <div class="fun-facts-shape top">
            <img src="{{ asset_url('frontend/img/shape.png') }}" class="shape1" alt="Shape">
            <img src="{{ asset_url('frontend/img/shape.png') }}" class="shape2" alt="Shape">
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-3 col-sm-6">
                <div class="signle-fun-fact">
                    <i class="las la-balance-scale"></i>
                    <h3><span class="odometer" data-count="15890">00</span>+</h3>
                    <p style="font-weight: bold; font-size:20px">القضية المقدمة</p>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="signle-fun-fact">
                    <i class="las la-gavel"></i>
                    <h3><span class="odometer" data-count="525">00</span>+</h3>
                    <p style="font-weight: bold; font-size:20px">المستشارين</p>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="signle-fun-fact">
                    <i class="las la-trophy"></i>
                    <h3><span class="odometer" data-count="275">00</span>+</h3>
                    <p style="font-weight: bold; font-size:20px">إجمالي العملاء</p>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="signle-fun-fact">
                    <i class="lab la-gitkraken"></i>
                    <h3><span class="odometer" data-count="1248">00</span></h3>
                    <p style="font-weight: bold; font-size:20px">المشاريع</p>
                </div>
            </div>
        </div>

        <div class="fun-facts-shape bottom">
            <img src=" {{ asset_url('frontend/img/shape.png') }} " class="shape1" alt="Shape">
            <img src="{{ asset_url('frontend/img/shape.png') }}" class="shape2" alt="Shape">
        </div>
    </div>
</div>
<!-- Ends Fun Fact Area -->

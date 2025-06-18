<!-- Right Way Area -->
<div class="right-way-area ptb-100 backcolor">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 right-way">
                <div class="fun-facts-shape top">
                    <img src="{{ asset_url('frontend/img/shape.png') }}" class="shape1" alt="Shape">
                    <img src="{{ asset_url('frontend/img/shape.png') }}" class="shape2" alt="Shape">
                </div>

                <div class="right-way-text">
                    <div class="section-title">
                        <h2>Our Case Studies - Methodology</h2>
                        <p style="color:white!important;">{{ $casestudy->decision }}</p>
                    </div>

                    <div class="text-sign">
                        <img src="{{ url('public/uploads/case-study/' . $casestudy->image) }}" alt="Sign">
                        <h3>{{ $casestudy->client }}</h3>
                        <p style="color:white!important;">{{ $casestudy->attorny }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="video-contant">
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
<!-- End Right Way Area -->

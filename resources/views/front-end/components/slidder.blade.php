<!-- Hero Slider Area -->
<div class="hero-slider owl-carousel owl-theme">
    @foreach ($slidder as $index => $slide)
        <div class="hero-slider-item"
            style="background-image: url('{{ url('public/uploads/slidder/' . $slide->image) }}');">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="slider-content">
                            <span>Welcome to AL Bidda Law Firm</span>
                            <h1 style="color: white!important;">{!! $slide->text !!}</h1>
                            <p style="color: white!important;">{{ $slide->summary }}</p>
                            <div class="text-sign">
                                <h3>{{ $slide->name }}</h3>
                                <p style="color: white!important;">{{ $slide->attorny }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>

{{-- <div class="hero-slider-item item-bg2">
        <div class="d-table">
            <div class="d-table-cell">
                <div class="container">
                    <div class="slider-content">
                        <span>Trusted Legal Expertise</span>
                        <h1>Your Justice, Our Priority</h1>
                        <p>Expert Legal Services Backed by Experience. Whether you're facing a dispute, contract issue, or need legal consultation, our skilled attorneys are here to guide and protect your rights.</p>
                        <div class="slider-btn">
                            <a href="javascript:void(0)" class="default-btn-one me-3">Free Consulting</a>
                            <a href="javascript:void(0)" class="default-btn-two">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-slider-item item-bg3">
        <div class="d-table">
            <div class="d-table-cell">
                <div class="container">
                    <div class="slider-content">
                        <span>Comprehensive Legal Solutions</span>
                        <h1>From Consultation to Resolution</h1>
                        <p>End-to-End Legal Support Across All Practice Areas, We specialize in civil, corporate, family, and criminal law—providing personalized solutions that deliver results.</p>
                        <div class="slider-btn">
                            <a href="javascript:void(0)" class="default-btn-one me-3">Free Consulting</a>
                            <a href="javascript:void(0)" class="default-btn-two">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
<!-- End Hero Slider Area -->

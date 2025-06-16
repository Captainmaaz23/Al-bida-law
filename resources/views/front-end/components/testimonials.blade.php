<!-- Case Study Slider Area -->
<div class="testimonials-area case-study-area ptb-100">
    <div class="container">
        <div class="section-title text-center">
            <span  style="color: #8a1538 !important;">Testemonials</span>
            <h2 style="color: #131e3d !important;">Here is what <span style="color: #8a1538 !important;">Our Client</span> say about us</h3>
        </div>   
        
        <div class="testimonials-slider owl-carousel owl-theme">
            @foreach ($client_review as $client)
                <div class="testimonials-slider-item">
                    <div class="testimonials-img">
                        <img src="{{ url('public/uploads/client_review/' . $client->image) }}"  alt="Image">
                    </div>
                    <div class="img-text">
                        <h3>{{$client->heading}}</h3>
                        <p>{{$client->summary}}</p>
                        <a href="javascript:void(0)">
                            Read More
                            <i class="las la-angle-double-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- End Case Study Slider Area -->
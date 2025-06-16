 <!-- Page banner Area -->
 <div class="page-banner bg-1">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="page-content">
                    <h2>Services</h2>
                    <ul>
                        <li><a href="">Home <i class="las la-angle-right"></i></a></li>
                        <li>Services</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page banner Area -->




<!-- Services -->
<div class="blog-area ptb-100">
    <div class="container">
        <div class="row justify-content-center">
            @foreach ($services as $service)
            <div class="col-lg-6 col-sm-6">
                <div class="blog-card">
                    <a href="blog-details.html">
                        <img src="{{ url('public/uploads/services/'.$service->image) }}" alt="Image" style="width:100%;height:70vh">
                    </a>
                    <div class="blog-card-text">
                        <h3><a href="blog-details.html">{{ $service->name }}</a></h3>
                       

                        <p>{!! Str::limit($service->description,200) !!}</p>

                        <a href="{{ route('front.singleservices', $service->id) }}" class="read-more">
                            Read More <i class="las la-angle-double-right"></i>
                        </a>
                    </div>
                </div>
            </div>            
            @endforeach
        </div>
        
    </div>
</div>
<!-- Services -->
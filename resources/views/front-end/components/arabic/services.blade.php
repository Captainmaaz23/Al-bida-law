 <!-- Page banner Area -->
 <div class="page-banner bg-1">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="page-content">
                    <h2>الخدمات العربية</h2>
                    <ul>
                        <li><a href="">بيت <i class="las la-angle-right"></i></a></li>
                        <li>خدمات</li>
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
        <div class="row justify-content-start">
            @foreach ($services as $service)
            <div class="col-lg-6 col-sm-6">
                <div class="blog-card text-end" dir="rtl">
                    <a href="{{ route('front.single_arabic_service', $service->id) }}">
                        <img src="{{ url('public/uploads/services/'.$service->image) }}" alt="Image" style="width:100%;height:70vh">
                    </a>
                    <div class="blog-card-text">
                        <h3><a href="{{ route('front.single_arabic_service', $service->id) }}" >{{ $service->title_arabic }}</a></h3>
                       

                        <p>{!! Str::limit($service->arabic_description,200) !!}</p>

                        <a href="{{ route('front.single_arabic_service', $service->id) }}" class="read-more">
                            اقرأ المزيد <i class="las la-angle-double-right"></i>
                        </a>
                    </div>
                </div>
            </div>            
            @endforeach
        </div>
        
    </div>
</div>
<!-- Services -->
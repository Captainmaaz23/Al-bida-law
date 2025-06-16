<div class="service-area pt-100 pb-70">
    <div class="container">    
            <h5 style="color: #131e3d !important; font-weight: bold; font-size:24px" class="text-end" data="rtl">معلومات عنا</h5>
            @if ($about)
    <h4 style="color: #131e3d !important;" data="rtl" class="text-end">
        {{ $about->arabic_title ?? '' }}
    </h4>
@endif

        
        @if($about)
        <p class="text-end" data="rtl" style="color: #131e3d !important;">{{ $about->arabic_description }}</p>
        @endif
        <div class="row justify-content-center">
            @foreach ($about_images as $about_image)
            <div class="col-lg-4 col-sm-6">
                <div class="service-card">
                    <a href="javascript:void(0)">
                        <img src="{{ url('public/uploads/abouts/' . $about_image->image) }}" style="height: 40vh; object-fit: cover; width: 100%; object-position: center center; border-radius: 8px;"  alt="Image">
                    </a>
                    <div class="service-text">
                        <h3>
                            <a href="javascript:void(0)">
                                <span class="text-end" style="direction: rtl; text-align: left; display: block;">
                                    {{ Str::limit($about_image->arabic_imagetitle,20) }} 
                                </span>
                            </a>
                        </h3>
                        
                    </div>
                </div>
            </div>
            @endforeach           
        </div>
    </div>
</div>
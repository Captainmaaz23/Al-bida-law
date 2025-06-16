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




<!-- Start Services Details Area -->
<div class="services-details-area ptb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-7 col-sm-12">
                <div class="services-details">
                    <div class="img">
                        <img src="{{ url('public/uploads/services',$single_service->image) }}" alt="Image">
                    </div>
                    <div class="services-details-content">
                        <h3>{{ $single_service->name }}</h3>
                        
                        <p>{!! $single_service->description !!}</b></p>
                      
                    </div>
                    <div class="article-footer">

                        <div class="article-tags">
                            <span><i class="las la-tags"></i></span>
                            <a href="">Corporate Law</a>,
                            <a href="">Games</a>,
                            <a href="">Travel</a>
                        </div>

                        <div class="article-share">
                            <ul class="social">
                                <li><span>Share:</span></li>
                                <li><a href="https://www.facebook.com/login/"><i class="lab la-facebook-f pt-2"></i></a></li>
                                <li><a href="https://twitter.com/i/flow/login" class="twitter pt-2" target="_blank"><i class="lab la-twitter"></i></a></li>
                                <li><a href="https://www.instagram.com/" class="linkedin pt-2" target="_blank"><i class="lab la-linkedin-in"></i></a></li>
                                <li><a href="https://www.google.co.uk/" class="instagram pt-2" target="_blank"><i class="lab la-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-sm-12">
                <div class="side-bar">
                    
                    
                    <div class="side-bar-box recent-post">
                        <h3 class="title">Success Stories</h3>
                        @foreach ($recent_services as $recent)
                        <div class="single-recent-post">
                            <div class="recent-post-img">
                                <a href="blog-details.html"><img src=" {{ url('public/uploads/services/'.$recent->image) }} " alt="Image"></a>
                            </div>
                            <div class="recent-post-content">
                                <ul>
                                    <li><a href="blog-details.html">{{ Auth::user()->name }}</a></li>
                                    <li><a href="blog-details.html"><i class="fa fa-calendar"></i>{{$recent->created_at->format('h:i A')}}</a></li>
                                </ul>
                                <h3><a href="blog-details.html">{!! Str::limit($recent->description,20) !!}</a></h3>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="side-bar-box categories-box">
                        <h3 class="title">Services</h3>
                        <ul>
                            {{-- <li><a href=""><i class="las la-angle-double-right"></i></i> News</a></li> --}}
                            @foreach ($services_name as $service)
                            <li><a href=""><i class="las la-angle-double-right"></i></i>{{$service->name}}</a></li>   
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="side-bar-box tags-box">
                        <h3 class="title">Tags</h3>
                        <ul>
                            @foreach ($blog_tag as $tag)
                            <li><a href="">{{$tag->tag}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Services Details Area Area -->
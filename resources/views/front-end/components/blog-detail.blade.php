 <!-- Page banner Area -->
 <div class="page-banner bg-1">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="page-content">
                    <h2>Blog Details</h2>
                    <ul>
                        <li><a href="">Home <i class="las la-angle-right"></i></a></li>
                        <li>Blog Details</li>
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
                        <img src="{{ url('public/uploads/blogs/'.$blogs->image) }}" alt="Image">
                    </div>
                    <div class="services-details-content">
                        <h3>Family Disputes</h3>
                        <ul class="blog-list">
                            <li>
                                <i class="las la-calendar"></i>
                                {{ $blogs->created_at->format('h:i A') }}
                            </li>
                            <li>
                                <i class="las la-user-tie"></i>
                                <a href="javascript:void(0)">{{ Auth::user()->name }}</a>
                            </li>
                        </ul>
                        <div style="text-align: justify; line-height: 2;">
                            {!! $blogs->description !!}
                        </div>
                        
                        
                        {{-- <blockquote class="blockquote">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                        </blockquote> --}}
                    </div>
                    
                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-sm-12">
                <div class="side-bar">
                    <div class="side-bar-box recent-post">
                        <h3 class="title">Recent Post</h3>
                        @foreach ($recent_blogs as $recent)
                            <div class="single-recent-post">
                                <div class="recent-post-img">
                                    <a href="javascript:void(0)"><img src="{{ url('public/uploads/blogs/',$recent->image) }}" alt="Image"></a>
                                </div>
                                <div class="recent-post-content">
                                    <ul>
                                        <li><a href="#">{{ Auth()->user()->name }}</a></li>
                                        <li><a href="#"><i class="fa fa-calendar"></i>{{ $recent->created_at->format('h:i A') }}</a></li>
                                    </ul>
                                    <h3><a href="blog-details.html">{{ $recent->name }}</a></h3>
                                </div>
                            </div>
                        @endforeach                        
                    </div>
                    
                    <div class="side-bar-box categories-box">
                        <h3 class="title">Categories</h3>
                        <ul>
                            @foreach ($services as $service)
                            <li><a href="blog-details.html"><i class="las la-angle-double-right"></i></i> {{$service->name}} </a></li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="side-bar-box tags-box">
                        <h3 class="title">Tags</h3>
                        <ul>
                            @foreach ($tags as $tag)
                            <li><a href="blog-details.html">{{$tag->tag}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Services Details Area Area -->
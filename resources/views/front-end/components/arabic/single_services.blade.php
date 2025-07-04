<!-- Page banner Area -->
<div class="page-banner bg-1">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="page-content">
                    <h2>خدمات</h2>
                    <ul>
                        <li><a href="">Home <i class="las la-angle-right"></i></a></li>
                        <li>خدمات</li>
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
            <div class="col-lg-8 col-md-7 col-sm-12 text-end" dir="rtl">
                <div class="services-details">
                    <div class="img">
                        <img src="{{ url('public/uploads/services', $single_service->image) }}" alt="Image">
                    </div>
                    <div class="services-details-content">
                        <h3>{{ $single_service->title_arabic }}</h3>

                        <p>{!! $single_service->arabic_description !!}</b></p>

                    </div>
                    <div class="article-footer">



                        <div class="article-share">
                            <ul class="social">
                                <li><span>يشارك :</span></li>
                                <li><a href="https://www.facebook.com/login/"><i class="lab la-facebook-f pt-2"></i></a>
                                </li>
                                <li><a href="https://twitter.com/i/flow/login" class="twitter pt-2" target="_blank"><i
                                            class="lab la-twitter"></i></a></li>
                                <li><a href="https://www.instagram.com/" class="linkedin pt-2" target="_blank"><i
                                            class="lab la-linkedin-in"></i></a></li>
                                <li><a href="https://www.google.co.uk/" class="instagram pt-2" target="_blank"><i
                                            class="lab la-instagram"></i></a></li>

                            </ul>
                        </div>

                        <div class="article-tags">
                            <span><i class="las la-tags text-end"></i></span>
                            @foreach ($blog_tag as $tag)
                                <a href="" class="px-2">{{ $tag->arabic_tag }},</a>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-4 col-md-5 col-sm-12 text-end">
                <div class="side-bar">
                    <div class="side-bar-box recent-post" x>
                        <h3 class="title">قصص النجاح</h3>
                        @foreach ($recent_services as $recent)
                            <div class="single-recent-post" dir="rtl">
                                <div class="d-flex justify-end">
                                    <!-- Image on the right -->
                                    <div class="recent-post-img" style="flex-shrink: 0;">
                                        <a href="{{ route('front.single_arabic_service', $recent->id) }}">
                                            <img src="{{ url('public/uploads/services/' . $recent->image) }}"
                                                alt="Image" class="w-100"
                                                style="max-width: 150px; border-radius: 5px;">
                                        </a>
                                    </div>

                                    <!-- Text content on the left -->
                                    <div class="recent-post-content p-3" style="text-align: left;">
                                        <h3 class="text-end mb-3">
                                            <a href="{{ route('front.single_arabic_service', $recent->id) }}"
                                                class="text-decoration-none">
                                                {!! Str::limit($recent->arabic_description, 60) !!}
                                            </a>
                                        </h3>

                                        <div class="d-flex justify-content-between text-nowrap flex-row-reverse">
                                            <!-- Date (on the right in RTL) -->
                                            <div class="d-flex align-items-center ms-3">
                                                <i class="las la-calendar ml-4"></i>
                                                <span>{{ arabicDiffForHumans($recent->created_at) }}</span>
                                            </div>

                                            <!-- Author (on the left in RTL) -->
                                            <div class="d-flex align-items-center">
                                                <i class="las la-user-alt ml-4"></i>
                                                <span>{!! optional($recent->user)->name ?? 'زائر' !!}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="side-bar-box categories-box">
                        <h3 class="title">خدمات</h3>
                        <ul>
                            {{-- <li><a href=""><i class="las la-angle-double-right"></i></i> News</a></li> --}}
                            @foreach ($services_name as $service)
                                <li>
                                    <a href="" class="d-flex justify-content-end align-items-center">
                                        <span>{{ $service->title_arabic }}</span>&nbsp;&nbsp;&nbsp;
                                        <i class="las la-angle-double-left"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="side-bar-box tags-box">
                        <h3 class="title">العلامات</h3>
                        <ul>
                            @foreach ($blog_tag as $tag)
                                <li><a href="">{{ $tag->arabic_tag }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Services Details Area Area -->

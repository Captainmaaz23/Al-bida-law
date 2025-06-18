<!-- Blog Area -->
@php
    use Illuminate\Support\Str;
@endphp
<div class="blog-area pt-100 pb-70">
    <div class="container">
        <div class="section-title">
            <span style="color: #131e3d !important; font-size:20px">Latest Blog</span>
            <h2 style="color: #131e3d !important;">Top <span style="color: #131e3d !important;">Blog</span> Related To
                Law, Cases & Consulting</h2>
        </div>
        <div class="row justify-content-center">
            @foreach ($blogs as $blog)
                <div class="col-lg-4 col-sm-6">
                    <a href="{{ url('public/uploads/blogs/' . $blog->image) }}" data-lightbox="blog-gallery">
                        <img src="{{ url('public/uploads/blogs/' . $blog->image) }}" alt="Not Found"
                            style="height: 80vh; object-fit: cover; width: 100%; object-position: center center; border-radius: 8px;" />
                    </a>

                    <div class="blog-card blogcardhome" style="height: 250px">

                        <div class="blog-card-text">
                            <h3>
                                <a href="javascript:void(0)">

                                    {!! $blog->english_title ?? '' !!}
                                </a>
                            </h3>

                            <div class="row mb-4">
                                <div class="col-6">
                                    <i class="las la-calendar"></i>
                                    {{ $blog->created_at->diffForHumans() }}
                                </div>
                                <div class="col-6">
                                    <i class="las la-user-alt"></i>
                                    {!! Auth::user()->name ?? 'Guest' !!}
                                </div>
                            </div>



                            <span style="color:white!important">
                                {!! Str::words($blog->description, 20, '...') !!}
                            </span>
                            <a href="{{ route('front.about', $blog->id) }}" class="read-more">Read More <i
                                    class="las la-angle-double-right"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

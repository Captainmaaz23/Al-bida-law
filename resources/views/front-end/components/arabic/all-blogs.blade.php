<!-- Page banner Area -->
<div class="page-banner bg-1">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="page-content">
                    <h2>مدونة</h2>
                    <ul>
                        <li><a href="index-2.html">بيت <i class="las la-angle-right"></i></a></li>
                        <li>مدونة</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page banner Area -->

<!-- News & Blog Area -->
<div class="blog-area ptb-100">
    <div class="container">
        <div class="row justify-content-center">
            @foreach ($blogs as $blog)
            <div class="col-4 text-end" dir="rtl">
                <div class="blog-card">
                    <a href="blog-details.html">
                        <img src=" {{ url('public/uploads/blogs/'.$blog->image) }}" style="width: 100%;height:60vh" alt="Image">
                    </a>
                    <div class="blog-card-text">
                        <h3><a href="blog-details.html">{{ Str::limit($blog->arabic_title,30) }}</a></h3>
                        <div class="row mb-4 justify-content-between text-nowrap" style="direction: rtl;">
                            <div class="col-auto ms-2">
                                <i class="las la-calendar"></i>
                                {{ arabicDiffForHumans($blog->created_at) }}
                            </div>
                            <div class="col-auto">
                                <i class="las la-user-alt"></i>
                                {!!optional($blog->user)->name ?? 'زائر' !!}
                            </div>
                        </div>

                        <p>{!! Str::limit($blog->arabic_description,60) !!}</p>

                        <a href="{{ route('front.single_arabic_blog',$blog->id) }}" class="read-more">
                            اقرأ المزيد <i class="las la-angle-double-left"></i>
                        </a>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
        <!-- Pagination -->
        <div class="col-lg-12 col-md-12">
            <ul class="pagination">
                @if ($blogs->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link" aria-hidden="true">&laquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $blogs->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                @endif
                @foreach ($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                    <li class="page-item {{ ($page == $blogs->currentPage()) ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach
                @if ($blogs->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $blogs->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link" aria-hidden="true">&raquo;</span>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- End News & Blog Area -->
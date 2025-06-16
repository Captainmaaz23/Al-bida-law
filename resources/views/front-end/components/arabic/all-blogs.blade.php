<!-- Page banner Area -->
<div class="page-banner bg-1">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="page-content">
                    <h2>Blog</h2>
                    <ul>
                        <li><a href="index-2.html">Home <i class="las la-angle-right"></i></a></li>
                        <li>Blog</li>
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
            <div class="col-4">
                <div class="blog-card">
                    <a href="blog-details.html">
                        <img src=" {{ url('public/uploads/blogs/'.$blog->image) }}" style="width: 100%;height:60vh" alt="Image">
                    </a>
                    <div class="blog-card-text">
                        <h3><a href="blog-details.html">{{$blog->name}}</a></h3>
                        <ul>
                            <li>
                                <i class="las la-calendar"></i>
                                {{ optional($blog->created_at)->format('h:i A') }}

                            </li>
                            <li>
                                <i class="las la-user-alt"></i>
                                {{ Auth::user()->name }}
                            </li>
                        </ul>

                        <p>{!! Str::limit($blog->description,20) !!}</p>

                        <a href="{{ route('front.about',$blog->id) }}" class="read-more">
                            Read More <i class="las la-angle-double-right"></i>
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
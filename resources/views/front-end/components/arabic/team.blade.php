
        <!-- Preloder Area -->
        <div class="preloader">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="lds-hourglass"></div>
                </div>
            </div>
        </div>
        <!-- Page banner Area -->
        <div class="page-banner bg-1">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="page-content">
                            <h2>Team</h2>
                            <ul>
                                <li><a href="index-2.html">Home <i class="las la-angle-right"></i></a></li>
                                <li>Team</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page banner Area -->

        <!-- Attorney Area -->
        <div class="attorney-area ptb-100">
            <div class="container">
                <div class="section-title">
                    <span style="color: #131e3d !important;">Experienced Team</span>
                    <h2 style="color: #131e3d !important;">محامينا ذوي الخبرة على استعداد للإجابة على أي أسئلة</h2>
                </div>  

                <div class="row justify-content-center">
                    @foreach ($teams as $team)
                    <div class="col-lg-4 col-sm-6">
                        <div class="attorney-card">
                            <a href="attorney-details.html">
                                <img src="{{ url('public/uploads/our-teams/',$team->image) }}" alt="Image" style="width:100%;height:70vh" class="img-fluid">
                            </a>
                            <div class="attorney-card-text">
                                <h3><a href="attorney-details.html">{{$team->arabic_name}}</a></h3>
                                <p>{{ $team->arabic_position }}</p>
                                <ul>
                                    <li>
                                        <a href="https://www.facebook.com/login/" target="_blank">
                                            <i class="lab la-facebook-f"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://twitter.com/i/flow/login" target="_blank">
                                            <i class="lab la-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/" target="_blank">
                                            <i class="lab la-instagram"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.google.co.uk/" target="_blank">
                                            <i class="lab la-google-plus"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    

                    <!-- Pagination -->
                    <div class="col-lg-12 col-md-12">
                        <ul class="pagination">
                            @if ($teams->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $teams->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif
                            @foreach ($teams->getUrlRange(1, $teams->lastPage()) as $page => $url)
                                <li class="page-item {{ ($page == $teams->currentPage()) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach
                            @if ($teams->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $teams->nextPageUrl() }}" aria-label="Next">
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
        </div>
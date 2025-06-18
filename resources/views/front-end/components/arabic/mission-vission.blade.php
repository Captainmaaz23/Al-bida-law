
        <div class="page-banner bg-1">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="page-content">
                            <h2>Vision - Mission</h2>
                            <ul>
                                <li><a href="">Home <i class="las la-angle-right"></i></a></li>
                                <li>Vision - Mission</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page banner Area -->

       <!-- Case Details Tab -->
        <div class="case-details-tab ptb-100">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6">
                        <div class="case-details-tab-item">
                            <h2>Our Study Process for this Case</h2>
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">رؤية</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">مهمة</a>
                                </li>
                                
                            </ul>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <h3>{{ $mission->arabic_title }}</h3>

                                    <p>{!! $mission->arabic_description !!}</p> 
                                    
                                </div>

                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <h3>{{ $vission->arabic_title }}</h3>
                                    <p>{!! $vission->arabic_description !!}</p> 
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="case-details-tab-item">
                            <div class="case-details-tab-img">
                                <img src="https://www.pngkey.com/png/full/57-576740_black-person-png-businessperson.png" alt="Image">
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        

        <!-- Page banner Area -->
        <div class="page-banner bg-1">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="page-content">
                            <h2>FAQ</h2>
                            <ul>
                                <li><a href="index-2.html">Home <i class="las la-angle-right"></i></a></li>
                                <li>FAQ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page banner Area -->

        <!-- Start FAQ Area -->
        <div class="faq-area ptb-100">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-7 col-md-12">
                        <div class="faq-accordion">
                            <ul class="accordion">
                                <ul class="accordion">
                                    @if($faq && $faq->count())
                                        @foreach ($faq as $index => $faqs)
                                            <li class="accordion-item">
                                                <a class="accordion-title {{ $index == 0 ? 'active' : '' }}" href="javascript:void(0)">
                                                    <i class="las la-plus"></i>
                                                    {{ strip_tags($faqs->english_question) }}
                                                </a>
                                
                                                <div class="accordion-content {{ $index == 0 ? 'show' : '' }}">
                                                    {!! $faqs->english_description !!}
                                                </div>
                                            </li>
                                        @endforeach
                                    @else
                                        <li>No FAQs found.</li>
                                    @endif
                                </ul>
                                
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-12">
                        <div class="faq-image">
                            <img src="https://media.istockphoto.com/id/155371886/photo/white-chess-king-among-lying-down-black-pawns-on-chessboard.jpg?s=612x612&w=0&k=20&c=iXmkS5nCdqI2Usi6APnUFuFppEtTLcrPtJTMwcWxtC8=" alt="image">
                        </div>
                    </div>
                </div>

                
                
                <div class="contact-form">
                    <form id="contactForm">
                        <div class="section-title">
                            <h2>Frequently Asked Questions</h2>  
                        </div> 
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" id="name" required data-error="Please enter your name" placeholder="Your Name">
                                    <div class="help-block with-errors"></div>
                                    <i class="las la-user"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" id="email" required data-error="Please enter your email" placeholder="Email Address">
                                    <div class="help-block with-errors"></div>
                                    <i class="las la-envelope"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="email" class="form-control" id="Phone" required data-error="Please enter your phone" placeholder="Your Phone">
                                    <div class="help-block with-errors"></div>
                                    <i class="las la-phone"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="email" class="form-control" id="subject" required data-error="Please enter your subject" placeholder="Your subject">
                                    <div class="help-block with-errors"></div>
                                    <i class="las la-id-card"></i>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <textarea name="message" id="message" class="form-control" cols="30" rows="6" required data-error="Please enter your message" placeholder="Write your message..."></textarea>
                                    <div class="help-block with-errors"></div>
                                    <i class="las la-sms"></i>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <button type="submit" class="default-btn-one">Get An Appointment</button>
                                <div id="msgSubmit" class="h3 text-center hidden"></div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End FAQ Area -->
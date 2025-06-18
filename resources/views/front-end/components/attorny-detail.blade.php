
        <!-- Page banner Area -->
        <div class="page-banner bg-1">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="page-content">
                            <h2>Attorney Details</h2>
                            <ul>
                                <li><a href="index-2.html">Home <i class="las la-angle-right"></i></a></li>
                                <li>Attorney Details</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page banner Area -->

        <!-- Attorney Details -->
        <div class="attorney-details pt-100 pb-70">
            <div class="container faq-area">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-5">
                        <div class="attor-details-item">
                            <img src="{{ url('public/uploads/our-teams/'.$team->image) }}" style="width:100%;height:70vh" alt="Image">
                            <div class="attor-details-left">
                                <div class="attor-social">
                                    <ul>
                                        <li>
                                            <a href="https://www.facebook.com/" target="_blank">
                                                <i class="lab la-facebook-f"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://www.instagram.com/" target="_blank">
                                                <i class="lab la-instagram"></i>
                                        </li>
                                        <li>
                                            <a href="https://www.twitter.com/" target="_blank">
                                                <i class="lab la-twitter"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://www.linkedin.com/" target="_blank">
                                                <i class="lab la-linkedin-in"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="attor-social-details">
                                    <h3>Contact info</h3>
                                    <ul>
                                        <li>
                                            <i class="las la-phone-volume"></i>
                                            <a href="tel:+07554332322">
                                                Call : {{ optional($team->user)->phone }}
                                            </a>
                                        </li>
                                        <li>
                                            <i class="las la-envelope"></i>
                                            <a href="https://templates.hibootstrap.com/cdn-cgi/l/email-protection#127a777e7e7d527e6b687d3c717d7f">
                                                <span class="__cf_email__" data-cfemail="e189848d8d8ea180958e938fcf828e8c">{{ optional($team->user)->email }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="las la-map-marker"></i>
                                            {{ optional($team->user)->address }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="attor-work">
                                    <h3>Working hours</h3>
                                    <div class="attor-work-left">
                                        <ul>
                                            <li>Monday</li>
                                            <li>Tuesday</li>
                                            <li>Sunday</li>
                                        </ul>
                                    </div>
                                    <div class="attor-work-right">
                                        <ul>
                                            <li>9:00 am - 8:00 pm </li>
                                            <li>9:00 am - 8:00 pm </li>
                                            <li>9:00 am - 8:00 pm </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="attor-prev">
                                <ul>
                                    <li>
                                        <a href="#">Previous</a>
                                    </li>
                                    <li>
                                        <a href="#">Next</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="attor-details-item">
                            <div class="attor-details-right">
                                <div class="attor-details-name">
                                    <h2>{{ $team->name }}</h2>
                                    <span>{{ $team->title }}</span>
                                    <p>{{ $team->bio }}</p>
                                </div>
                                <div class="attor-details-things">
                                    <h3>Biography</h3>
                                    <p>{{ $team->position }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contact-form">
                    <form id="contactForm">
                        <div class="section-title">
                            <h2>Get Appointment</h2>  
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
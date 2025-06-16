<!-- Contact Area -->
<div class="contact-area ptb-100">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-6">
                <div class="contact-text">
                    <div class="section-title">
                        <h2>Contact Us Today, Get In Touch With Expert</h2>
                        <p>Discover your legal options with our Free Case Evaluation. Our expert team will assess your situation and provide tailored guidance to help you navigate your legal challenges effectively.</p>   
                    </div> 

                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="contact-card">
                                <span>Phone Number</span>
                                <h3><a href="tel:+0123456987">+0123 456 987</a></h3>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="contact-card">
                                <span>Our Social Link</span>
                                <ul>
                                    <li>
                                        <a href="javascript:void(0)" target="_blank">
                                            <i class="lab la-facebook-f"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" target="_blank">
                                            <i class="lab la-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" target="_blank">
                                            <i class="lab la-instagram"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" target="_blank">
                                            <i class="lab la-google-plus"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="section-title">
                    <span>Contact Form</span>
                    <h2>Free Case Evaluation</h2>  
                </div> 
                <div class="contact-form">
                    <form method="post" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="fullname" class="form-control" style="background-color:gray"  id="name" required placeholder="Full name">
                                    <i class="las la-user"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" id="email" required placeholder="Email address" style="background-color:gray">
                                    <i class="las la-envelope"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="phone" class="form-control" id="Phone" required placeholder="Phone No" style="background-color:gray">
                                    <i class="las la-phone"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="date" name="date" class="form-control" id="date" style="background-color:gray">
                                    <i class="las la-calendar"></i>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <textarea name="message" id="message" class="form-control" cols="30" rows="6" required placeholder="Write your message..." style="background-color:gray"></textarea>
                                    <i class="las la-sms"></i>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <button type="submit" class="default-btn-one">Get An Appointment</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Contact Area -->
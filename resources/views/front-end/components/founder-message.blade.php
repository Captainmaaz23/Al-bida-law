<!-- Page banner Area -->
<div class="page-banner bg-1">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="page-content">
                    <h2>Founder Message</h2>
                    <ul>
                        <li><a href="">Home <i class="las la-angle-right"></i></a></li>
                        <li>Founder Message</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page banner Area -->



<div class="attorney-details pt-100 pb-70">
    <div class="container faq-area">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-5">
                <div class="attor-details-item">
                    <img src="{{ url('public/uploads/founders/'.$founder->image) }}" alt="Image">
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
                            <h4>{{ $founder->founder_name }} </h4>
                            <h6>{{ $founder->designation }} </h6>
                            <h6>Al Bidda Law Firm</h6>
                            <br/>
                            <ul>
                                <li>
                                    <i class="las la-phone-volume"></i>
                                    <a href="tel:+07554332322">
                                        {{ optional($founder->user)->phone }}
                                    </a>
                                </li>
                                <li>
                                    <i class="las la-envelope"></i>
                                    <a href="https://templates.hibootstrap.com/cdn-cgi/l/email-protection#127a777e7e7d527e6b687d3c717d7f">
                                        <span class="__cf_email__" data-cfemail="e189848d8d8ea180958e938fcf828e8c">{{ optional($founder->user)->email }}</span>
                                    </a>
                                </li>
                                <li>
                                    <i class="las la-map-marker"></i>
                                    {{ optional($founder->user)->address }}
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
                    
                </div>
            </div>
            <div class="col-lg-7">
                <div class="attor-details-item">
                    <div class="attor-details-right">
                        
                        <div class="attor-details-things">
                            <h3>Dear Clients and Partners,</h3>
                            <p>{{ $founder->message }}</p>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
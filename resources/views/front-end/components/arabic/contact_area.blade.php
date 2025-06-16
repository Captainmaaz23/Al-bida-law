<!-- Contact Area -->
<div class="contact-area ptb-100">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-6">
                <div class="contact-text">
                    <div class="section-title">
                        <h2 dir="rtl">اتصل بنا اليوم، وتواصل مع الخبراء</h2>
                        <p dir="rtl">اكتشف خياراتك القانونية مع تقييمنا المجاني لقضيتك. سيقوم فريقنا من الخبراء بتقييم وضعك وتقديم إرشادات مُخصصة لمساعدتك في التعامل مع تحدياتك القانونية بفعالية.</p>   
                    </div> 

                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="contact-card">
                                <span dir="rtl">رقم التليفون</span>
                                <h3><a href="tel:+0123456987">+0123 456 987</a></h3>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="contact-card">
                                <span dir="rtl" class="pr-3">رابطنا الاجتماعي</span>
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
                <div class="section-title" style="padding-left: 20px">
                    <span>نموذج الاتصال</span>
                    <h2>تقييم الحالة مجانا</h2>  
                </div> 
                <div class="contact-form">
                    <form method="post" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="fullname" class="form-control" style="background-color:gray"  id="name" required placeholder="الاسم الكامل" dir="rtl">
                                    <i class="las la-user"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" id="email" required placeholder="عنوان البريد الإلكتروني" style="background-color:gray" dir="rtl">
                                    <i class="las la-envelope"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="phone" class="form-control" id="Phone" required placeholder="رقم الهاتف" style="background-color:gray" dir="rtl">
                                    <i class="las la-phone"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="date" name="date" class="form-control" id="date" style="background-color:gray" dir="ltr">
                                    <i class="las la-calendar"></i>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <textarea name="message" id="message" class="form-control" cols="30" rows="6" required placeholder="أكتب رسالتك..." style="background-color:gray" dir="rtl"></textarea>
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
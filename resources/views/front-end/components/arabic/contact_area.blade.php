<!-- Contact Area -->
<div class="contact-area ptb-100">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-6">
                <div class="contact-text">
                    <div class="section-title">
                        <h2 dir="rtl">اتصل بنا اليوم، وتواصل مع الخبراء</h2>
                        <p dir="rtl" style="font-size:16px">اكتشف خياراتك القانونية مع تقييمنا المجاني لقضيتك. سيقوم
                            فريقنا
                            من الخبراء
                            بتقييم وضعك وتقديم إرشادات مُخصصة لمساعدتك في التعامل مع تحدياتك القانونية بفعالية.</p>
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
                    <span style="font-weight: bold; font-size:24px">نموذج الاتصال</span>
                    <h2>تقييم الحالة مجانا</h2>
                </div>
                <div class="contact-form">
                    <form method="post" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="row justify-content-center">


                            <div class="col-md-6">
                                <div class="form-group" dir="rtl">
                                    <div class="form-group position-relative" dir="rtl">
                                        <input type="email" name="email" class="form-control" id="email"
                                            style="background-color:gray; font-size:16px;  padding-right: 70px;"
                                            required placeholder="عنوان البريد الإلكتروني" dir="rtl">
                                        <i class="las la-envelope position-absolute"
                                            style="right:0; top: 50%; transform: translateY(-50%);"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group" dir="rtl">
                                    <div class="form-group position-relative" dir="rtl">
                                        <input type="text" name="fullname" class="form-control"
                                            style="background-color: gray; font-size:16px; padding-right: 70px;"
                                            id="name" required placeholder="الاسم الكامل" dir="rtl">
                                        <i class="las la-user position-absolute"
                                            style="right:0; top: 50%; transform: translateY(-50%);"></i>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group" dir="rtl">
                                    <div class="form-group position-relative" dir="rtl">
                                        <input type="text" name="phone" class="form-control" id="Phone"
                                            required placeholder="رقم الهاتف"
                                            style="background-color: gray; font-size:16px; padding-right: 70px;"
                                            dir="rtl">
                                        <i class="las la-phone position-absolute"
                                            style="right:0; top: 50%; transform: translateY(-50%);"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group" dir="rtl">
                                    <div class="form-group
                                    position-relative"
                                        dir="rtl">
                                        <!-- Hidden real date input -->
                                        <input type="date" id="real-date"
                                            style="opacity: 0; position: absolute; width: 100%; height: 100%; cursor: pointer;">

                                        <!-- Visible fake input -->
                                        <input type="text" id="fake-date" class="form-control"
                                            style="background-color: gray; font-size:16px;  padding-right: 70px; "
                                            placeholder="يوم/شهر/سنة" dir="rtl" readonly>
                                        <i class="las la-calendar position-absolute"
                                            style="right:0; top: 50%; transform: translateY(-50%);"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <div class="form-group" dir="rtl">
                                    <div class="form-group position-relative" dir="rtl">
                                        <textarea name="message" id="message" class="form-control" cols="30" rows="6" required
                                            placeholder="أكتب رسالتك..." style="background-color:gray; font-size:16px; padding-right: 70px;" dir="rtl"></textarea>
                                        <i class="las la-sms position-absolute"
                                            style="right:0; top: 16%; transform: translateY(-50%);"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <button type="submit" class="default-btn-one"> احصل على موعد</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Contact Area -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const realDate = document.getElementById('real-date');
        const fakeDate = document.getElementById('fake-date');

        realDate.addEventListener('change', function() {
            if (this.value) {
                const [year, month, day] = this.value.split('-');
                // Convert to Arabic numerals in يوم/شهر/سنة format
                fakeDate.value = `${toArabicNum(year)}/${toArabicNum(month)}/${toArabicNum(day)}`;
            }
        });

        fakeDate.addEventListener('click', function() {
            realDate.showPicker();
        });

        // Convert numbers to Arabic numerals
        function toArabicNum(num) {
            const arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            return num.toString().split('').map(d => arabicNumerals[parseInt(d)]).join('');
        }
    });
</script>

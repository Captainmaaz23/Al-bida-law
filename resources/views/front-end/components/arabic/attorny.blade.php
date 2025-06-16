<!-- Attorney Area -->
<div class="attorney-area pt-100 pb-70 backcolor">
    <div class="container">
        <div class="section-title">
            <span>محامي ذو خبرة</span>
            <h2 data="rtl">محامينا ذوي الخبرة على استعداد للإجابة على أي أسئلة</h2>
        </div>  

        <div class="row justify-content-center">

            @foreach ($experienced_attorny as $attorny)
                <div class="col-lg-4 col-sm-6">
                    <div class="attorney-card attcardhome">
                        <a href="javascript:void(0)">
                            <img 
                                src="{{ url('public/uploads/case-study/' . $attorny->image) }}" 
                                alt="Not Found"
                                style="height: 80vh; object-fit: cover; width: 100%; object-position: center center; "
                            />
                        </a>
                        <div class="attorney-card-text">
                            <h3 class="text-center" data="rtl"><a href="javascript:void(0)" >{{ Str::limit($attorny->arabic_client, 25) }}</a></h3>
                            <p class="text-end" dir="rtl">{{ Str::limit($attorny->arabic_attorny,30) }}</p>
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
            @endforeach

        </div>

    </div>
</div>
<!-- End Attorney Area -->
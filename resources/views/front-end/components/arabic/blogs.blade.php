<!-- Blog Area -->
@php
    use Illuminate\Support\Str;
@endphp
<div class="blog-area pt-100 pb-70">
    <div class="container">
        <div class="section-title">
            <span style="font-weight:bold; font-size:24px; color: #131e3d !important;">أحدث مدونة</span>
            <h2 style="color: #131e3d !important;">قمة <span style="color: #131e3d !important;">مدونة</span> متعلق
                بالقانون والقضايا والاستشارات</h2>
        </div>
        <div class="row justify-content-center">
            @foreach ($blogs as $blog)
                <div class="col-lg-4 col-sm-6">
                    <a href="{{ url('public/uploads/blogs/' . $blog->image) }}" data-lightbox="blog-gallery">
                        <img src="{{ url('public/uploads/blogs/' . $blog->image) }}" alt="Not Found"
                            style="height: 80vh; object-fit: cover; width: 100%; object-position: center center; border-radius: 8px;" />
                    </a>

                    <div class="blog-card blogcardhome" style="height: 250px">

                        <div class="blog-card-text">
                            <h3 class="text-end" style="direction: rtl;">
                                <a href="javascript:void(0)">
                                    {!! Str::limit($blog->arabic_title, 30) !!}
                                </a>
                            </h3>

                            <div class="row mb-4 justify-content-between text-nowrap"
                                style="direction: rtl; font-family: 'Tahoma', 'Arial Arabic', sans-serif;">
                                <div class="col-auto ms-2">
                                    <i class="las la-calendar"></i>
                                    @php
                                        // Convert date to Arabic format with Arabic numerals
                                        $arabicDate = $blog->created_at->locale('ar')->diffForHumans();

                                        // Convert Western numerals to Arabic-Indic
                                        $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
                                        $arabicDate = preg_replace_callback(
                                            '/[0-9]/',
                                            function ($matches) use ($arabicNumerals) {
                                                return $arabicNumerals[$matches[0]];
                                            },
                                            $arabicDate,
                                        );

                                        // Manual translations
                                        $translations = [
                                            'second' => 'ثانية',
                                            'minute' => 'دقيقة',
                                            'hour' => 'ساعة',
                                            'day' => 'يوم',
                                            'week' => 'أسبوع',
                                            'month' => 'شهر',
                                            'year' => 'سنة',
                                            'ago' => 'منذ',
                                            'from now' => 'من الآن',
                                            'seconds' => 'ثوانٍ',
                                            'minutes' => 'دقائق',
                                            'hours' => 'ساعات',
                                            'days' => 'أيام',
                                            'weeks' => 'أسابيع',
                                            'months' => 'شهور',
                                            'years' => 'سنوات',
                                        ];

                                        foreach ($translations as $en => $ar) {
                                            $arabicDate = str_replace($en, $ar, $arabicDate);
                                        }

                                        echo $arabicDate;
                                    @endphp
                                </div>
                                <div class="col-auto">
                                    <i class="las la-user-alt"></i>
                                    {!! Auth::user()->name ?? 'زائر' !!}
                                </div>
                            </div>

                            <p class="text-start" dir="rtl" class="text-end">
                                {!! Str::limit($blog->arabic_description, 60) !!}
                            </p>

                            <a href="{{ route('front.single_arabic_blog', $blog->id) }}" class="read-more d-flex text-end" dir="rtl">
                                اقرأ المزيد
                                <i class="las la-angle-double-left mt-1"></i>
                            </a>

                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

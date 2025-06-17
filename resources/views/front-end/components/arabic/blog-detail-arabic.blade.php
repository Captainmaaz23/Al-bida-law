 <!-- Page banner Area -->
 <div class="page-banner bg-1">
     <div class="d-table">
         <div class="d-table-cell">
             <div class="container">
                 <div class="page-content">
                     <h2>تفاصيل المدونة</h2>
                     <ul>
                         <li><a href="">بيت <i class="las la-angle-right"></i></a></li>
                         <li>تفاصيل المدونة</li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- End Page banner Area -->

 <!-- Start Services Details Area -->
 <div class="services-details-area ptb-100">
     <div class="container">
         <div class="row justify-content-center">
             <div class="col-lg-8 col-md-7 col-sm-12 text-end" dir="rtl">
                 <div class="services-details">
                     <div class="img">
                         <img src="{{ url('public/uploads/blogs/' . $blogs->image) }}" alt="Image">
                     </div>
                     <div class="services-details-content">
                         <h3>{{ $blogs->arabic_title }}</h3>
                         <ul class="blog-list">
                             <li>
                                 <i class="las la-calendar"></i>
                                 {{ $blogs->created_at->format('h:i A') }}
                             </li>
                             <li>
                                 <i class="las la-user-tie"></i>
                                 <a href="javascript:void(0)">{{ optional($blogs->user)->name }}</a>
                             </li>
                         </ul>
                         <div style="text-align: justify; line-height: 2;">
                             {!! $blogs->arabic_description !!}
                         </div>


                         {{-- <blockquote class="blockquote">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                            <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                        </blockquote> --}}
                     </div>

                 </div>
             </div>
             <div class="col-lg-4 col-md-5 col-sm-12 text-end" dir="rtl">
                 <div class="side-bar">
                     <div class="side-bar-box recent-post">
                         <h3 class="title">المشاركة الأخيرة</h3>
                         @foreach ($recent_blogs as $recent)
                             <div class="single-recent-post" dir="rtl">
                                 <div class="d-flex justify-content-end">
                                     <!-- Image on the right (visual left in RTL) -->
                                     <div class="recent-post-img" style="flex-shrink: 0;">
                                         <a href="{{ route('front.single_arabic_blog', $recent->id) }}">
                                             <img src="{{ url('public/uploads/blogs/' . $recent->image) }}"
                                                 alt="Image" class="w-100"
                                                 style="max-width: 150px; border-radius: 5px;">
                                         </a>
                                     </div>

                                     <!-- Text content on the left (visual right in RTL) -->
                                     <div class="recent-post-content p-3" style="text-align: right;">
                                         <h3 class="mb-3">
                                             <a href="{{ route('front.single_arabic_blog', $recent->id) }}"
                                                 class="text-decoration-none">
                                                 {{ $recent->arabic_title }}
                                             </a>
                                         </h3>

                                         <div class="d-flex justify-content-between text-nowrap">
                                             <!-- Date first (right side in RTL) -->
                                             <div class="d-flex align-items-center ms-3">
                                                 <i class="las la-calendar ms-2"></i>
                                                 <span>{{ arabicDiffForHumans($recent->created_at) }}</span>
                                             </div>

                                             <!-- Author second (left side in RTL) -->
                                             <div class="d-flex align-items-center">
                                                 <i class="las la-user-alt ms-2"></i>
                                                 <span>{!! optional($recent->user)->name ?? 'زائر' !!}</span>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         @endforeach
                     </div>

                     <div class="side-bar-box categories-box">
                         <h3 class="title">فئات</h3>
                         <ul>
                             @foreach ($services as $service)
                                 <li><a href="{{ route('front.single_arabic_blog', $recent->id) }}"><i
                                             class="las la-angle-double-left"></i></i> {{ $service->title_arabic }}
                                     </a></li>
                             @endforeach
                         </ul>
                     </div>

                     <div class="side-bar-box tags-box">
                         <h3 class="title">العلامات</h3>
                         <ul>
                             @foreach ($tags as $tag)
                                 <li><a href="javascript:void(0)">{{ $tag->arabic_tag }}</a></li>
                             @endforeach
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- End Services Details Area Area -->

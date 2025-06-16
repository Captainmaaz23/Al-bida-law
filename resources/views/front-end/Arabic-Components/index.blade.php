
        <div class="preloader">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="lds-hourglass"></div>
                </div>
            </div>
        </div>
        <!-- End Preloder Area -->

        
        

        

         <!-- Service Area -->
        
        <!-- End Service Area -->

<!-- Choose Area -->
{{-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('front-end.layout.head')
    @section('title' ,'KING')
</head> --}}

    @extends('front-end.layout.arabic_app')

    @section('title','Umer Farooq')

    @section('content')
        
    @include('front-end.components.arabic.slidder')
    @include('front-end.components.arabic.about_albida')
    
    @include('front-end.components.arabic.why_choose_use')
    @include('front-end.components.arabic.testimonials')
    @include('front-end.components.arabic.case_study')
    @include('front-end.components.arabic.contact_area')
    @include('front-end.components.arabic.attorny')
    @include('front-end.components.arabic.blogs')
       

        @endsection
       
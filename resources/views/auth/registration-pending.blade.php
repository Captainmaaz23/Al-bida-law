@extends('layouts.guest')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
            <div class="content">                
                <div class="block block-rounded block-themed">

                    <div class="block-header">
                        <h3 class="block-title">Registration Request received.</h3>
                    </div>

                    <div class="block-content text-center">

                        <i class="far fa-check-circle display-2 my-3 text-success"></i>
                        <p>Dear seller, thank you for showing interest and signing up on our platform. Your request has been received and is currently under review. One of our executives will contact you soon for onboarding.</p>

                        <div class="mb-4 text-muted">
                            Already onBoard? <a href="{{ url('/login') }}">Login</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.front')
@section('title', 'Verify Email Address')

@section('content')
<div id="intro" class="jarallax" data-speed="0.5" style="background-image: url({{ asset('theme') }}/img/intro_img/1.jpg);">
    <div class="grid grid--container">
        <div class="row row--xs-middle">
            <div class="col col--lg-8 ">
                <div class="card">
                    <div class="card-header">
                        <h3 style="color: #fff;">{{ __('Verify Your Email Address') }}</h3>
                    </div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="custom-btn custom-btn--medium custom-btn--style-2">{{ __('click here to request another') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

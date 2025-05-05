@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-center" style="background-color: #800020; color: white; border-top-left-radius: 1rem; border-top-right-radius: 1rem; padding: 1.5rem;">
                    <h4 class="fw-bold mb-0">{{ __('Verify Your Email Address') }}</h4>
                </div>

                <div class="card-body p-5">
                    @if (session('resent'))
                        <div class="alert alert-success text-center" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <p class="mb-3 text-center" style="color: #800020;">
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                    </p>
                    <p class="mb-4 text-center" style="color: #800020;">
                        {{ __('If you did not receive the email') }},
                    </p>

                    <form class="text-center" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn" style="background-color: #806000; color: white;">
                            {{ __('Click here to request another') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

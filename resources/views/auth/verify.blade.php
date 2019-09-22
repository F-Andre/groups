@extends('template')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('users.Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('users.A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('users.Before proceeding, please check your email for a verification link.') }}
                    {{ __('users.If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('users.click here to request another') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

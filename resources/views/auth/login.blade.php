@extends('layouts.main')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-mobile">
            <div class="column is-half-desktop is-offset-one-quarter-desktop is-12-mobile">
                <p class="tertiary-heading white bold">Login</p>
                <p class="body grey3 margin-top-16">Don't have an account yet?</p>
                <h1 href="/register">Signup</h1>
                <form class="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <label class="label">Email</label>
                    <p class="control">
                        <input id="email" name="email" class="input" type="email" placeholder="customer@gmail.com" value="{{ old('email') }}" required autofocus> 
                    </p>
                    @if ($errors->has('email'))
                        <p{{ $errors->first('email') }}</p>
                    @endif

                    <label class="label" for="email">Password</label>
                    <p class="control">
                        <input class="input" type="password" placeholder="••••••••" required>
                    </p>
                    @if ($errors->has('password'))
                        <p{{ $errors->first('password') }}</p>
                    @endif

                    <button class="button is-primary">Login</button>
                </form>
                <a href="{{ route('password.request') }}">Need to reset your password? No problem!</a>
            </div>
        </div>
    </div>
</section>
@endsection

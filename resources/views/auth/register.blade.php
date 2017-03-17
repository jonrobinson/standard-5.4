@extends('layouts.main')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-mobile">
            <div class="column is-half-desktop is-offset-one-quarter-desktop is-12-mobile">
                <p class="tertiary-heading white bold">Login</p>
                <p class="body grey3 margin-top-16">Don't have an account yet?</p>
                <h1 href="/register">Signup</h1>
                <form class="form" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    <label class="label">First Name</label>
                    <p class="control">
                        <input id="first_name" name="first_name" class="input" type="text" placeholder="First name" value="{{ old('first_name') }}" required autofocus> 
                    </p>
                    @if ($errors->has('first_name'))
                        <p>{{ $errors->first('first_name') }}</p>
                    @endif

                    <label class="label">Last Name</label>
                    <p class="control">
                        <input id="last_name" name="last_name" class="input" type="text" placeholder="Last name" value="{{ old('last_name') }}" required autofocus> 
                    </p>
                    @if ($errors->has('last_name'))
                        <p>{{ $errors->first('last_name') }}</p>
                    @endif

                    <label class="label">Email</label>
                    <p class="control">
                        <input id="email" name="email" class="input" type="email" placeholder="customer@gmail.com" value="{{ old('email') }}" required autofocus> 
                    </p>
                    @if ($errors->has('email'))
                        <p>{{ $errors->first('email') }}</p>
                    @endif

                    <label class="label" for="email">Password</label>
                    <p class="control">
                        <input id="password" name="password" class="input" type="password" placeholder="••••••••" required>
                    </p>
                    @if ($errors->has('password'))
                        <p>{{ $errors->first('password') }}</p>
                    @endif

                    <button class="button is-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

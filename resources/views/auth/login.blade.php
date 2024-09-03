@extends('layouts.auth.app')

@section('content')
    <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
        <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
        <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

        <div class="card bg-glass">
            <div class="card-body px-7 py-5 px-md-4 bg-light">

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 mb-2 mx-auto">
                            <p1>Welcome!</p1>
                            <br>
                            <p style="color:#245953">
                                Please log in your correct credentials.
                            </p>
                            <!-- Email input -->
                            <div class="form-outline mb-3">
                                <input id="username" type="text"
                                    class="form-control @error('username') is-invalid @enderror" name="username"
                                    value="{{ old('username') }}" placeholder="username" autocomplete="username" autofocus
                                    @required(true)>

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <p>{{ $message }}</p>
                                </span>
                                @enderror
                            </div>
                            <!-- Password input -->
                            <div class="form-outline mb-1">
                                <div class="input-group mb-3">
                                    <input id="password" type="password"
                                        class="form-control show-password rounded @error('password') is-invalid @enderror"
                                        name="password" placeholder="Password" autocomplete="current-password"
                                        @required(true)>
                                    <span class="input-group-text rounded bg-white ml-1 toggle-password">
                                        <a href="javascript:void(0)" class="text-dark">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </span>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <p>{{ $message }}</p>
                                    </span>
                                @enderror
                            </div>

                            <!-- Remember me -->
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label mb-0 ms-2" for="remember">
                                            <small>Remember Me</small>
                                        </label>
                                    </div>
                                </div>
                                @if (settings()->get('is_forgot_password') == true ?? false)
                                <div class="col-md-6 text-md-end">
                                    <a href="{{ route('password.request') }}">
                                        <small>Forgot Password?</small>
                                    </a>
                                </div>
                                @endif
                            </div>


                            <!-- Submit button -->
                            <div class="d-flex justify-content-around align-items-center mb-2">
                                <button type="submit" class="btn btn-info btn-block">
                                    Log In
                                </button>
                            </div>

                            <!-- register -->
                            @if (settings()->get('is_register') == true ?? false)
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <a href="{{ route('register') }}">
                                            Don't Have An Account?
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

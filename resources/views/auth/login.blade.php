@extends('layouts.app')

@section('content')
<div class="container">
    <br><br>
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <!-- Login form -->
            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
                    <label for="email" class="col-2 col-form-label">Email</label>
                    <div class="col-10">                        
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                            <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                    <label for="password" class="col-2 col-form-label">Password</label>
                    <div class="col-10">
                        <input id="password" type="password" class="form-control" name="password" required>
                        @if ($errors->has('password'))
                            <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-2"></div>
                    <div class="col-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck1" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="gridCheck1">
                            Remember me
                            </label>          
                        </div>
                    </div>
                    <div class="col-7">
                        <a class="float-left" href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a> 
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Login</button>                        
                    </div>
                    <div class="col-4"></div>                 
                </div>
                <div class="form-group row">
                    <div class="col-4"></div>
                    <div class="col-4">
                                             
                    </div>
                    <div class="col-4"></div>                 
                </div>
            </form>
        </div>
        <div class="col-2"></div>
    </div>                    
</div>
@endsection
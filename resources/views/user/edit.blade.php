@extends('layouts.app')

@section('content')

<div class="container">
    <br><br>
    <form action="{{route('user.update', $user->id)}}" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="" 
                @if ($user->name)
                    value="{{$user->name}}"
                @endif
            >
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" class="form-control " id="email" disabled placeholder="" 
                @if ($user->email)
                    value="{{$user->email}}"
                @endif
            >
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" class="form-control" id="location" placeholder="Ex: 178 ABC Street, Hanoi" 
            @if ($user->location)
                value="{{$user->location}}"
            @endif
            >
            @if ($errors->has('location'))
                <span class="help-block">
                    <strong>{{ $errors->first('location') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" class="form-control" id="phone" placeholder="Ex: 01257139514" aria-describedby="phoneHelper" 
            @if ($user->phone)
                value="{{$user->phone}}"
            @endif>
            @if ($errors->has('phone'))
                <span class="help-block">
                    <strong>{{ $errors->first('phone') }}</strong>
                </span>
            @endif
            <small id="phoneHelper" class="form-text text-muted">
                Phone must start with/without + and contains only 9 to 15 numerical characters.
            </small>
        </div>
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <button type="submit" class="btn btn-primary">Change Information</button>
    </form>
</div>
<script>
    @if (isset($status) && $status)
        alert('Changed successfully!!');
    @endif
</script>
@endsection
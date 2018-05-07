@extends('layouts.app')

@section('content')
<div class="container">
  <br><br>
  @foreach ($users as $index=>$user)
    <div class="card card-body">
      <div class="row">
        @if ($user->profile_image)
        <img style="max-height:260px" class="col-4 profileImage" src="{{ asset('storage/user_profile/profileimgs/'. $user->profile_image) }}" alt="">
        @else
        <img class="col-4 profileImage" src="{{ asset('storage/dogs/noimage.jpg') }}" alt="">
        @endif
        <div class="col-8">
          <a href="{{ route('user.show', ['id'=>$user->id]) }}">{{ $user->name }}</a>
          @if (isset($user->email) || $user->email != null)
            <p>{{ $user->email }}</p>
          @endif
          @if (isset($user->phone) || $user->phone != null)
            <p>{{ $user->phone }}</p>
          @endif
          @if (isset($user->location) || $user->location != null)
            <p>{{ $user->location }}</p>
          @endif
        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection

@section('script')

@endsection
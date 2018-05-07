@extends('layouts.app')

@section('content')
<div class="container">
  <br><br>
  @foreach ($dogs as $index=>$dog)
    <div class="card card-body">
      <div class="row">
        @if ($dog->profile_image)
        <img style="max-height:260px" class="col-4 profileImage" src="{{ asset('storage/'. $dog->profile_image) }}" alt="">
        @else
        <img class="col-4 profileImage" src="{{ asset('storage/dogs/noimage.jpg') }}" alt="">
        @endif
        <div class="col-8">
          <a href="{{ route('dog.show', ['id'=>$dog->id]) }}">{{ $dog->name }}</a>
          @if (isset($user->breed) || $dog->breed != null)
            <p>{{ $dog->breed }}</p>
          @endif
          @if (isset($dog->gender) || $dog->gender != null)
            <p>{{ $dog->gender }}</p>
          @endif
        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection

@section('script')

@endsection
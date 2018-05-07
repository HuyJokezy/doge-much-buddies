@extends('layouts.app')

@section('content')
<div class="container">
  <br><br>
  @for ($i = 0; $i < count($friends); $i++)
    @if ($i % 2 == 0)
      <div class="row">
        <div class="col-6">
          <div class="card card-body">
            <div class="row">
              @if ($friends[$i]->profile_image)
              <img class="col-3 profileImage" src="{{ asset('storage/'. $friends[$i]->profile_image) }}" alt="">
              @else
              <img class="col-3 profileImage" src="{{ asset('storage/dogs/noimage.jpg') }}" alt="">
              @endif
              <div class="col-9">
                <a href="{{ route('user.show', ['id'=>$friends[$i]->id]) }}">{{ $friends[$i]->name }}</a>
              </div>
            </div>
          </div>
        </div>
        @if ($i < (count($friends) - 1))
          <div class="col-6">
            <div class="card card-body">
              <div class="row">
                @if ($friends[$i + 1]->profile_image)
                <img class="col-3 profileImage" src="{{ asset('storage/'. $friends[$i + 1]->profile_image) }}" alt="">
                @else
                <img class="col-3 profileImage" src="{{ asset('storage/dogs/noimage.jpg') }}" alt="">
                @endif
                <div class="col-9">
                  <a href="{{ route('user.show', ['id'=>$friends[$i + 1]->id]) }}">{{ $friends[$i + 1]->name }}</a>
                </div>
              </div>
            </div>
          </div>
        @endif        
      </div>
    @endif    
  @endfor
</div>
@endsection

@section('script')

@endsection
@extends('layouts.app')

@section('content')
<div class="container">
  <br><br>
  <div class="row">
    @if ($dog->profile_image == null)
      <img style="max-height: 500px;" src="{{ asset('storage/dogs/noimage.jpg') }}" class="col-6"></img>
    @else
      <img style="max-height: 500px;" src="{{ asset('storage/' . $dog->profile_image) }}" class="col-6"></img>
    @endif
    <div class="col-6">
      <h4>{{ $dog->name }}</h4>
      @if (isset($dog->breed) || $dog->breed != null)
        <p>{{ $dog->breed }}</p>
      @endif
      @if (isset($dog->gender) || $dog->gender != null)
        <p>{{ $dog->gender }}</p>
      @endif
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-6" id="friendStatus">
      @if (true)
        <button class="btn btn-primary btn-block" onclick="follow({{ $dog->id }}">Follow</button>
      @endif
    </div>   
  </div>
</div>
@endsection

@section('script')
<script>
  function follow(id) {
    // axios.post(`/friend/response/${id}`, {
    //   'response': 'accept'
    // }).then(response => {        
    //   document.getElementById('friendStatus').innerHTML = `
    //     <button class="btn btn-block btn-disabled">You are friend with {{ $dog->name }}</button>`
    // });
  }
</script>
@endsection
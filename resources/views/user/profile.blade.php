@extends('layouts.app')

@section('content')
<div class="container">
  <br><br>
  <div class="row">
    @if ($user->profile_image == null)
      <img src="{{ asset('storage/dogs/noimage.jpg') }}" class="col-4"></img>
    @else
      <img src="{{ asset('storage/user_profile/profileimgs/' . $user->profile_image) }}" class="col-4"></img>
    @endif
    <div class="col-8">
      <h5>{{ $user->name }}</h5>
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
  <br>
  <div class="row">
    <div class="col-4" id="friendStatus">
      @if ($user->relationship == 'pending')
        <button class="btn btn-primary btn-block" onclick="accept({{ $user->id }})">Accept friend request</button>
        <button class="btn btn-secondary btn-block" onclick="deny({{ $user->id }})">Deny friend request</button>
      @elseif ($user->relationship == 'requested')
        <button class="btn btn-secondary btn-block" onclick="cancel({{ $user->id }})">Cancel friend request</button>
      @elseif ($user->relationship == 'stranger')
        <button class="btn btn-primary btn-block" onclick="send({{ $user->id }})">Send friend request</button>
      @elseif ($user->relationship == 'friend')
        <button class="btn btn-block btn-disabled">You are friend with {{ $user->name }}</button>
      @endif
    </div>   
  </div>
</div>
@endsection

@section('script')
<script>
  function accept(id) {
    axios.post(`/friend/response/${id}`, {
      'response': 'accept'
    }).then(response => {        
      document.getElementById('friendStatus').innerHTML = `
        <button class="btn btn-block btn-disabled">You are friend with {{ $user->name }}</button>`
    });
  }

  function cancel(id) {
    axios.post(`/friend/response/${id}`, {
      'response': 'deny'
    }).then(response => {        
      document.getElementById('friendStatus').innerHTML = `
        <button class="btn btn-primary btn-block" onclick="send({{ $user->id }})">Send friend request</button>`
    });
  }
  
  function deny(id) {
    axios.post(`/friend/response/${id}`, {
      'response': 'deny'
    }).then(response => {        
      document.getElementById('friendStatus').innerHTML = `
        <button class="btn btn-primary btn-block" onclick="send({{ $user->id }})">Send friend request</button>`
    });
  }
  
  function send(id) {
    axios.post(`/user/${id}/addFriend `, {
      'response': 'accept'
    }).then(response => {        
      document.getElementById('friendStatus').innerHTML = `
        <button class="btn btn-secondary btn-block" onclick="cancel({{ $user->id }})">Cancel friend request</button>`
    });
  }
</script>
@endsection
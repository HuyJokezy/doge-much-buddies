<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-info sticky-top">
            <div class="container">
                <!-- Brand -->
                <a class="navbar-brand" href="{{ url('/home') }}">{{ config('app.name', 'Laravel') }}</a>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                    </ul>
                    <ul class="navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="checkFriendRequest()"><i class="fas fa-user-friends"></i></a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('user.edit', ['id'=>Auth::user()->id]) }}">Profile</a>
                                    <a class="dropdown-item" href="{{ route('user.myDog', ['id'=>Auth::user()->id]) }}">Your Doggo</a>
                                    <a class="dropdown-item" href="{{ route('user.myFriend', ['id'=>Auth::user()->id]) }}">Friends</a>
                                    <div class="dropdown-divider"></div>
                                    <div>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>  
                                    </div>                                                                                           
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
        @include('layouts.messages')
        <button id="friendRequestButton" type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg" style="display: none;"></button>
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Friend Requests</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="friendRequestList" class="modal-body">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        let friendReqCount = 0;
        function checkFriendRequest () {
            axios.get('/friend/requests')
            .then(response => {
                let requests = response.data;
                // console.log(response.data);
                let img = requests.profile_image ? `user_profile/profileimgs/${data.profile_image}` : 'dogs/noimage.jpg';
                let friendRequestHtml = '';
                friendReqCount = requests.length;
                if (requests.length === 0) {
                    friendRequestHtml = '<p>You have no friend requests</p>'
                } else {
                    requests.forEach((friendRequest) => {
                        friendRequestHtml += `
                            <div class="card card-body" id="reqCard${friendRequest.id}">
                                <div class="row">
                                    <img src="{{ asset('storage/${img}') }}" alt="" class="imgProfile col-4">                                
                                    <div class="col-4">
                                        <a href="/user/${friendRequest.id}">${friendRequest.name}</a>
                                    </div>
                                    <div class="col-4">
                                        <div class="float-right">
                                            <button class="btn btn-primary" onclick="accept(${friendRequest.id})">Accept</button>
                                            <button class="btn btn-secondary" onclick="deny(${friendRequest.id})">Deny</button>
                                        </div>                                    
                                    </div>
                                </div>
                            </div>`;
                    });
                }
                document.getElementById('friendRequestList').innerHTML = friendRequestHtml;
                document.getElementById('friendRequestButton').click();
            });
        }
        
        function accept(id) {
            axios.post(`/friend/response/${id}`, {
                'response': 'accept'
            }).then(response => {        
                friendReqCount -= 1;
                if (friendReqCount === 0) {
                    document.getElementById('friendRequestList').innerHTML = '<p>You have no friend requests</p>';
                } else {
                    document.getElementById(`reqCard${id}`).remove();
                }
            });
        }

        function deny(id) {
            axios.post(`/friend/response/${id}`, {
                'response': 'deny'
            }).then(response => {
                friendReqCount -= 1;
                if (friendReqCount === 0) {
                    document.getElementById('friendRequestList').innerHTML = '<p>You have no friend requests</p>';
                } else {
                    document.getElementById(`reqCard${id}`).remove();
                }
            });
        }
    </script>
    @yield('script')
</body>
</html>

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!

                    <passport-clients></passport-clients>
                    <passport-authorized-clients></passport-authorized-clients>
                    <passport-personal-access-tokens></passport-personal-access-tokens>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script type="text/javascript">
        // axios.get('/api/dog/37/images')
        // .then(response => {
        //     console.log(response.data);
        // });

        // Test react post
        // axios.post('post/15/post_reacts', {
        //     type: 'Like'
        // })
        // .then(response => {
        //     console.log(response.data);
        // });

        // Test react update
        // axios.put('post/15/post_reacts', {
        //     type: 'Like'
        // })
        // .then(response => {
        //     console.log(response.data);
        // });
        
        // Test react delete
        // axios.delete('post/15/post_reacts')
        // .then(response => {
        //     console.log(response.data);
        // });

        // Test comment post from owner
        // axios.post('post/9/post_comments', {
        //     comment: 'Lovely picture :D'
        // })
        // .then(response => {
        //     console.log(response.data);
        // });

        // Test comment update from owner (notice: comment_id)
        // axios.put('post/9/post_comments/41', {
        //     comment: 'Awesome ShibaInu :D'
        // })
        // .then(response => {
        //     console.log(response.data);
        // });

        // Test comment delete from owner (notice: comment_id)
        // axios.delete('post/9/post_comments/41')
        // .then(response => {
        //     console.log(response.data);
        // });

        // Test comment post from friend
        // axios.post('post/3/post_comments', {
        //     comment: 'Lovely picture :D'
        // })
        // .then(response => {
        //     console.log(response.data);
        // });

        // Test comment post from friend (notice: comment_id)
        // axios.delete('post/3/post_comments/42')
        // .then(response => {
        //     console.log(response.data);
        // });

        // Test comment post from non-friend
        // axios.post('post/1/post_comments', {
        //     comment: 'Lovely picture :D'
        // })
        // .then(response => {
        //     console.log(response.data);
        // });

        // Test add friend (notice target id)
        // axios.post('user/1/addFriend')
        // .then(response => {
        //     console.log(response.data);
        // });

        // Test reponse friend request (notice target id) response = {deny, acccept}
        // axios.post('friend/response/3', {
        //     response: 'deny'
        // })
        // .then(response => {
        //     console.log(response.data);
        // });
    </script>
@endsection
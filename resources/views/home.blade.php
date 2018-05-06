@extends('layouts.app')

@section('content')
<div class="container">
    <br><br>
    <div class="row">
        <div class="col-10">
            @foreach ($posts as $index=>$post)
                <div class="card card-body" id="post{{ $post->id }}">
                    <h6>{{ $post->owner->name }} <small>at {{ $post->created_at }}</small></h6>
                    <div class="dropdown-divider"></div>
                    <p>{{ $post->content }}</p>
                    <div class="dropdown-divider"></div>
                    <div class="row">
                        <div class="col-1"><i class="far fa-smile"></i> {{ $post->laughCount }}</div>
                        <div class="col-1"><i class="far fa-thumbs-up"></i> {{ $post->likeCount }}</div>
                        <div class="col-1"><i class="far fa-heart"></i> {{ $post->loveCount }}</div>
                    </div>
                </div>
                <br>
            @endforeach           
        </div>
  </div>
</div>
@endsection
@section('script')
    <script type="text/javascript">
        $('i').mouseover(function(){
            $(this).removeClass('far').addClass('fas');
        }).mouseout(function(){
            $(this).removeClass('fas').addClass('far');       
        });
        
        $('i').hover(function() {
            $(this).css('cursor','pointer');
        }, function() {
            $(this).css('cursor','auto');
        });
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
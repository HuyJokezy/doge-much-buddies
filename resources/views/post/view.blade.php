@extends('layouts.app')

@section('content')
<div class="container"><br><br>
  <div class="card card-body" id="post{{ $post->id }}">
                    @if ($post->isOwner)
                    <h6>{{ $post->owner->name }} <small>at {{ $post->created_at }}</small></h6>
                    @else
                    <h6><a href="/user/{{ $post->owner->id }}">{{ $post->owner->name }} </a><small>at {{ $post->created_at }}</small></h6>
                    @endif
                    <div class="dropdown-divider"></div>
                    <p>{{ $post->content }}</p>
                    <p>
                        <i class="fas fa-tags"></i>
                        @foreach ($post->tags as $tag)
                            <a href="#">{{ $tag->dog->name }}</a>,
                        @endforeach
                    </p>
                    <div class="dropdown-divider"></div>
                    <div class="row">
                        <div class="col-1 reactionIcon">
                            <i id="laugh{{ $post->id }}" class="far fa-smile" onclick="toggleReaction({{ $post->id }}, 'Laugh')"></i>
                            <span id="laughCount{{ $post->id }}" class="reactionInfo"> {{ $post->laughCount }}</span>
                        </div>
                        <div class="col-1 reactionIcon">
                            <i id="like{{ $post->id }}" class="far fa-thumbs-up" onclick="toggleReaction({{ $post->id }}, 'Like')"></i>
                            <span id="likeCount{{ $post->id }}" class="reactionInfo"> {{ $post->likeCount }}</span>
                        </div>
                        <div class="col-1 reactionIcon">
                            <i id="love{{ $post->id }}" class="far fa-heart" onclick="toggleReaction({{ $post->id }}, 'Love')"></i>
                            <span id="loveCount{{ $post->id }}" class="reactionInfo"> {{ $post->loveCount }}</span>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    @if ($post->isOwner)
                    <div class="card card-body">
                        <textarea class="form-control" id="content" rows="3"></textarea>
                        <br>
                        <div class="row">
                          <div class="col-9"></div>
                          <div class="col-3">
                            <button class="btn btn-primary float-right" onclick="comment()">Comment</button>
                          </div>
                        </div>                        
                    </div>
                    @endif
                    @foreach ($post->comments as $indexComment=>$comment)
                        <div class="card card-body">
                            <p class="row">
                                <strong class="col-6"><a href="/user/{{ $comment->ownerObject->id }}">{{ $comment->ownerObject->name }} </a></strong>
                                <small class="col-6" style="text-align: right;">{{ $comment->created_at }}</small>
                            </p>
                            <p class="commentBox">{{ $comment->comment }}</p>
                        </div>
                    @endforeach
                </div>
</div>
                
@endsection

@section('script')
<script type="text/javascript">
        let yourPostReactions = {};

        $('i').mouseover(function(){
            $(this).removeClass('far').addClass('fas');
        }).mouseout(function(){
            let postId = $(this).attr('id').replace(/\D/g,'');
            let type = $(this).attr('id').replace(/[0-9]/g, '');
            if (type !== yourPostReactions[postId].toLowerCase()) $(this).removeClass('fas').addClass('far');                
        });
        
        $('i').hover(function() {
            $(this).css('cursor','pointer');
        }, function() {
            $(this).css('cursor','auto');
        });
     

        (function () {
            
                @if ($post->yourReaction == 'Laugh')
                    $('#laugh' + {{ $post->id }}).addClass('fas').removeClass('far');
                    yourPostReactions['{{ $post->id }}'] = 'Laugh';
                @elseif ($post->yourReaction == 'Like')
                    $('#like' + {{ $post->id }}).addClass('fas').removeClass('far');
                    yourPostReactions['{{ $post->id }}'] = 'Like';
                @elseif ($post->yourReaction == 'Love')
                    $('#love' + {{ $post->id }}).addClass('fas').removeClass('far');
                    yourPostReactions['{{ $post->id }}'] = 'Love';
                @else
                    yourPostReactions['{{ $post->id }}'] = 'None';
                @endif
            
        })();

        function comment () {
          axios.post(`/post/{{ $post->id }}/post_comments`, {
            comment: document.getElementById('content').value
          })
          .then(response => (response.status === 200) ? location.reload(true) : alert('Sorry there is a problem with the connection, please try again later'))
        }

        function toggleReaction(id, type) {
            if (yourPostReactions[id.toString()] === type) {
                axios.delete(`/post/${ id }/post_reacts`, {})
                .then(response => {
                    $('#' + type.toLowerCase() + id).removeClass('fas').addClass('far');
                    yourPostReactions[id.toString()] = 'None';
                })
                let newCount = parseInt(document.getElementById(type.toLowerCase() + 'Count' + id).innerHTML) - 1;
                document.getElementById(type.toLowerCase() + 'Count' + id).innerHTML = newCount;
            } else if (yourPostReactions[id.toString()] === 'None') {
                axios.post(`/post/${ id }/post_reacts`, {
                    type: type
                })
                .then(response => {
                    $('#' + type.toLowerCase() + id).removeClass('far').addClass('fas');
                    yourPostReactions[id.toString()] = type;
                    let newCount = parseInt(document.getElementById(type.toLowerCase() + 'Count' + id).innerHTML) + 1;
                    document.getElementById(type.toLowerCase() + 'Count' + id).innerHTML = newCount;
                })
            } else {
                axios.put(`/post/${ id }/post_reacts`, {
                    type: type
                })
                .then(response => {
                    $('#' + type.toLowerCase() + id).removeClass('far').addClass('fas');
                    $('#' + yourPostReactions[id.toString()].toLowerCase() + id).removeClass('fas').addClass('far');

                    let newCount = parseInt(document.getElementById(type.toLowerCase() + 'Count' + id).innerHTML) + 1;
                    document.getElementById(type.toLowerCase() + 'Count' + id).innerHTML = newCount;

                    newCount = parseInt(document.getElementById(yourPostReactions[id.toString()].toLowerCase() + 'Count' + id).innerHTML) - 1;
                    document.getElementById(yourPostReactions[id.toString()].toLowerCase() + 'Count' + id).innerHTML = newCount;

                    yourPostReactions[id.toString()] = type;
                })
            }
        }
    </script>

@endsection

        
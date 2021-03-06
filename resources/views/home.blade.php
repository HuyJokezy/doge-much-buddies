@extends('layouts.app')

@section('content')
<div class="container">
    <br><br>
    <div class="row">
        <div class="col-10">
            <div class="card card-body">
                <a href="{{ route('post.create') }}">+ New post</a>
            </div>
            @foreach ($posts as $index=>$post)
                <div class="card card-body" id="post{{ $post->id }}">               
                    <div class="row">
                        <div class="col-6">
                        @if ($post->owner->id === \Auth::user()->id)
                        <h6>{{ $post->owner->name }} <small>at {{ $post->created_at }}</small></h6>
                        @else
                        <h6><a href="/user/{{ $post->owner->id }}">{{ $post->owner->name }} </a><small>at {{ $post->created_at }}</small></h6>
                        @endif
                        </div>
                        <div class="col-6"><i class="fas fa-book float-right" onclick="window.location.href='/post/{{ $post->id }}'"></i></div>
                    </div>
                    <div class="dropdown-divider"></div>
                    @if ($post->image)
                    <img class="d-block w-100" src="{{ asset('storage/'. $post->image) }}" alt="">
                    @endif
                    <br>
                    @if (strlen($post->content) < 250)
                        <p>{{ $post->content }}</p>
                    @else
                        <p>{{ substr($post->content, 0, 239) }}  <a href="/post/{{ $post->id }}">...see more</a></p>                        
                    @endif
                    @if (!empty($post->tags))
                    <p>
                        <i class="fas fa-tags"></i>
                        @foreach ($post->tags as $tag)
                            <a href="/dog/{{ $tag->dog->id }}">{{ $tag->dog->name }}</a>,
                        @endforeach
                    </p>
                    @endif
                    <div class="dropdown-divider"></div>
                    <div class="row">
                        <div class="col-1 reactionIcon">
                            <i id="laugh{{ $post->id }}" class="far fa-smile reactionI" onclick="toggleReaction({{ $post->id }}, 'Laugh')"></i>
                            <span id="laughCount{{ $post->id }}" class="reactionInfo"> {{ $post->laughCount }}</span>
                        </div>
                        <div class="col-1 reactionIcon">
                            <i id="like{{ $post->id }}" class="far fa-thumbs-up reactionI" onclick="toggleReaction({{ $post->id }}, 'Like')"></i>
                            <span id="likeCount{{ $post->id }}" class="reactionInfo"> {{ $post->likeCount }}</span>
                        </div>
                        <div class="col-1 reactionIcon">
                            <i id="love{{ $post->id }}" class="far fa-heart reactionI" onclick="toggleReaction({{ $post->id }}, 'Love')"></i>
                            <span id="loveCount{{ $post->id }}" class="reactionInfo"> {{ $post->loveCount }}</span>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="card card-body">
                        <a href="/post/{{ $post->id }}">+ Comment on post</a>
                    </div>
                    @foreach ($post->comments as $indexComment=>$comment)
                        @if ($indexComment > 2)
                            <div class="card card-body">
                                <a href="/post/{{ $post->id }}">...View more comments</a>
                            </div>
                            @break
                        @endif
                        <div class="card card-body">
                            <p class="row">
                                @if ($comment->ownerObject->id === $post->owner->id)
                                <strong class="col-6">{{ $comment->ownerObject->name }} </strong>
                                @else
                                <a class="col-6" href="/user/{{ $comment->ownerObject->id }}"><strong>{{ $comment->ownerObject->name }} </strong></a>
                                @endif
                                <small class="col-6" style="text-align: right;">{{ $comment->created_at }}</small>
                            </p>
                            <p class="commentBox">{{ $comment->comment }}</p>
                        </div>
                    @endforeach
                </div>
                <br>
            @endforeach           
        </div>
  </div>
</div>
@endsection
@section('script')
    <script type="text/javascript">
        let yourPostReactions = {};

        $('.reactionI').mouseover(function(){
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
            @foreach ($posts as $index=>$post)
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
            @endforeach
        })();

        function toggleReaction(id, type) {
            if (yourPostReactions[id.toString()] === type) {
                axios.delete(`post/${ id }/post_reacts`, {})
                .then(response => {
                    $('#' + type.toLowerCase() + id).removeClass('fas').addClass('far');
                    yourPostReactions[id.toString()] = 'None';
                })
                let newCount = parseInt(document.getElementById(type.toLowerCase() + 'Count' + id).innerHTML) - 1;
                document.getElementById(type.toLowerCase() + 'Count' + id).innerHTML = newCount;
            } else if (yourPostReactions[id.toString()] === 'None') {
                axios.post(`post/${ id }/post_reacts`, {
                    type: type
                })
                .then(response => {
                    $('#' + type.toLowerCase() + id).removeClass('far').addClass('fas');
                    yourPostReactions[id.toString()] = type;
                    let newCount = parseInt(document.getElementById(type.toLowerCase() + 'Count' + id).innerHTML) + 1;
                    document.getElementById(type.toLowerCase() + 'Count' + id).innerHTML = newCount;
                })
            } else {
                axios.put(`post/${ id }/post_reacts`, {
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
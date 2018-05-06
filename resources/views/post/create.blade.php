@extends('layouts.app')

@section('content')
<div class="container">
  <br><br>
  <form>
    <div class="form-group">
      <label for="content"></label>
      <textarea class="form-control" id="content" rows="6"></textarea>
    </div>
    <div class="form-group card card-body">
      <div>Tags: 
        <select name="" id="" onchange="tagDog(this.value)">
            <option value="" selected>Choose a dog</option>
          @foreach ($followedDogs as $index=>$followDog)
            <option id="tick{{ $followDog->id }}" value="{{ $followDog->id }}">
              | | {{$followDog->name}} ({{$followDog->breed}})
            </option>
          @endforeach
        </select>
      </div>
      <br>
      <p id="taggedDogsText"></p>
    </div>
  </form>
  <div class="row">
    <div class="col-3"><button class="btn btn-primary btn-block" onclick="post()">Post</button></div>     
  </div> 
</div>
@endsection

@section('script')
<script>
  let taggedDogs = [];
  let tagableDogs = {};
  @foreach ($followedDogs as $index=>$followDog)
    tagableDogs['{{ $followDog->id }}'] = {
      name: '{{ $followDog->name }}',
      breed: '{{ $followDog->breed }}',
    };
  @endforeach
  
  function tagDog(id) {
    if (id === "") return;;
    let alreadyTaggedDog = taggedDogs.findIndex((taggedDog) => {
      return taggedDog.id === id;
    });
    if (alreadyTaggedDog !== -1) {
      taggedDogs.splice(alreadyTaggedDog, 1);
      let text = document.getElementById('tick' + id).innerHTML.trim();
      document.getElementById('tick' + id).innerHTML = text.substr(0, 1) + ' ' + text.substr(2);
    } else {
      taggedDogs.push({
        id,
        name: tagableDogs[`${ id }`].name,
        breed: tagableDogs[`${ id }`].breed
      });
      let text = document.getElementById('tick' + id).innerHTML.trim();
      document.getElementById('tick' + id).innerHTML = text.substr(0, 1) + 'X' + text.substr(2);
    }
    let taggedDogsText = taggedDogs.map((taggedDog) => taggedDog.name).join(', ');
    document.getElementById('taggedDogsText').innerHTML = taggedDogsText;
  }

  function post() {
    console.log(1);
    let content = document.getElementById('content').value;
    let tags = taggedDogs.map((taggedDog) => taggedDog.id);
    axios.post('/post', {
      content,
      tags
    })
    .then(response => response.status === 200 ? window.location.href = '/home' : alert('Sorry there is some connection problem, please post again later'));
  }
</script>
@endsection
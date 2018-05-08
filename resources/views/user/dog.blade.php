@extends('layouts.app')

@section('content')
<div class="container">
  <br><br>
  <div class="row">
    <div class="col-7">
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="false">
        <div class="carousel-inner">
          @foreach ($dogs as $index=>$dog)
            <div
              id="dog{{ $index }}"
              @if ($index == 0)
                class="carousel-item active"
              @else
                class="carousel-item"
              @endif
            >
              <img class="d-block w-100" src="{{ asset('storage/'. $dog->profile_image) }}" alt="{{ $dog->name }}">
              <div class="carousel-caption d-none d-md-block" style="color:black; background-color: rgb(189,195,199, 0.6)">
                <h5>{{ $dog->name }}</h5>
                <p>{{ $dog->breed }} ({{ $dog->gender }})</p>
              </div>
            </div>
          @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
    <div class="col-5">
      <p><a href="#" onclick="openChangeDogInfoModal()" class="btn btn-success btn-block">Change Information</a></p>
      <p><a href="#" class="btn btn-primary btn-block">View Tagged Posts</a></p>
      <p><a href="#" onclick="openDeleteDogModal()" class="btn btn-danger btn-block">Delete</a></p>
      <br>
      <p style="text-align: center;">or</p>
      <br>
      <p><a href="{{ route('dog.create') }}" class="btn btn-light btn-block">Bring in your new doggo <i class="fas fa-plus"></i></a></p>
  </div>
  </div>
  <br><br>
</div>

<!-- Modal Hidden Button -->
@foreach ($dogs as $index=>$dog)
  <button id="modalButton{{ $index }}" type="button" class="btn btn-primary" data-toggle="modal" data-target="#dogInfo{{ $index }}" style="display: none"></button>
  <button id="modalButtonDelete{{ $index }}" type="button" class="btn btn-primary" data-toggle="modal" data-target="#dogDelete{{ $index }}" style="display: none"></button>
@endforeach

<!-- Modal View -->
@foreach ($dogs as $index=>$dog)
  <div class="modal fade" id="dogInfo{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="dogInfoLabel{{ $index }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="dogInfoLabel{{ $index }}">Doggo Information</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form enctype="multipart/form-data" method="POST" action="{{route('dog.update', $dogs[$index]->id)}}">
            <div class="form-row">
              <div class="form-group col-12">
                <label for="dogName{{ $index }}">Name</label>
                <input type="text" name="name" class="form-control" id="dogName{{ $index }}" placeholder="" value="{{ $dog->name }}">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-6">
                <label for="dogBreed{{ $index }}">Breed</label>
                <select name="breed" class="form-control" id="dogBreed{{ $index }}">
                  <option value="Labrador Retriever" 
                    {{ ($dog->breed == 'Labrador Retriever' ? 'selected' : '') }}>Labrador Retriever</option>
                  <option value="German Shepherds"
                    {{ ($dog->breed == 'German Shepherds' ? 'selected' : '') }}>German Shepherds</option>
                  <option value="Golden Retriever"
                    {{ ($dog->breed == 'Golden Retriever' ? 'selected' : '') }}>Golden Retriever</option>
                  <option value="Bulldog"
                    {{ ($dog->breed == 'Bulldog' ? 'selected' : '') }}>Bulldog</option>
                  <option value="Boxer"
                    {{ ($dog->breed == 'Boxer' ? 'selected' : '') }}>Boxer</option>
                  <option value="Rottweiler"
                    {{ ($dog->breed == 'Rottweiler' ? 'selected' : '') }}>Rottweiler</option>
                  <option value="Dachshund"
                    {{ ($dog->breed == 'Dachshund' ? 'selected' : '') }}>Dachshund</option>
                  <option value="Husky"
                    {{ ($dog->breed == 'Husky' ? 'selected' : '') }}>Husky</option>
                  <option value="Great Dane"
                    {{ ($dog->breed == 'Great Dane' ? 'selected' : '') }}>Great Dane</option> 
                  <option value="Doberman Pinschers"
                    {{ ($dog->breed == 'Doberman Pinschers' ? 'selected' : '') }}>Doberman Pinschers</option>
                  <option value="Australian Shepherds"
                    {{ ($dog->breed == 'Australian Shepherds' ? 'selected' : '') }}>Australian Shepherds</option>
                  <option value="Corgi"
                    {{ ($dog->breed == 'Corgi' ? 'selected' : '') }}>Corgi</option>
                  <option value="Shiba"
                    {{ ($dog->breed == 'Shiba' ? 'selected' : '') }}>Shiba</option>
                  <option value="Mixed"
                    {{ ($dog->breed == 'Mixed' ? 'selected' : '') }}>Mixed</option>
                </select>
              </div>
              <div class="form-group col-6">
                <label for="dogGender{{ $index }}">Gender</label>
                <select name="gender" class="form-control" id="dogGender{{ $index }}">
                  <option value="Male"
                    {{ ($dog->gender == 'Male' ? 'selected' : '') }}>Male</option>
                  <option value="Female"
                    {{ ($dog->gender == 'Female' ? 'selected' : '') }}>Female</option>
                </select>
              </div>
            </div>
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <button style="display: none;" id="dogSubmit{{ $index }}" type="submit"></button>          
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" onclick="document.getElementById('dogSubmit{{ $index }}').click()"class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
@endforeach

<!-- Modal Delete -->
@foreach ($dogs as $index=>$dog)
  <div class="modal fade" id="dogDelete{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="dogDeleteLabel{{ $index }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="dogDeleteLabel{{ $index }}">Are you leaving?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>I understand that you heard that call from the wild, but can we talk about it?</p>
          <p>The pack will always be here waiting for you</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Stay with us</button>
          <button type="button" onclick="deleteDog({{ $dogs[$index]->id }})"class="btn btn-danger">Good byeee!!</button>
        </div>
      </div>
    </div>
  </div>
@endforeach

@endsection

@section('script')
<script>
  function openChangeDogInfoModal() {
    const currentDogId = parseInt(document.getElementsByClassName('active')[0].id.replace('dog', ''));
    document.getElementById(`modalButton${currentDogId}`).click()
  }

  function openDeleteDogModal() {
    const currentDogId = parseInt(document.getElementsByClassName('active')[0].id.replace('dog', ''));
    document.getElementById(`modalButtonDelete${currentDogId}`).click()
  }

  function deleteDog(id) {
    axios.delete(`/dog/${ id }`, []).then(response => {
      if (response.status === 200) location.reload();
    });
  }
</script>
@endsection
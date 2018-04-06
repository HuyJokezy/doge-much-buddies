@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('dog.store')}}" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Enter your dog name">
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="breed">Select your dog's breed</label>
            <select class="form-control" id="breed" name="breed">
                <option selected>Mixed</option>
                <option>Labrador Retriever</option>
                <option>German Shepherds</option>
                <option>Golden Retriever</option>
                <option>Bulldog</option>
                <option>Boxer</option>
                <option>Rottweiler</option>
                <option>Dachshund</option>
                <option>Husky</option>
                <option>Great Dane</option>
                <option>Doberman Pinschers</option>
                <option>Australian Shepherds</option>
                <option>Corgi</option>
                <option>Shiba</option>
            </select>
            @if ($errors->has('breed'))
                <span class="help-block">
                    <strong>{{ $errors->first('breed') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label>Dog gender</label><br>
            <label class="radio-inline"><input type="radio" name="gender" checked="checked" value="Male">Male</label>
            <label class="radio-inline"><input type="radio" name="gender" value="Female">Female</label>
            @if ($errors->has('breed'))
                <span class="help-block">
                    <strong>{{ $errors->first('breed') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="profileimg">Profile picture</label>
            <input type="file" class="form-control-file" id="profileimg" name="profileimg">
            @if ($errors->has('profileimg'))
                <span class="help-block">
                    <strong>{{ $errors->first('profileimg') }}</strong>
                </span>
            @endif
        </div>
        {{ csrf_field() }}
        {{-- {{ method_field('PUT') }} --}}
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
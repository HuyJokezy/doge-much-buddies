@extends('layouts.app')
@section('content')
    <div class="container">
        <strong>{{ $exception->getMessage() }}</strong>
    </div>
@endsection
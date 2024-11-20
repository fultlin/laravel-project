@extends('layout')
@section('content')

@if($errors->any()) 
  @foreach($errors->all() as $error)
    <div class="alert alert-danger" role='alert'>{{$error}}</div>
  @endforeach
@endif

<form action="/articles" method="POST">
  @csrf
  <div class="mb-3">
    <label for="date" class="form-label">Date</label>
    <input type="date" class="form-control" id="date" aria-describedby="namelHelp" name="date" value="{{ date('Y-m-d') }}">
  </div>
  <div class="mb-3">
    <label for="exampleInputName1" class="form-label">Name</label>
    <input type="text" class="form-control" id="exampleInputName1" aria-describedby="emailHelp" name="name">
  </div>
  <div class="mb-3">
    <label for="exampleInputdescription1" class="form-label">Description</label>
    <input type="text" class="form-control" id="exampleInputdescription1" aria-describedby="emailHelp" name="desc">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection

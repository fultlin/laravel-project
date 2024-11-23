@extends('layout')
@section('content')

@if($errors->any()) 
  @foreach($errors->all() as $error)
    <div class="alert alert-danger" role='alert'>{{$error}}</div>
  @endforeach
@endif


<form action="/comment/{{$comment->id}}/update" method="POST">
  @csrf
  <div class="mb-3">
    <label for="exampleInputName1" class="form-label">Name</label>
    <input type="text" class="form-control" id="exampleInputName1" aria-describedby="emailHelp" name="name"  value="{{ $comment->name }}">
  </div>
  <div class="mb-3">
    <label for="exampleInputdescription1" class="form-label">Description</label>
    <input type="text" class="form-control" id="exampleInputdescription1" aria-describedby="emailHelp" name="desc"  value="{{ $comment->desc }}">
  </div>
  <button type="submit" class="btn btn-primary">Update comment</button>
</form>
@endsection

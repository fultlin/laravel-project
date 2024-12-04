@extends('layout')
@section('content')
@if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
<div class="card text-center mb-3" style="width: 70rem; margin: 0 auto;">
    <div class="card-header">
    Author: {{$user}}
    </div>
  <div class="card-body">
    <h5 class="card-title">{{ $article->name }}</h5>
    <p class="card-text">{{ $article->desc }}</p>
    <div class="d-flex justify-content-center gap-3">
        @can('update')
          <a href="/articles/{{$article->id}}/edit" class="btn btn-primary">Edit article</a>
          <form action="/articles/{{$article->id}}" method="POST">
              @method("DELETE")
              @csrf
              <button type="submit" class="btn btn-danger">Delete article</button>
          </form>
        @endcan
    </div>
  </div>
  <div class="comments-section flex">
    <h3>Comments</h3>
    @if($comments->isEmpty())
        <p>No comments yet.</p>
    @else
        @foreach($comments as $comment)
        <div class="row mt-3" style='margin: 0 auto'>
            <div class="col-sm-6 mb-3 mb-sm-0">
                <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{$comment->name}}</h5>
                    <p class="card-text">{{$comment->desc}}</p>
                    @can('update_comment',$comment)
                      <a href="/comment/{{$comment->id}}/edit" class="btn btn-primary">Comment update</a>
                      <a href="/comment/{{$comment->id}}/delete" class="btn btn-warning">Comment delete</a>
                    @endcan
                </div>
                </div>
            </div>

        </div>
        @endforeach
    @endif
  </div>
  
    <h3 class="text=centered">Add comment</h3>
    @if($errors->any()) 
        @foreach($errors->all() as $error)
            <div class="alert alert-danger" role='alert'>{{$error}}</div>
        @endforeach
    @endif

  <form action="/comment" method="POST">
  @csrf
  <div class="mb-3">
    <label for="exampleInputName1" class="form-label">Name</label>
    <input type="text" class="form-control" id="exampleInputName1" aria-describedby="emailHelp" name="name">
  </div>
  <div class="mb-3">
    <label for="exampleInputdescription1" class="form-label">Description</label>
    <input type="text" class="form-control" id="exampleInputdescription1" aria-describedby="emailHelp" name="desc">
  </div>
  <input type="hidden" name="article_id" value="{{$article->id}}">
  <button type="submit" class="btn btn-primary">Save comment</button>
</form>
</div>
@endsection('content')
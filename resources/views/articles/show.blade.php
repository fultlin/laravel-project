@extends('layout')
@section('content')
<div class="card text-center mb-3" style="width: 70rem;">
    <div class="card-header">
    Author: {{$user}}
    </div>
  <div class="card-body">
    <h5 class="card-title">{{ $article->name }}</h5>
    <p class="card-text">{{ $article->desc }}</p>
    <div class="d-flex justify-content-center gap-3">
        <a href="/articles/{{$article->id}}/edit" class="btn btn-primary">Edit article</a>
        <form action="/articles/{{$article->id}}" method="POST">
            @method("DELETE")
            @csrf
            <button type="submit" class="btn btn-danger">Delete article</button>
        </form>
    </div>
  </div>
  <div class="comments-section">
    <h4>Comments</h4>
    @if($comments->isEmpty())
        <p>No comments yet.</p>
    @else
        @foreach($comments as $comment)
            <div class="comment">
                <p><strong>{{ $comment->name }}:</strong> {{ $comment->desc }}</p>
            </div>
        @endforeach
    @endif
</div>
</div>
@endsection('content')
@extends('layout')
@section('content')
    <section>
        <table class="table">
            <thead>
                <tr>
                <th scope="col">Дата</th>
                <th scope="col">Название</th>
                <th scope="col">Описание</th>
                <th scope="col">Изображение</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($articles as $article) 
                <tr>
                <th scope="row">{{ $article->date }}</th>
                <td>{{ $article->name }}</td>
                <td style="width:20rem">{{ $article->desc }}</td>
                <td><a href="/galary/{{$article->full_image}}"><img class="img-thumbnail" src="{{ $article->preview_image }}" alt="{{ $article->shortDesc }}" width=200 height=100/></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection

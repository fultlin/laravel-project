@extends('layout')
@use('App\Models\User', 'User')
@section('content')
<section>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Дата</th>
                <th scope="col">Название</th>
                <th scope="col">Описание</th>
                <th scope="col">Автор</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($articles as $article) 
                <tr>
                    <th scope="row">{{ $article->date }}</th>
                    <td>{{ $article->name }}</td>
                    <td style="width:20rem">{{ $article->desc }}</td>
                    <td>
                        {{User::findOrFail($article->user_id)->name}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection
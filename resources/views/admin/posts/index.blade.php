@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Lista Post</div>

                <div class="card-body">

                    <div class="mb-3">
                        <a href="{{route("posts.create")}}"><button type="button" class="btn btn-success">Aggiungi post</button></a>
                    </div>

                    <table class="table">
                        <thead>
                          <tr class="text-center">
                            <th scope="col">#</th>
                            <th scope="col">Titolo</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Stato</th>
                            <th scope="col">Azioni</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                            <tr>
                                <td>{{$post->id}}</td>
                                <td>{{$post->title}}</td>
                                <td>{{$post->slug}}</td>
                                <td class="text-center">
                                    @if ($post->published)
                                    <span class="badge badge-pill badge-success">Pubblicato</span>
                                    @else  
                                    <span class="badge badge-pill badge-secondary">Bozza</span>   
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route("posts.show", $post->id)}}"><button type="button" class="btn btn-primary">Visualizza</button></a></td> 
                                <td>
                                    <a href="{{route("posts.edit", $post->id)}}"><button type="button" class="btn btn-warning">Modifica</button></a>
                                </td> 
                                <td>
                                    <form action="{{route("posts.destroy", $post->id)}}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-danger">Elimina</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

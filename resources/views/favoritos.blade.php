@php
    namespace App\Models;
    use App\Models\Core\Favoritos;
    $favoritos = Favoritos::all();
@endphp

@foreach ($favoritos as $favorito)
    <a href="{{route($favorito->ruta)}}" class="badge badge-pill badge-dark">{{$favorito->nombre}}</a>
@endforeach

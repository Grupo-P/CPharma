@php
    namespace App\Models;
    use App\Models\Core\Favoritos;
    $favoritos = Favoritos::orderBy('nombre', 'asc')->get();
@endphp

@foreach ($favoritos as $favorito)
    <a href="{{route($favorito->ruta)}}" class="badge badge-pill badge-dark shadow">{{$favorito->nombre}}</a>
@endforeach

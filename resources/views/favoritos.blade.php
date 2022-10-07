@php
    namespace App\Models;
    use App\Models\Core\Favoritos;
    $favoritos = Favoritos::where('user_favoritos', auth()->user()->id)->orderBy('nombre', 'asc')->get();
@endphp

<div class="mb-2">
    @foreach ($favoritos as $favorito)
        <a href="{{route($favorito->ruta)}}" class="badge badge-pill badge-dark shadow">{{$favorito->nombre}}</a>
    @endforeach
</div>

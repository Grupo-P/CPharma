@php
    use App\Models\Licencia;
    use App\Models\Cola;
    use App\Models\Caja;
    use App\Models\Anuncio;
    use App\Models\User;
    use App\Models\Auditoria;
    use App\Models\Turno;
@endphp

@php
    $validate_licence = Licencia::validate_licence();
    $datetime1 = new DateTime(date('Y-m-d'));
    $datetime2 = new DateTime($validate_licence['fecha']);
    $interval = $datetime1->diff($datetime2);
    $diff_dias = $interval->format('%a');
    $interval = $interval->format('%R%a');
    $color = ($validate_licence['validate_licence'])?( ($diff_dias > 5)?'green-500':'red-500' ):'red-500';

    $colas = Cola::where('deleted_at','=',null)->where('activa','=','1')->get();
    $cajas = Caja::where('deleted_at','=',null)->where('activa','=','1')->get();
    $anuncios = Anuncio::where('deleted_at','=',null)->where('activa','=','1')->get();
    $usuarios = User::all();
    $auditorias = Auditoria::where('fecha','>',date('Y-m-d'))->get();
    $turnos = Turno::where('deleted_at','=',null)->where('fecha_llamado','=',null)->where('fecha_solicitud','>',date('Y-m-d'))->get();
@endphp


<div class="grid grid-cols-1 p-6 md:grid-cols-4 gap-6">

    <x-cards href="{{ route('licencia') }}">
        <x-slot name="color">{{$color}}</x-slot>
        <x-slot name="tamanioTexto">1xl</x-slot>
        <x-slot name="icono"><i class="fa fa-unlock-alt text-4xl"></i></x-slot>
        <x-slot name="texto">{{$validate_licence['mensaje'];}}</x-slot>
        <x-slot name="contador"><p class="text-{{$color}} text-4xl">{{$interval}}</p></x-slot>
    </x-cards>

    <x-cards href="{{ route('construccion') }}">
        <x-slot name="color">gray-900</x-slot>
        <x-slot name="tamanioTexto">2xl</x-slot>
        <x-slot name="icono"><i class="fa fa-file-alt text-4xl text-gray-900"></i></x-slot>
        <x-slot name="texto">Reportes</x-slot>
        <x-slot name="contador"><p class="text-gray-500 text-4xl">0</p></x-slot>
    </x-cards>

    <x-cards href="{{ route('turnos') }}">
        <x-slot name="color">gray-900</x-slot>
        <x-slot name="tamanioTexto">2xl</x-slot>
        <x-slot name="icono"><i class="fa fa-receipt text-4xl text-gray-900"></i></x-slot>
        <x-slot name="texto">Turnos</x-slot>
        <x-slot name="contador"><p class="text-gray-500 text-4xl">{{count($turnos)}}</p></x-slot>
    </x-cards>

    <x-cards href="{{ route('auditorias') }}">
        <x-slot name="color">gray-900</x-slot>
        <x-slot name="tamanioTexto">2xl</x-slot>
        <x-slot name="icono"><i class="fa fa-search text-4xl text-gray-900"></i></x-slot>
        <x-slot name="texto">Auditorias</x-slot>
        <x-slot name="contador"><p class="text-gray-500 text-4xl">{{count($auditorias)}}</p></x-slot>
    </x-cards>

    <x-cards href="{{ route('colas') }}">
        <x-slot name="color">gray-900</x-slot>
        <x-slot name="tamanioTexto">2xl</x-slot>
        <x-slot name="icono"><i class="fa fa-people-arrows text-4xl text-gray-900"></i></x-slot>
        <x-slot name="texto">Colas</x-slot>
        <x-slot name="contador"><p class="text-gray-500 text-4xl">{{count($colas)}}</p></x-slot>
    </x-cards>

    <x-cards href="{{ route('cajas') }}">
        <x-slot name="color">gray-900</x-slot>
        <x-slot name="tamanioTexto">2xl</x-slot>
        <x-slot name="icono"><i class="fa fa-cash-register text-4xl text-gray-900"></i></x-slot>
        <x-slot name="texto">Cajas</x-slot>
        <x-slot name="contador"><p class="text-gray-500 text-4xl">{{count($cajas)}}</p></x-slot>
    </x-cards>

    <x-cards href="{{ route('anuncios') }}">
        <x-slot name="color">gray-900</x-slot>
        <x-slot name="tamanioTexto">2xl</x-slot>
        <x-slot name="icono"><i class="fa fa-bullhorn text-4xl text-gray-900"></i></x-slot>
        <x-slot name="texto">Anuncios</x-slot>
        <x-slot name="contador"><p class="text-gray-500 text-4xl">{{count($anuncios)}}</p></x-slot>
    </x-cards>

    <x-cards href="{{ route('usuarios') }}">
        <x-slot name="color">gray-900</x-slot>
        <x-slot name="tamanioTexto">2xl</x-slot>
        <x-slot name="icono"><i class="fa fa-user text-4xl text-gray-900"></i></x-slot>
        <x-slot name="texto">Usuarios</x-slot>
        <x-slot name="contador"><p class="text-gray-500 text-4xl">{{count($usuarios)}}</p></x-slot>
    </x-cards>

</div>

<table class="table table-borderless table-striped col-12">
    <thead class="thead-dark">
        <tr>
            <td style="vertical-align: middle">
                <span style="background-color: white; font-size: 15px; margin-left: {{($loop->depth == 2) ? '0' : $loop->depth*20}}px" class="subtitulo badge badge-large badge-pill">
                    {{ $hijo->nombre }}

                    <a href="/cuentas/{{$hijo->id}}" role="button" class="text-success" data-toggle="tooltip" data-placement="top" title="Detalle" style="margin-left: 10px">
                        <i class="far fa-eye"></i>
                    </a>

                    <a href="/cuentas/{{$hijo->id}}/edit" role="button" class="text-info" data-toggle="tooltip" data-placement="top" title="Modificar">
                        <i class="fas fa-edit"></i>
                    </a>

                    @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA')
                        <a href="/cuentas/{{$hijo->id}}/delete" role="button" class="text-danger" data-toggle="tooltip" data-placement="top" title="Desincorporar">
                            <i class="fa fa-reply"></i>
                        </a>
                    @endif
                </span>
            </td>

            @php
                $hijos = compras\ContCuenta::where('pertenece_a', $hijo->id)->get();
            @endphp
        </tr>

        <tr>
            @if ($hijos->count())
                <td style="vertical-align: middle">
                    @foreach ($hijos as $hijo)
                        @include('pages.contabilidad.cuentas.recursivo')
                    @endforeach
                </td>
            @endif
        </tr>
    </thead>
</table>

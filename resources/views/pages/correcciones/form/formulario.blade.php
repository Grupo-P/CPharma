<form action="{{ route('atributos.excel') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(!isset($resultado) && !$formularioConfirmado)
        <div class="form-group">
            <label>
                <span class="font-weight-bold mb-1">Atributos</span>
                <select name="atributo" id="atributo" class="text-uppercase form-control" required>
                    <option value="-1">-- Selecciona un atributo --</option>
                    @foreach ($listaAtributos as $index => $atributo)
                        <option value="{{ $atributo['id'] }}">{{ $atributo['nombre'] }}</option>
                    @endforeach
                </select>
                @if ($errors->has('atributo'))
                    <small class="text-danger">{{ $errors->first('atributo') }}</small>
                @endif
            </label>
        </div>
    @else
        <input type="hidden" name="atributo" value="{{ old('atributo') }}">
    @endif

    <div class="form-group">
        <label>
            <span class="font-weight-bold mb-1">{{ (isset($resultado) && $formularioConfirmado) ? 'Carga de nuevo el excel para confirmar':'Cargar excel' }}</span>
            <input class="form-control" type="file" name="excel_file">
            @if ($errors->has('excel_file'))
            @php
            @endphp
                <small class="text-danger">{{ $errors->first('excel_file') }}</small>
            @endif
        </label>
    </div>

    <div class="form-group {{ (!isset($resultado) && !$formularioConfirmado) ? 'd-block':'d-none' }}">
        <label class="alert alert-success" style="cursor: pointer;">
            <span class="font-weight-bold mb-1">Agregar</span>
            <input type="radio" name="accion_ejecutar" id="accion_ejecutar" value="agregar" 
                {{ old('accion_ejecutar') == 'agregar' ? 'checked':null}}
            >
        </label>

        <label class="alert alert-danger" style="cursor: pointer;">
            <span class="font-weight-bold mb-1">Quitar</span>
            <input type="radio" name="accion_ejecutar" id="accion_ejecutar2" value="quitar" 
                {{ old('accion_ejecutar') == 'quitar' ? 'checked':null}}
            >
        </label>

        @if ($errors->has('accion_ejecutar'))
            <small class="text-danger">{{ $errors->first('accion_ejecutar') }}</small>
        @endif
    </div>

    @if(!$formularioConfirmado)
        <div class="alert alert-secondary">
            <p>
                <span class="font-weight-bold">Nota:</span><br/>
                El archivo excel debe contener al menos una columna con la palabra <strong>(barra)</strong><br>
                El archivo debe ser una extension de excel valida <strong>(xlsx, xls)</strong>
            </p>

        </div>
    @endif

    @if ($formularioConfirmado && isset($resultado))
        <div class="alert alert-danger">
            <p class="mb-0">Estas apunto de <strong class="text-uppercase font-weight-bold">{{ old('accion_ejecutar') == 'agregar' ? 'agregar':'quitar' }}</strong> el atributo <strong class="text-uppercase font-weight-bold">{{ $resultado['atributo'] }}</strong> a <strong class="font-weight-bold">{{ count($resultado['exitoso']) }}</strong> Artículos</p>
        </div>
    @endif

    <div class="form-group">
        @if ($formularioConfirmado && isset($resultado))
            <input type="hidden" name="confirmado" value="si">
            <input type="hidden" name="excel_nombre" value="{{ $resultado['excel_nombre'] }}">
            <a class="btn btn-danger" href="{{ route('atributos.masivos') }}">Atrás</a>
        @endif
        <button type="submit" class="btn btn-success mr-4">
            {{ isset($resultado) && $formularioConfirmado ? 'Confirmar':'Cargar'}}
        </button>
    </div>
</form>
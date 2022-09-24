<div class="form-group">
    {!! Form::label('name', 'Nombre') !!} <span class="text-danger"><strong>*</strong></span>
    {!! Form::text('name', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el nombre del rol...']) !!}

    @error('name')
        <small class="text-danger">{{$message}}</small>
    @enderror

    {!! Form::hidden('guard_name', 'web', null) !!}
</div>

<div class="form-group mt-5 col-12">
    <label class="h4">Listado de permisos</label>
    <hr/>
    @foreach($grupos_permisos as $permisos)
        <label class="h6 mt-5">{{$permisos[0]}}</label>  
        <br>      
        @foreach($permisos[1] as $permiso)
            <li style="display:inline-block;" class="col-md-2 col-sm-12">
                {!! Form::checkbox('permissions[]', $permiso->id, null, ['class' => 'mr-1']) !!}
                {{$permiso->description}}
            </li>
        @endforeach 
        </br>
    @endforeach
</div>
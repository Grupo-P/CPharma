<div class="form-group">
    {!! Form::label('variable', 'Variable') !!}
    {!! Form::text('variable', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el nombre de la variable...']) !!}

    @error('variable')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('valor', 'Valor') !!}
    {!! Form::text('valor', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el valor de la variable...']) !!}

    @error('valor')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">                    
    {!! Form::label('descripcion', 'Descripcion') !!}
    {!! Form::textarea('descripcion', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese la descripcion de la variable...']) !!}
</div>
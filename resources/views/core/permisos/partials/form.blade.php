<div class="form-group">
    {!! Form::label('name', 'Nombre') !!} <span class="text-danger"><strong>*</strong></span>
    {!! Form::text('name', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el nombre del permiso...']) !!}

    @error('name')
        <small class="text-danger">{{$message}}</small>
    @enderror

    {!! Form::hidden('guard_name', 'web', null) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Descripción') !!} <span class="text-danger"><strong>*</strong></span>
    {!! Form::text('description', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese la descripción del permiso...']) !!}

    @error('description')
        <small class="text-danger">{{$message}}</small>
    @enderror    
</div>
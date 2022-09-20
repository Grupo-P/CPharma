<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el nombre del usuario...']) !!}

    @error('name')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('email', 'Email') !!}
    {!! Form::text('email', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el email del usuario...']) !!}

    @error('email')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('password', 'Contraseña') !!}
    {!! Form::text('password', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese la contraseña del usuario...']) !!}

    @error('password')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>
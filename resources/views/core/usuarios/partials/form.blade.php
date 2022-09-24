<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el nombre del usuario...']) !!}

    @error('name')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('documento', 'Cedula') !!}
    {!! Form::text('documento', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese la cedula del usuario...']) !!}

    @error('documento')
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

<div class="form-group mt-5 col-12">
    <label class="h5">Listado de roles</label>
    <hr/>
    @foreach($roles as $role)
        <label style="display:inline-block;" class="col-md-2 col-sm-12">
            {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'mr-1']) !!}
            {{$role->name}}            
        </label>
    @endforeach
</div>
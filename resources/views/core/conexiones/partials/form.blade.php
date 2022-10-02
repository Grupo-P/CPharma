<div class="form-group">
    {!! Form::label('nombre', 'Nombre') !!}
    {!! Form::text('nombre', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el nombre de la conexión...']) !!}

    @error('nombre')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('nombre_mostrar', 'Nombre para mostrar') !!}
    {!! Form::text('nombre_mostrar', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el nombre para mostrar de la conexión...']) !!}   
</div>

<div class="form-group">
    {!! Form::label('siglas', 'Siglas') !!}
    {!! Form::text('siglas', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese las siglas de la conexión...']) !!}

    @error('siglas')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('ip_address', 'Dirrección IP') !!}
    {!! Form::text('ip_address', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese la dirección IP de la conexión...']) !!}
</div>

<div class="form-group">
    {!! Form::label('driver_db', 'Driver de la base de datos',) !!}
    {!! Form::text('driver_db', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el driver de la conexión...']) !!}

    @error('driver_db')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('instancia_db', 'Instancia de la base de datos') !!}
    {!! Form::text('instancia_db', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese la instancia de la conexión...']) !!}

    @error('instancia_db')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('usuario', 'Usuario') !!}
    {!! Form::text('usuario', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el usuario de la conexión...']) !!}

    @error('usuario')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('clave', 'Clave') !!}
    {!! Form::text('clave', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese la clave de la conexión...']) !!}

    @error('clave')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('db_online', 'Base de datos online') !!}
    {!! Form::text('db_online', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el nombre de la base de datos online...']) !!}

    @error('db_online')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('db_offline', 'Base de datos offline') !!}
    {!! Form::text('db_offline', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el nombre de la base de datos offline...']) !!}
</div>

<div class="form-group">
    {!! Form::label('online', 'Es online') !!}    
    {!! Form::select('online', [''=> 'Seleccione una opción', '1' => 'SI', '0' => 'NO'], '', [ 'class' => 'form-control']); !!}

    @error('online')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>
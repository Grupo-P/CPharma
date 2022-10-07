<div class="form-group">
    <img src="{{$url_imagen}}" alt="Foto de perfil" class="img-circle elevation-2" style="display:block;" width="80" height="80"/>
</div>

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

<div class="row">
    <div class="col-2 text-center" style="margin-top:1.5%">
        <img id="pictureProfile" src="{{$url_imagen}}" alt="Foto de perfil" class="img-circle elevation-2" width="80" height="80"/>
    </div>

    <div class="col">
        <div class="form-group">
            {!! Form::label('file', 'Imagen que se mostrará en el perfil') !!}
            {!! Form::file('file', ['class' => 'form-control-file'] ) !!}
        </div>
        <ul>
            <li>Esta imagen sera visible por el resto de usuarios del sistema.</li>
            <li>La imagen debe tener un tamaño menor a 10MB.</li>
            <li>Los formatos aceptados son png, jpg y jpeg.</li>
        </ul>        
    </div>
</div>

<script>
    //Cambiar Imager
    document.getElementById('file').addEventListener("change",cambiarImagen);

    function cambiarImagen(event){
        var file = event.target.files[0];

        var reader = new FileReader();
        reader.onload = (event)=>{
            document.getElementById("pictureProfile"). setAttribute('src', event.target.result);
        };
        reader. readAsDataURL(file) ;
    }
</script>

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
<div class="form-group">
    {!! Form::label('name', 'Nombre') !!} <span class="text-danger"><strong>*</strong></span>
    {!! Form::text('name', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el nombre del rol...']) !!}

    @error('name')
        <small class="text-danger">{{$message}}</small>
    @enderror

    {!! Form::hidden('guard_name', 'web', null) !!}
</div>
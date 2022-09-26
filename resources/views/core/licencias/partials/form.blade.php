<div class="form-group">
    {!! Form::label('hash1', 'Hash1') !!}
    {!! Form::text('hash1', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el hash1...']) !!}

    @error('hash1')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('hash2', 'Hash2') !!}
    {!! Form::text('hash2', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el hash2...']) !!}

    @error('hash2')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('hash3', 'Hash3') !!}
    {!! Form::text('hash3', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el hash3...']) !!}

    @error('hash3')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('hash4', 'Hash4') !!}
    {!! Form::text('hash4', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el hash4...']) !!}

    @error('hash4')
        <small class="text-danger">{{$message}}</small>
    @enderror
</div>
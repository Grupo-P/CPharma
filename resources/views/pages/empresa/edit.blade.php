@extends('layouts.model')

@section('title')
    Empresa
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-edit"></i>
        Modificar empresa
	</h1>
	<hr class="row align-items-start col-12">
	<div class="card border-success" style="max-width: 40rem;">
        <div class="card-body">
            {!! Form::model($empresa, ['route' => ['empresa.update', $empresa], 'method' => 'PUT']) !!}
                <fieldset>
                    <div class="form-group">
                        <div class="col-md-10">
                            {!! Form::label('nombre', 'Nombre') !!}
                            {!! Form::text('nombre', null, [ 'class' => 'form-control', 'placeholder' => 'Farmacia Tierra Negra C.A.', 'autofocus']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            {!! Form::label('rif', 'RIF') !!}
                            {!! Form::text('rif', null, [ 'class' => 'form-control', 'placeholder' => 'J-400145717']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            {!! Form::label('telefono', 'Telefono') !!}
                            {!! Form::text('telefono', null, [ 'class' => 'form-control', 'placeholder' => '0261-7988326']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            {!! Form::label('direccion', 'Direccion') !!}
                            {!! Form::textarea('direccion', null, [ 'class' => 'form-control', 'placeholder' => 'Calle 72 esquina av. 14A local nro. 13a-99 sector tierra negra Maracaibo Zulia zona postal 4002', 'rows' => '2']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            {!! Form::submit('Guardar', ['class' => 'btn btn-success btn-md', 'data-toggle' =>'modal', 'data-target' => 'exampleModalCenter']) !!}
                        </div>
                    </div>
                </fieldset>
            {!! Form::close()!!}
        </div>
    </div>
@endsection
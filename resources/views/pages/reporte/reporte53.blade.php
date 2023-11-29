@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Ventas de concursos
	</h1>
	<hr class="row align-items-start col-12">

	<?php
		include(app_path().'\functions\config.php');
		include(app_path().'\functions\functions.php');
		include(app_path().'\functions\querys_mysql.php');
		include(app_path().'\functions\querys_sqlserver.php');

		$InicioCarga = new DateTime("now");

		if (isset($_GET['SEDE'])){
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
		}
		echo '<hr class="row align-items-start col-12">';

		FG_Guardar_Auditoria('CONSULTAR','REPORTE','Ventas de concursos');
	?>

    <form action="{{ route('reporte.generar') }}" method="POST" enctype="multipart/form-data" class="row">
        @csrf
        {{-- Mensaje --}}
        <div class="col-12">
            <div class="alert alert-secondary">
                <p>
                    <span class="font-weight-bold">Nota:</span><br/>
                    Selecciona el rango de fecha del concurso<br>
                    El archivo excel debe contener al menos una columna con la palabra <strong>(barra)</strong><br>
                    El archivo debe ser una extension de excel valida <strong>(xlsx, xls)</strong>
                </p>
            </div>
        </div>

        {{-- Fecha inicio --}}
        <div class="form-group col-12 col-md-6">
            <label class="w-100">
                <span class="font-weight-bold mb-2 d-block">Fecha de inicio</span>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required value="{{ old('fecha_inicio') }}">
            </label>

            @if ($errors->has('fecha_inicio'))
                <small class="text-danger">{{ $errors->first('fecha_inicio') }}</small>
            @endif
        </div>

        {{-- Fecha limite --}}
        <div class="form-group col-12 col-md-6">
            <label class="w-100">
                <span class="font-weight-bold mb-2 d-block">Fecha limite</span>
                <input type="date" name="fecha_limite" id="fecha_limite" class="form-control" required value="{{ old('fecha_limite') }}">
            </label>

            @if ($errors->has('fecha_limite'))
                <small class="text-danger">{{ $errors->first('fecha_limite') }}</small>
            @endif
        </div>

        {{-- Excel --}}
        <div class="form-group col-6">
            <label class="w-100">
                <span class="font-weight-bold mb-2 d-block">Cargar excel</span>
                <input class="form-control" type="file" name="excel_file" required>
                @if ($errors->has('excel_file'))
                    <small class="text-danger">{{ $errors->first('excel_file') }}</small>
                @endif
            </label>
        </div>

        <div class="form-group col-12">
            <input type="hidden" name="sede" value="{{ $_GET['SEDE'] }}">
            <button type="submit" class="btn btn-success mr-4">
               Generar reporte
            </button>
        </div>
    </form>

    <?php 
        $FinCarga = new DateTime("now");
        $IntervalCarga = $InicioCarga->diff($FinCarga);
        echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");

    ?>
@endsection


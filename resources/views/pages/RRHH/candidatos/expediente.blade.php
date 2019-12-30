@extends('layouts.model')

@section('title', 'Mostrar candidato')

@section('estilosInternos')
  <style>
    td {
      width: 50%;
    }
  </style>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="far fa-eye"></i>
    Detalles del candidato
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/candidatos/" method="POST" style="display: inline;">
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br/><br/>

  {{-- ******************* CANDIDATO ******************* --}}
  <table class="table table-borderless table-striped">
    <thead class="thead-dark">
      <tr>
        <th scope="row" colspan="2">Candidato</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">Nombre</th>
        <td>{{$candidatos->nombres . " " . $candidatos->apellidos}}</td>
      </tr>

      <tr>
        <th scope="row">Cédula</th>
        <td>{{$candidatos->cedula}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono celular</th>
        <td>{{$candidatos->telefono_celular}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono de habitación</th>
        <td>{{$candidatos->telefono_habitacion}}</td>
      </tr>

      <tr>
        <th scope="row">Correo</th>
        <td>{{$candidatos->correo}}</td>
      </tr>

      <tr>
        <th scope="row">Como nos contactó</th>
        <td>{{$candidatos->como_nos_contacto}}</td>
      </tr>

      <tr>
        <th scope="row">Tipo de relación</th>
        <td>{{$candidatos->tipo_relacion}}</td>
      </tr>

      <tr>
        <th scope="row">Relaciones laborales</th>
        <td>{{$candidatos->relaciones_laborales}}</td>
      </tr>

      <tr>
        <th scope="row">Dirección</th>
        <td>{{$candidatos->direccion}}</td>
      </tr>

      <tr>
        <th scope="row">Experiencia laboral</th>
        <td>{{$candidatos->experiencia_laboral}}</td>
      </tr>

      <tr>
        <th scope="row">Observaciones</th>
        <td>{{$candidatos->observaciones}}</td>
      </tr>

      <tr>
        <th scope="row">Estatus</th>
        <td>{{$candidatos->estatus}}</td>
      </tr>

      <tr>
        <th scope="row">Motivo de rechazo</th>
        <td>{{$candidatos->motivo_rechazo}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$candidatos->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$candidatos->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$candidatos->user}}</td>
      </tr>
    </tbody>
  </table>

  <?php
    //-------------------- PRUEBAS DEL CANDIDATO --------------------//
    $candidato_pruebas = compras\RH_Candidato_Prueba::where('rh_candidatos_id', $candidatos->id)
    ->orderBy('id', 'desc')
    ->first();

    //-------------------- ENTREVISTAS DEL CANDIDATO --------------------//
    $entrevista = compras\RH_Entrevista::where('rh_candidatos_id', $candidatos->id)
    ->orderBy('id', 'desc')
    ->first();

    //-------------------- PRACTICAS DEL CANDIDATO --------------------//
    $practicas = compras\RH_Practica::where('rh_candidatos_id', $candidatos->id)
    ->orderBy('id', 'desc')
    ->first();

    //-------------------- REFERENCIAS DEL CANDIDATO --------------------//
    $referencias = compras\RH_Candidato_EmpresaReferencia::where('rh_candidatos_id', $candidatos->id)
    ->orderBy('id', 'desc')
    ->first();

    //-------------------- CONTACTOS DE EMPRESAS --------------------//
    $contacto_emp = null;

    if(!is_null($referencias)) {
      $contacto_emp = compras\RH_ContactoEmp::where('rh_emprf_id', $referencias->rh_empresaref_id)
      ->orderBy('id', 'desc')
      ->first();
    }

    //-------------------- EXAMENES MEDICOS DEL CANDIDATO --------------------//
    $examenes = compras\RH_ExamenesM::where('rh_candidatos_id', $candidatos->id)
    ->orderBy('id', 'desc')
    ->first();
  ?>

  <?php
    if(!is_null($candidato_pruebas)) {
      $prueba = compras\RH_Prueba::find($candidato_pruebas->rh_pruebas_id);
  ?>
  <table class="table table-borderless table-striped">
    <thead class="thead-dark">
      <tr>
        <th scope="row" colspan="2">Fase #1</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">Facilitador</th>
        <td>{{$candidato_pruebas->facilitador}}</td>
      </tr>

      <tr>
        <th scope="row">Fecha de prueba</th>
        <td>{{date("d-m-Y", strtotime($candidato_pruebas->fecha))}}</td>
      </tr>

      <tr>
        <th scope="row">Tipo de prueba</th>
        <td>{{$prueba->tipo_prueba}}</td>
      </tr>

      <tr>
        <th scope="row">Nombre de prueba</th>
        <td>{{$prueba->nombre_prueba}}</td>
      </tr>

      <tr>
        <th scope="row">Resultado de prueba</th>
        <td>{{$candidato_pruebas->resultado}}</td>
      </tr>

      <tr>
        <th scope="row">Observaciones</th>
        <td>{{$candidato_pruebas->observaciones}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$candidato_pruebas->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$candidato_pruebas->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$candidato_pruebas->user}}</td>
      </tr>
    </tbody>
  </table>
  <?php
    }
  ?>

  <?php
    if(!is_null($entrevista)) {
      $vacante = compras\RH_Vacante::find($entrevista->rh_vacantes_id);
  ?>
  <table class="table table-borderless table-striped">
    <thead class="thead-dark">
      <tr>
        <th scope="row" colspan="2">Fase #2</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">Entrevistadores</th>
        <td>{{$entrevista->entrevistadores}}</td>
      </tr>

      <tr>
        <th scope="row">Fecha de entrevista</th>
        <td>{{date("d-m-Y", strtotime($entrevista->fecha_entrevista))}}</td>
      </tr>

      <tr>
        <th scope="row">Lugar de entrevista</th>
        <td>{{$entrevista->lugar}}</td>
      </tr>

      <tr>
        <th scope="row">Vacante asociada</th>
        <td>
          {{
            $vacante->sede
            . " - " . $vacante->nombre_vacante 
            . " - " . $vacante->departamento
            . " - " . $vacante->turno
            . " - " . $vacante->dias_libres
          }}
        </td>
      </tr>

      <tr>
        <th scope="row">Observaciones</th>
        <td>{{$entrevista->observaciones}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$entrevista->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$entrevista->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$entrevista->user}}</td>
      </tr>
    </tbody>
  </table>
  <?php
    }
  ?>

  <?php
    if(!is_null($practicas)) {
  ?>
  <table class="table table-borderless table-striped">
    <thead class="thead-dark">
      <tr>
        <th scope="row" colspan="2">Fase #3</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">Líder de práctica</th>
        <td>{{$practicas->lider}}</td>
      </tr>

      <tr>
        <th scope="row">Lugar de práctica</th>
        <td>{{$practicas->lugar}}</td>
      </tr>

      <tr>
        <th scope="row">Tiempo de práctica (horas)</th>
        <td>{{$practicas->duracion}}</td>
      </tr>

      <tr>
        <th scope="row">Observaciones</th>
        <td>{{$practicas->observaciones}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$practicas->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$practicas->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$practicas->user}}</td>
      </tr>
    </tbody>
  </table>
  <?php
    }
  ?>

  <?php
    if(!is_null($referencias)) {
      $empresa = compras\RH_EmpresaReferencia::find($referencias->rh_empresaref_id);
  ?>
  <table class="table table-borderless table-striped">
    <thead class="thead-dark">
      <tr>
        <th scope="row" colspan="2">Fase #4</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">Nombre de la empresa</th>
        <td>{{$empresa->nombre_empresa}}</td>
      </tr>

      <tr>
        <th scope="row">Dirección de la empresa</th>
        <td>{{$empresa->direccion}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono de la empresa</th>
        <td>{{$empresa->telefono}}</td>
      </tr>

      <tr>
        <th scope="row">Correo de la empresa</th>
        <td>{{$empresa->correo}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$referencias->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$referencias->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$referencias->user}}</td>
      </tr>
    </tbody>
  </table>
  <?php
    }
  ?>

  <?php
    if(!is_null($contacto_emp)) {
  ?>
  <table class="table table-borderless table-striped">
    <thead class="thead-dark">
      <tr>
        <th scope="row" colspan="2">Fase #5</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">Representante de empresa</th>
        <td>{{$contacto_emp->nombre . " " . $contacto_emp->apellido}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono del representante</th>
        <td>{{$contacto_emp->telefono}}</td>
      </tr>

      <tr>
        <th scope="row">Correo del representante</th>
        <td>{{$contacto_emp->correo}}</td>
      </tr>

      <tr>
        <th scope="row">Cargo del representante</th>
        <td>{{$contacto_emp->cargo}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$contacto_emp->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$contacto_emp->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$contacto_emp->user}}</td>
      </tr>
    </tbody>
  </table>
  <?php
    }
  ?>

  <?php
    if(!is_null($examenes)) {
      $examen_lab = compras\RHI_Examen_Laboratorio::where('rh_examenes_id', $examenes->id)
      ->orderBy('id', 'desc')
      ->first();

      if(!is_null($examen_lab)) {
        $laboratorio = compras\RH_Laboratorio::find($examen_lab->rh_laboratorio_id);
  ?>
  <table class="table table-borderless table-striped">
    <thead class="thead-dark">
      <tr>
        <th scope="row" colspan="2">Fase #6</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">Nombre del consultorio</th>
        <td>{{$laboratorio->nombre}}</td>
      </tr>

      <tr>
        <th scope="row">Dirección del consultorio</th>
        <td>{{$laboratorio->direccion}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono fijo</th>
        <td>{{$laboratorio->telefono_fijo}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono celular</th>
        <td>{{$laboratorio->telefono_celular}}</td>
      </tr>

      <tr>
        <th scope="row">Representante del consultorio</th>
        <td>{{$examen_lab->representante}}</td>
      </tr>

      <tr>
        <th scope="row">Cargo del representante</th>
        <td>{{$examen_lab->cargo}}</td>
      </tr>

      <tr>
        <th scope="row">Estado del candidato</th>
        <td>{{$examenes->estado}}</td>
      </tr>

      <tr>
        <th scope="row">Observaciones</th>
        <td>{{$examenes->observaciones}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$examen_lab->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$examen_lab->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$examen_lab->user}}</td>
      </tr>
    </tbody>
  </table>
  <?php
    }
  }
  ?>
@endsection
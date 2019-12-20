@extends('layouts.model')

@section('title', 'Mostrar candidato')

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
    $contacto_emp = compras\RH_Practica::where('rh_candidatos_id', $candidatos->id)
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
        <td>{{$empresa->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$empresa->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$empresa->user}}</td>
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
        <th scope="row" colspan="2">Fase #3</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">Líder de práctica</th>
        <td>{{$contacto_emp->lider}}</td>
      </tr>

      <tr>
        <th scope="row">Lugar de práctica</th>
        <td>{{$contacto_emp->lugar}}</td>
      </tr>

      <tr>
        <th scope="row">Tiempo de práctica (horas)</th>
        <td>{{$contacto_emp->duracion}}</td>
      </tr>

      <tr>
        <th scope="row">Observaciones</th>
        <td>{{$contacto_emp->observaciones}}</td>
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
@endsection
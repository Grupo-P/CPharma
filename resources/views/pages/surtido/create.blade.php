@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
    * {
      box-sizing: border-box;
    }
    .autocomplete {
      position: relative;
      display: inline-block;
    }
    input {
      border: 1px solid transparent;
      background-color: #f1f1f1;
      border-radius: 5px;
      padding: 10px;
      font-size: 16px;
    }
    input[type=text] {
      background-color: #f1f1f1;
      width: 100%;
    }
    .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      z-index: 99;
      top: 100%;
      left: 0;
      right: 0;
    }
    .autocomplete-items div {
      padding: 10px;
      cursor: pointer;
      background-color: #fff;
      border-bottom: 1px solid #d4d4d4;
    }
    .autocomplete-items div:hover {
      background-color: #e9e9e9;
    }
    .autocomplete-active {
      background-color: DodgerBlue !important;
      color: #ffffff;
    }
  </style>
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-box"></i> Agregar nuevo surtido
    </h1>

    <hr class="row align-items-start col-12">

    @csrf

    <form id="form" autocomplete="off" action="" target="_blank">
      <table style="width:100%;">
        <tr>
          <td colspan="4">
            <div class="autocomplete" style="width:90%;">
              <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()">
            </div>

            <input id="myId" name="Id" type="hidden">

            <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
          </td>
        </tr>

        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
          <td colspan="4">
            <div class="autocomplete" style="width:90%;">
              <input id="myInputCB" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCB()">
            </div>

            <input id="SEDE" name="SEDE" type="hidden" value="{{ $_GET['SEDE'] }}">

            <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
          </td>
        </tr>

        <tr>
          <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
          <td colspan="4">
            <div class="autocomplete" style="width:90%;">
              <input id="myInputCI" type="text" name="CodInt" placeholder="Ingrese el codigo interno del articulo " onkeyup="conteoCI()">
            </div>
            <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
          </td>
        </tr>
      </table>
    </form>

    <br>

    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Codigo de barra</td>
          <th scope="col">Código interno</td>
          <th scope="col">Descripción</td>
          <th scope="col">Existencia</td>
          <th scope="col">Cantidad</td>
        </tr>
      </thead>
      <tbody id="content">
      </tbody>
  </table>

  <br>

  <button onclick="procesar()" type="button" class="btn btn-outline-success">Procesar</button>
@endsection

@section('scriptsFoot')
    @if($ArtJson!="")
        <script type="text/javascript">
            ArrJs = eval({!! $ArtJson !!});
            autocompletado(document.getElementById("myInput"),document.getElementById("myId"), ArrJs);
        </script>
    @endif

    @if($CodJson!="")
        <script type="text/javascript">
            ArrJsCB = eval({!! $CodJson !!});
            autocompletadoCB(document.getElementById("myInputCB"),document.getElementById("myId"), ArrJsCB);
        </script>
    @endif

    @if($CodIntJson!="")
        <script type="text/javascript">
          ArrJsInt = eval({!! $CodIntJson !!});
          autocompletadoCI(document.getElementById("myInputCI"),document.getElementById("myId"), ArrJsInt);
        </script>
    @endif

    <script>
        var i = 1;
        articulos = [];

        $('#form').submit(function () {
            event.preventDefault();

            data = $(this).serialize();

            $.ajax({
                type: 'GET',
                url: window.location.href,
                data: data,
                success: function (response) {
                    cantidad = 0;

                    if (cantidad != '' || !Number.isInteger(cantidad) || cantidad <= 0) {
                        cantidad = prompt('Ingrese la cantidad (número entero)');
                    }

                    html = `
                        <tr>
                            <td align="center"><strong>${i}</strong></td>
                            <td align="center">${response.CodigoBarra}</td>
                            <td align="center">${response.CodigoInterno}</td>
                            <td align="center">${response.Descripcion}</td>
                            <td align="center">${response.Existencia ? response.Existencia : 0}</td>
                            <td align="center">${cantidad}</td>
                        </tr>
                    `;

                    articulos.push({
                        id_articulo: response.IdArticulo,
                        codigo_articulo: response.CodigoInterno,
                        codigo_barra: response.CodigoBarra,
                        descripcion: response.Descripcion,
                        existencia_actual: response.Existencia ? response.Existencia : 0,
                        cantidad: cantidad
                    });

                    $('#content').append(html);

                    $('#myInput').val('');
                    $('#myInputCB').val('');
                    $('#myInputCI').val('');
                    $('#myId').val('');

                    i = i + 1;
                },
                error: function (error) {
                    $('body').html(error.responseText);
                }
            });
        });

        function procesar() {
            if (articulos.length > 0) {
                $.ajax({
                    type: 'POST',
                    data: {
                        articulos: articulos,
                        _token: '{{ csrf_token() }}'
                    },
                    url: window.location.origin + '/surtido',
                    success: function (response) {
                        window.location.href = '/surtido';
                    },
                    error: function (error) {
                        $('body').html(error.responseText);
                    }
                });
            } else {
                alert('Debe agregar artículos para procesar este surtido');
            }
        }
    </script>
@endsection

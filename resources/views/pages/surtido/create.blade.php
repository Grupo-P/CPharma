@extends('layouts.model')

@section('title')
  Surtido de gavetas
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
          <td>
            <label>Tipo de surtido *</label>
          </td>

          <td>
            
            <label class="mr-5" for="medicamentos">
                <input type="radio" name="tipo_surtido" id="medicamentos" value="MEDICAMENTOS"> Medicamentos
            </label>

            <label class="mr-5" for="otc">
                <input type="radio" name="tipo_surtido" id="otc" value="OTC - VIT - NAT"> OTC - VIT - NAT
            </label>

            <label class="mr-5" for="descartables">
                <input type="radio" name="tipo_surtido" id="descartables" value="DESCARTABLES"> Descartables
            </label>
          </td>
        </tr>

        <tr>
            <td colspan="4">
                <hr>
            </td>
        </tr>

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
              <input autofocus id="myInputCB" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCB()">
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
            <th scope="col">Acciones</td>
        </tr>
      </thead>
      <tbody id="content">
        @foreach(compras\SurtidoDetalle::where('control', $surtido->control)->get() as $detalle)
            <tr>
                <td align="center"><strong>{{ $loop->iteration }}</strong></td>
                <td align="center">{{ $detalle->codigo_barra }}</td>
                <td align="center">{{ $detalle->codigo_articulo }}</td>
                <td align="center">{{ $detalle->descripcion }}</td>
                <td align="center">{{ $detalle->existencia_actual }}</td>
                <td align="center">{{ $detalle->cantidad }}</td>
                <td align="center">
                    <button data-id-articulo="{{ $detalle->id_articulo }}" onclick="eliminar(this)" class="btn btn-sm btn-outline-danger">
                        <i class="fa fa-trash"></i> Eliminar
                    </button>
                </td>
            </tr>
        @endforeach
      </tbody>
  </table>

  <input type="hidden" name="control" value="{{ $surtido->control }}">

  <br>

  <button onclick="procesar()" type="button" class="btn btn-outline-success">Procesar</button>

  <div class="modal" tabindex="-1" id="modalCantidad" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Introduzca la cantidad</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="number" class="form-control" name="cantidad" required>

        <input type="hidden" class="form-control" name="id_articulo" required>
        <input type="hidden" class="form-control" name="codigo_interno" required>
        <input type="hidden" class="form-control" name="codigo_barra" required>
        <input type="hidden" class="form-control" name="existencia" required>
        <input type="hidden" class="form-control" name="descripcion" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" onclick="agregar()">Agregar</button>
      </div>
    </div>
  </div>
  </div>
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
            autocompletadoCBScan(document.getElementById("myInputCB"),document.getElementById("myId"), ArrJsCB);
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
        articulos = {!! compras\SurtidoDetalle::where('control', $surtido->control)->get() !!};

        $('[name=cantidad]').keypress(function (event) {
            value = $(this).val();

            if (value > 0) {
                if (event.key == 'Enter') {
                    agregar();
                }
            }

        });

        $('#form').submit(function () {
            event.preventDefault();

            id = $('#myId').val();

            if (!id) {
                return false;
            }

            data = $(this).serialize();

            $.ajax({
                type: 'GET',
                url: window.location.href,
                data: data,
                success: function (response) {
                    if (response.Descripcion == '') {
                        alert('No se ha encontrado ningún artículo');
                        $('#myInputCB').val('');
                        $('#myInputCB').focus();
                        return false;
                    }

                    $('[name=id_articulo]').val(response.IdArticulo);
                    $('[name=codigo_interno]').val(response.CodigoInterno);
                    $('[name=codigo_barra]').val(response.CodigoBarra);
                    $('[name=existencia]').val(response.Existencia ? response.Existencia : 0);
                    $('[name=descripcion]').val(response.Descripcion);

                    $('#modalCantidad').modal('show');

                    $('[name=cantidad]').focus();
                },
                error: function (error) {
                    alert('Ha ocurrido un error, por favor intente nuevamente');

                    console.log('Datos: ' + data);
                    console.log('ID: ' + id);
                }
            });
        });

        $('#myInputCB').keypress(function (event) {
            key = event.key;
            value = $(this).val();

            if (key == 'Enter') {
                event.preventDefault();

                if (value != '') {
                    index = ArrJsCB.indexOf(value);
                    index = index + 1;
                    id = ArrJsCB[index];

                    $('#myId').val(id);

                    data = $('#form').serialize();

                    $.ajax({
                        type: 'GET',
                        url: window.location.href,
                        data: data,
                        success: function (response) {
                            if (response.Descripcion == '') {
                                alert('No se ha encontrado ningún artículo');
                                $('#myInputCB').val('');
                                $('#myInputCB').focus();
                                return false;
                            }

                            $('[name=id_articulo]').val(response.IdArticulo);
                            $('[name=codigo_interno]').val(response.CodigoInterno);
                            $('[name=codigo_barra]').val(response.CodigoBarra);
                            $('[name=existencia]').val(response.Existencia ? response.Existencia : 0);
                            $('[name=descripcion]').val(response.Descripcion);

                            $('#modalCantidad').modal('show');

                            $('[name=cantidad]').focus();
                        },
                        error: function (error) {
                            alert('Ha ocurrido un error, por favor intente nuevamente');

                            console.log('Datos: ' + data);
                            console.log('ID: ' + id);
                        }
                    });
                }
            }
        });

        function procesar() {
            control = $('[name=control]').val();
            tipo_surtido = $('[name=tipo_surtido]:checked').val();

            if (!$('[name=tipo_surtido]').is(':checked')) {
                alert('El campo tipo de surtido es obligatorio');
                return false;
            }

            if (articulos.length > 0) {
                $.ajax({
                    type: 'POST',
                    data: {
                        articulos: articulos,
                        tipo_surtido: tipo_surtido,
                        control: control,
                        _token: '{{ csrf_token() }}'
                    },
                    url: window.location.origin + '/surtido',
                    success: function (response) {
                        window.location.href = '/surtido';
                    },
                    error: function (error) {
                        alert('Ha ocurrido un error, por favor intente nuevamente');
                        $('body').html(error.responseText);
                    }
                });
            } else {
                alert('Debe agregar artículos para procesar este surtido');
            }
        }

        $('[name=cantidad]').blur(function () {
            value = $(this).val();

            if (value == '' || value <= 0) {
                $('[name=cantidad]').val('');
                $('[name=cantidad]').focus();

                return false;
            }
        });

        $('[name=cantidad]').keypress(function (evento) {
            tecla = (document.all) ? evento.keyCode : evento.which;

            if (tecla == 8) {
                return true;
            }

            patron = /[A-Za-z0-9]/;
            tecla_final = String.fromCharCode(tecla);
            return patron.test(tecla_final);
        });

        function agregar() {
            cantidad = $('[name=cantidad]').val();

            if (cantidad == '') {
                $('[name=cantidad]').val('');
                $('[name=cantidad]').focus();

                return false;
            }

            id_articulo = $('[name=id_articulo]').val();
            codigo_interno = $('[name=codigo_interno]').val();
            codigo_barra = $('[name=codigo_barra]').val();
            descripcion = $('[name=descripcion]').val();
            existencia = $('[name=existencia]').val();
            cantidad = $('[name=cantidad]').val();
            control = $('[name=control]').val();

            cantidad = parseInt(cantidad);

            $.ajax({
                type: 'POST',
                url: '/surtido/agregarArticulo',
                data: {
                    id_articulo: id_articulo,
                    codigo_articulo: codigo_interno,
                    codigo_barra: codigo_barra,
                    descripcion: descripcion,
                    existencia_actual: existencia,
                    cantidad: cantidad,
                    control: control,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    html = `
                        <tr>
                            <td align="center"><strong>${i}</strong></td>
                            <td align="center">${codigo_barra}</td>
                            <td align="center">${codigo_interno}</td>
                            <td align="center">${descripcion}</td>
                            <td align="center">${existencia}</td>
                            <td align="center">${cantidad}</td>
                            <td align="center">
                                <button data-id-articulo="${id_articulo}" onclick="eliminar(this)" class="btn btn-sm btn-outline-danger">
                                    <i class="fa fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    `;

                    articulos.push({
                        id_articulo: id_articulo,
                        codigo_articulo: codigo_interno,
                        codigo_barra: codigo_barra,
                        descripcion: descripcion,
                        existencia_actual: existencia,
                        cantidad: cantidad
                    });

                    $('#content').prepend(html);

                    $('#myInput').val('');
                    $('#myInputCB').val('');
                    $('#myInputCI').val('');
                    $('#myId').val('');

                    i = i + 1;

                    $('#modalCantidad').modal('hide');
                    $('[name=cantidad]').val('');

                    $('#myInputCB').focus();                    
                },
                error: function (error) {
                    $('body').html(error.responseText);
                }
            });

        }

        function eliminar(that) {
            control = $('[name=control]').val();
            id_articulo = $(that).attr('data-id-articulo');
            token = '{{ csrf_token() }}';

            $.ajax({
                type: 'POST',
                url: '/surtido/eliminar',
                data: {
                    control: control,
                    id_articulo: id_articulo,
                    _token: token
                },
                success: function (data) {
                    $(that).parent().parent().remove();

                    for (var i = articulos.length - 1; i >= 0; i--) {
                        if (articulos[i].id_articulo == id_articulo) {
                            articulos.splice(i, 1);
                        }
                    }
                },
                error: function (error) {
                    $('body').html(error.responseText);
                }
            });
        }
    </script>
@endsection

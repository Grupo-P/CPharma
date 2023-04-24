@extends('layouts.modelUser')

@section('title')
    Tasas de venta
@endsection

@section('estilosInternos')
    <style>
        form table thead + tbody tr td input {text-align:center;}
    </style>
@endsection

@section('scriptsCabecera')
    <script>
        /********************* FUNCIONES VUELTO PAGO MOVIL *********************/
        /* Funcion segun tipo de documento
            // para filtrar el campo documento
            */            

            function tipoDocumento(campo_tipo_doc,campo_documento)
            {
                var tipo_documento=$("#"+campo_tipo_doc).val();

                switch(tipo_documento){
                    case "V":
                        //cedula venezolana
                        //filtrar documento
                        stringWithNumbers = $("#"+campo_documento).val();
                        onlyNumbers = stringWithNumbers.replace(/[^0-9]+/g, ""); // esto retorna '1234'
                        $("#"+campo_documento).val(onlyNumbers);
                        $("#"+campo_documento).attr( "minlength",'7' );
                        $("#"+campo_documento).attr( "maxlength",'8' );
                        $("#"+campo_documento).attr( "min",'1000000' );
                        $("#"+campo_documento).attr( "max",'99999999' );
                        $("#"+campo_documento).attr( "type",'number' );
                        $("#"+campo_documento).val(onlyNumbers);
                        $("#"+campo_documento).attr( "onkeypress",'return /[0-9]/i.test(event.key)' );
                        
                        campoDoc=document.getElementById("documento_cliente_pagoMovil")
                        if (campoDoc.value.length > campoDoc.maxLength) campoDoc.value = campoDoc.value.slice(0, campoDoc.maxLength);

                    break;        
                    case "J":
                        //rif Juridico
                        stringWithNumbers = $("#"+campo_documento).val();
                        onlyNumbers = stringWithNumbers.replace(/[^0-9]/g, ""); // esto retorna '1234'
                        $("#"+campo_documento).val(onlyNumbers);
                        $("#"+campo_documento).attr( "minlength",'9' );
                        $("#"+campo_documento).attr( "maxlength",'9' );
                        $("#"+campo_documento).attr( "min",'' );
                        $("#"+campo_documento).attr( "max",'' );
                        $("#"+campo_documento).attr( "type",'text' );                        

                        $("#"+campo_documento).val(onlyNumbers);        
                        campoDoc=document.getElementById("documento_cliente_pagoMovil")
                        if (campoDoc.value.length > campoDoc.maxLength) campoDoc.value = campoDoc.value.slice(0, campoDoc.maxLength);                
                    break;
                    case "E":
                        //cedula extranjera
                        //filtrar documento
                        stringWithNumbers = $("#"+campo_documento).val();
                        onlyNumbers = stringWithNumbers.replace(/[^0-9]+/g, ""); // esto retorna '1234'
                        $("#"+campo_documento).val(onlyNumbers);
                        $("#"+campo_documento).attr( "minlength",'6' );
                        $("#"+campo_documento).attr( "maxlength",'15' );
                        $("#"+campo_documento).attr( "min",'100000' );
                        $("#"+campo_documento).attr( "max",'999999999999999' );
                        $("#"+campo_documento).attr( "type",'number' );
                        
                        $("#"+campo_documento).val(onlyNumbers);                        
                    break;                          
                }
            }
            function copiarTexto(idCampo){
                
                var texto = $("#"+idCampo).val();
                
               // Crea un campo de texto "oculto"
                var aux = document.createElement("input");

                // Asigna el contenido del elemento especificado al valor del campo
                aux.setAttribute("value", texto);

                // Añade el campo a la página
                document.body.appendChild(aux);

                // Selecciona el contenido del campo
                aux.select();

                // Copia el texto seleccionado
                document.execCommand("copy");

                // Elimina el campo de la página
                document.body.removeChild(aux);            
            }
        $(document).ready(function () {

            
            
            /*################# Verificador de pagos ############*/
            /* carga de pagos ultimos 30 minutos de zelle binance etc*/
            $.ajax({
                url: '/verificadorPagosAjax',
                success: function (response) {
                    $('#verificadorPagos').html(response);
                },
                error: function (error) {
                    $('.actualizarPagos').click();
                },
                timeout: 300000
            });

            /* carga de pagos ultimos 30 minutos de zelle binance etc*/
            $('.actualizarPagos').click(function () {
                $('#verificadorPagos').html('<div class="text-center"><img width="100px" class="mb-5" src="/assets/img/cargando.gif" alt=""></div>');

                $.ajax({
                    url: '/verificadorPagosAjax',
                    success: function (response) {
                        $('#verificadorPagos').html(response);
                    },
                    error: function (error) {
                        $('.actualizarPagos').click();
                    },
                    timeout: 300000
                });
            });

            /*############################################# */

            /*############### js pago movil ################*/
            /* Ocultar mensaje de exito al cargar*/
            $('.tpago-existoso-container').hide();
            $('#tpago-error-container').hide();

            /*Actualizar la factura a la cual se le va a hacer el pago movil*/
            $("#actualizarPagoMovil").click(function (){
                $('#btn-procesar-pago').attr('disabled', false);
                caja = $('#caja_pago_movil').val();
                $.ajax({                  
                    type: 'GET',
                    url: '/vuelto/vdc/actualizar',
                    data: {
                        caja: caja
                    },
                    success: function (response) {
                        console.log(response);
                        $("#total_factura_PM").html("Total Factura: Bs. "+response.total_factura);
                        $("#numero_factura_PM").html("Número factura: "+response.numero_factura);
                        $("#total_pagado_PM").html("Total Pagado: Bs. "+response.total_factura_pagado);
                        $("#cliente_PM").html("Cliente: "+response.cliente);
                        $("#telefono_PM").val(response.telefono)
                        $('#numero_factura_pago_movil').val(response.numero_factura);                

                        switch(response.tipo_cliente){
                            case "V":
                                //cedula venezolana
                                //filtrar documento
                                stringWithNumbers = $("#documento_cliente_pagoMovil").val();
                                onlyNumbers = stringWithNumbers.replace(/[^0-9]+/g, ""); // esto retorna '1234'
                                $("#documento_cliente_pagoMovil").val(onlyNumbers);
                                $("#documento_cliente_pagoMovil").attr( "minlength",'7' );
                                $("#documento_cliente_pagoMovil").attr( "maxlength",'8' );
                                $("#documento_cliente_pagoMovil").attr( "min",'1000000' );
                                $("#documento_cliente_pagoMovil").attr( "max",'99999999' );
                                $("#documento_cliente_pagoMovil").attr( "type",'number' );
                                $("#documento_cliente_pagoMovil").val(onlyNumbers);
                                $("#documento_cliente_pagoMovil").attr( "onkeypress",'return /[0-9]/i.test(event.key)' );
                                
                                campoDoc=document.getElementById("documento_cliente_pagoMovil")
                                if (campoDoc.value.length > campoDoc.maxLength) campoDoc.value = campoDoc.value.slice(0, campoDoc.maxLength);

                            break;        
                            case "J":
                                //rif Juridico
                                stringWithNumbers = $("#documento_cliente_pagoMovil").val();
                                onlyNumbers = stringWithNumbers.replace(/[^0-9]/g, ""); // esto retorna '1234'
                                $("#documento_cliente_pagoMovil").val(onlyNumbers);
                                $("#documento_cliente_pagoMovil").attr( "minlength",'9' );
                                $("#documento_cliente_pagoMovil").attr( "maxlength",'9' );
                                $("#documento_cliente_pagoMovil").attr( "min",'' );
                                $("#documento_cliente_pagoMovil").attr( "max",'' );
                                $("#documento_cliente_pagoMovil").attr( "type",'text' );                        

                                $("#documento_cliente_pagoMovil").val(onlyNumbers);        
                                campoDoc=document.getElementById("documento_cliente_pagoMovil")
                                if (campoDoc.value.length > campoDoc.maxLength) campoDoc.value = campoDoc.value.slice(0, campoDoc.maxLength);                
                            break;
                            case "E":
                                //cedula extranjera
                                //filtrar documento
                                stringWithNumbers = $("#documento_cliente_pagoMovil").val();
                                onlyNumbers = stringWithNumbers.replace(/[^0-9]+/g, ""); // esto retorna '1234'
                                $("#documento_cliente_pagoMovil").val(onlyNumbers);
                                $("#documento_cliente_pagoMovil").attr( "minlength",'6' );
                                $("#documento_cliente_pagoMovil").attr( "maxlength",'15' );
                                $("#documento_cliente_pagoMovil").attr( "min",'100000' );
                                $("#documento_cliente_pagoMovil").attr( "max",'999999999999999' );
                                $("#documento_cliente_pagoMovil").attr( "type",'number' );
                                
                                $("#documento_cliente_pagoMovil").val(onlyNumbers);                        
                            break;                          
                        }
                        $("#tipo_documento_pagoMovil option[value="+ response.tipo_cliente +"]").attr("selected",true);
                        
                        $("#documento_cliente_pagoMovil").val(response.cedula_cliente);
                        $("#monto_PM").attr( "max",response.monto);
                        $("#monto_PM").val(response.monto);
                    },
                    error: function (error) {
                        $('.actualizarPagos').click();
                    },
                    timeout: 300000
                });

            });

            /* Procesar el pago movil
            // a travez de detectar el submit del form vueltoVDC
            // se previene el submit por defecto y se serializa
            */
            $('#vueltoVDC').submit(function (event) {
                $('#btn-procesar-pago').html('Cargando...');
                
                event.preventDefault();
                $('.tpago-existoso-container').hide();
                $('#tpago-error-container').hide();
                $('#btn-procesar-pago').attr('disabled', true);
                
                numero_factura = $('#numero_factura_pago_movil').val();
                caja = $('#caja_pago_movil').val();
                monto = $('#monto_PM').val();
                tcliente=$('#tipo_documento_pagoMovil option:selected').val();
                
                cedula_cliente=$('#documento_cliente_pagoMovil').val();

                $.ajax({
                    type: 'GET',
                    url: '/vuelto/vdc/validar',
                    data: {
                        numero_factura: numero_factura,
                        caja: caja,
                        monto: monto,                        
                        cedula_cliente: tcliente+cedula_cliente,
                
                    },
                    success: function (response) {

                        if (response == 'exito') {
                            data = $('#vueltoVDC').serialize();

                            $.ajax({
                                type: 'GET',
                                url: '/vuelto/vdc',
                                data: data,
                                success: function (response) {
                                    response = JSON.parse(response);

                                    console.log(response.resultado);

                                    if (response.resultado == 'exito') {
                                        $('.numero-referencia-container').html('Numero de referencia: ' + response.referencia);
                                        $('.tpago-existoso-container').show();
                                        $('.tpago-form-container').hide();
                                        $('#btn-procesar-pago').attr('disabled', true);
                                        $('.btn-procesar-tpago').html('Procesado');
                                        return false;
                                    }
                                    $('#tpago-error-container').show();
                                    $('#tpago-error-text').html(response.error);
                                    $('#btn-procesar-pago').attr('disabled', false);
                                    $('.btn-procesar-tpago').html('Procesar');
                                    
                                },
                                error: function (error) {
                                    console.log(error);
                                }
                            })
                        } else {
                            $('#btn-procesar-pago').attr('disabled', false);
                            $('.btn-procesar-tpago').html('Procesar');
                            $('#tpago-error-container').show();
                            $('#tpago-error-text').html(response);
                            $('#btn-procesar-pago').attr('disabled', false);
                            $('.btn-procesar-tpago').html('Procesar');
                            //alert(response);
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            $('#darVuelto').on('shown.bs.modal', function (event) {
                $('[name=telefono_cliente]').focus();
                $("#vueltoVDC")[0].reset();
                $('#btn-procesar-pago').attr('disabled', true);
                $('.btn-procesar-tpago').html('Procesar');
                $("#total_factura_PM").html("Total Factura: ");
                $("#numero_factura_PM").html("Número factura: ");
                $("#total_pagado_PM").html("Total Pagado: ");
                $("#cliente_PM").html("Cliente: ");
            });

            $('#darVuelto').on('hide.bs.modal', function (event) {
                $('.tpago-existoso-container').hide();
                $('#tpago-error-container').hide();
                $('.tpago-form-container').show();
                
                $("#vueltoVDC")[0].reset();
                $('#btn-procesar-pago').attr('disabled', true);
                $('.btn-procesar-tpago').html('Procesar');
            });
        });

        /********************* FACTURAS DEL CLIENTE *********************/

        //Variables globales para almacenar valores numericos de facturas y totales
        var f1 = 0, f2 = 0, f3 = 0, totalBs = 0, totalDs = 0, auxBs = 0;

        /*
            TITULO: calcularFactura
            PARAMETROS : [fac1] Objeto JQuery con el campo factura 1
                       [fac2] Objeto JQuery con el campo factura 2
                       [fac3] Objeto JQuery con el campo factura 3
                       [totalFacBs] Objeto JQuery con el campo total en Bs
                       [totalFacDs] Objeto JQuery con el campo total en $
                       [tasa] Objeto JQuery con el campo tasa de venta Back End
                       [decimales] Objeto JQuery con el campo decimales de venta Back End
                       [tolerancia] Objeto JQuery con el campo tolerancia de venta Back End
                       [saldoRestanteBs] Objeto JQuery con el campo saldo restante en Bs
                       [saldoRestanteDs] Objeto JQuery con el campo saldo restante en $
                       [resultado] Objeto JQuery para el resultado final de la factura
            FUNCION: Realizar los calculos para conectar una o las tres facturas en un resultado dado en bolivares o divisas
            RETORNO: No aplica
        */

        function calcularFactura(fac1, fac2, fac3, totalFacBs, totalFacDs, tasa, decimales, tolerancia, saldoRestanteBs, saldoRestanteDs, resultado) {

            //Variables para guardar el valor numerico de las facturas fac1, fac2 y fac3
            f1 = parseFloat(fac1.val());
            f2 = parseFloat(fac2.val());
            f3 = parseFloat(fac3.val());

            validarNegativos(fac1, fac2, fac3);

            //Validacion y suma de totales en bolivares
            if(isNaN(f1) || isNaN(f2) || isNaN(f3)) {

                //Validacion de la factura 1
                if(!isNaN(f1)) {
                    totalBs = f1;

                    if(!isNaN(f2)) {
                        totalBs += f2;
                    }
                    if(!isNaN(f3)) {
                        totalBs += f3;
                    }
                }

                //Validacion de la factura 2
                if(!isNaN(f2)) {
                    totalBs = f2;

                    if(!isNaN(f1)) {
                        totalBs += f1;
                    }
                    if(!isNaN(f3)) {
                        totalBs += f3;
                    }
                }

                //Validacion de la factura 3
                if(!isNaN(f3)) {
                    totalBs = f3;

                    if(!isNaN(f1)) {
                        totalBs += f1;
                    }
                    if(!isNaN(f2)) {
                        totalBs += f2;
                    }
                }
            }
            else {
                totalBs = f1 + f2 + f3;
            }

            if(totalBs > 0) {
                //Calculo de totales
                totalDs = (Math.ceil((totalBs/tasa) * 100)) / 100;
                totalBs = redondearArriba(totalBs);

                //Imprimir resultados
                totalFacBs.val(separarMiles(totalBs, decimales));
                totalFacDs.val(separarMiles(totalDs, decimales));
                saldoRestanteBs.val(separarMiles(totalBs, decimales));
                saldoRestanteDs.val(separarMiles(totalDs, decimales));

                resultado.val('El cliente debe: Bs. ' + separarMiles(totalBs, decimales)).addClass('bg-danger text-white');
            }
            else {
                totalFacBs.val('');
                totalFacDs.val('');
                saldoRestanteBs.val('');
                saldoRestanteDs.val('');

                resultado.val('-').removeClass('bg-danger text-white');
            }

            //Variable auxiliar para conservar temporalmente el valor del total en Bs
            auxBs = totalBs;

            formatearVariables();
        }

        /*
            TITULO: validarNegativos
            PARAMETROS : [fac1] Objeto JQuery con el campo factura 1
                         [fac2] Objeto JQuery con el campo factura 2
                         [fac3] Objeto JQuery con el campo factura 3
            FUNCION: Validar si alguna de las facturas tiene valores negativos, lanzar un mensaje de error y formatear los valores a 0
            RETORNO: No aplica
        */
        function validarNegativos(fac1, fac2, fac3) {
            if((f1 < 0) || (f2 < 0) || (f3 < 0)) {

                $('#errorModalCenter').modal('show');

                if(f1 < 0) {
                    fac1.val('');
                    f1 = 0;
                }
                if(f2 < 0) {
                    fac2.val('');
                    f2 = 0;
                }
                if(f3 < 0) {
                    fac3.val('');
                    f3 = 0;
                }
            }
        }

        /*
            TITULO: formatearVariables
            PARAMETROS : No aplica
            FUNCION: Formatear las variables para evitar valores basura
            RETORNO: No aplica
        */
        function formatearVariables() {
            //Facturas
            totalBs = 0;
            totalDs = 0;

            //Abonos
            convA1 = 0;
            totalAb = 0;
        }

        /*
            TITULO: redondearArriba
            PARAMETROS : [numero] Numero a redondear
            FUNCION: Redondea a 2 decimales siempre hacia arriba
            RETORNO: Numero redondeado
        */
        function redondearArriba(numero) {
            return (Math.ceil((numero * 100))) / 100;
        }

        /*
            TITULO: separarMiles
            PARAMETROS : [cantidad] Numero a transformar
                         [decimales] Numero de decimales solicitados
            FUNCION: Transforma con expresiones regulares un valor numerico a formato moneda
            RETORNO: Numero con formato moneda
        */
        function separarMiles(cantidad, decimales) {
            //Transformamos el numero en string
            cantidad += '';

            //Eliminar cualquier caracter diferente a (.) o numeros
            cantidad = parseFloat(cantidad.replace(/[^0-9\.]/g, ''));

            //Validar los decimales
            decimales = decimales || 0;

            //Si el numero es cero o texto alphanumerico retornamos cero
            if(isNaN(cantidad) || cantidad === 0)  {
                return parseFloat(0).toFixed(decimales);
            }

            //Si el valor es mayor o menor que cero formateamos a moneda
            cantidad = '' + cantidad.toFixed(decimales);
            var cantidad_parts = cantidad.split('.'),
            regexp = /(\d+)(\d{3})/;

            while(regexp.test(cantidad_parts[0])) {
                cantidad_parts[0] = cantidad_parts[0].replace(regexp, '$1' + '.' + '$2');
            }

            //Retornamos el valor formateado
            return cantidad_parts.join(',');
        }

        /********************* ABONOS DEL CLIENTE *********************/

        //Variables globales para almacenar valores numericos de abonos y totales
        var ab1 = 0, ab2 = 0, convA1 = 0, totalAb = 0, restanteBs = 0, restanteDs = 0;

        /*
            TITULO: calcularAbono
            PARAMETROS : [abono1] Objeto JQuery con el campo abono 1
                         [abono2] Objeto JQuery con el campo abono 2
                         [convAbono1] Objeto JQuery con el campo conversion abono 1 en Bs
                         [totalAbonos] Objeto JQuery con el campo total abonos en Bs
                         [totalFacBs] Objeto JQuery con el campo total en Bs
                         [tasa] Objeto JQuery con el campo tasa de venta Back End
                         [decimales] Objeto JQuery con el campo decimales de venta Back End
                         [tolerancia] Objeto JQuery con el campo tolerancia de venta Back End
                         [saldoRestanteBs] Objeto JQuery con el campo saldo restante en Bs
                         [saldoRestanteDs] Objeto JQuery con el campo saldo restante en $
                         [resultado] Objeto JQuery para el resultado final de la factura
            FUNCION: Realizar los calculos para las conversiones de los abonos del cliente en $ y dar los resultados de las restas a de la factura en ambas monedas
            RETORNO: No aplica
        */
        function calcularAbono(abono1, abono2, convAbono1, totalAbonos, totalFacBs, tasa, decimales, tolerancia, saldoRestanteBs, saldoRestanteDs, resultado) {

            //Variables para guardar el valor numerico de los abonos 1 y 2
            ab1 = parseFloat(abono1.val());
            ab2 = parseFloat(abono2.val());

            validarAbonosNegativos(abono1, abono2);

            //Validar abono en dolares inferior o igual a 2000
            if(!isNaN(ab1) && (ab1 > 2000)) {
                $('#errorModalRango').modal('show');
                abono1.val('');
                ab1 = 0;
            }

            //Calcular conversiones y totales de los abonos
            if(isNaN(ab1) || isNaN(ab2)) {

                if(!isNaN(ab1)) {
                    convA1 = ab1 * tasa;
                    totalAb = convA1;
                }
                else if(!isNaN(ab2)) {
                    totalAb = ab2;
                }
            }
            else if(convA1 > 0) {
                totalAb = convA1 + ab2;
            }
            else {
                convA1 = ab1 * tasa;
                totalAb = convA1 + ab2;
            }

            if(totalAb > 0) {
                //Calculo de totales a mostrar
                totalBs = auxBs;
                restanteBs = (totalBs - totalAb).toFixed(decimales);
                restanteDs = (restanteBs / tasa).toFixed(decimales);

                //Imprimir resultados
                if((restanteBs < 0) || (restanteDs < 0)) {

                    if(restanteBs < 0) {
                        saldoRestanteBs.val('-' + separarMiles(restanteBs, decimales));
                    }

                    if(restanteDs < 0) {
                        saldoRestanteDs.val('-' + separarMiles(restanteDs, decimales));
                    }
                }
                else {
                    if(restanteBs >= 0) {
                        saldoRestanteBs.val(separarMiles(restanteBs, decimales));
                    }

                    if(restanteDs >= 0) {
                        saldoRestanteDs.val(separarMiles(restanteDs, decimales));
                    }
                }

                convAbono1.val(separarMiles(convA1, decimales));
                totalAbonos.val(separarMiles(totalAb, decimales));
            }

            if(restanteBs > 0) {
                resultado.val('El cliente debe: Bs. ' + separarMiles(restanteBs, decimales)).addClass('bg-danger text-white');
            }
            else if(restanteBs < ((-1) * tolerancia)) {
                resultado.val('Hay un vuelto pendiente de: Bs. -' + separarMiles(restanteBs, decimales)).removeClass('bg-danger text-white');
            }
            else if(restanteBs != 0) {
                resultado.val('-').removeClass('bg-danger text-white');
            }
            else if((restanteBs == 0) && (totalBs > 0)) {
                resultado.val('-').removeClass('bg-danger text-white');
            }

            formatearVariables();
        }

        /*
            TITULO: validarAbonosNegativos
            PARAMETROS : [abono1] Objeto JQuery con el campo abono 1
                         [abono2] Objeto JQuery con el campo abono 2
            FUNCION: Validar si alguno de los abonos tiene valores negativos, lanzar un mensaje de error y formatear los valores a 0
            RETORNO: No aplica
        */
        function validarAbonosNegativos(abono1, abono2) {
            if((ab1 < 0) || (ab2 < 0)) {

                $('#errorModalCenter').modal('show');

                if(ab1 < 0) {
                    abono1.val('');
                    ab1 = 0;
                }
                if(ab2 < 0) {
                    abono2.val('');
                    ab2 = 0;
                }
            }
        }

        /********************* INICIO DE LA EJECUCION DEL SCRIPT *********************/
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

            //Identificamos los objetos del DOM con objetos JQuery
            var botonLimpiar = $('#btn-borrarN');
            var resultado = $('#resultado');
            var elementoActivo = '';

            //Facturas y totales
            var fac1 = $('#fac1'); //Factura #1 del cliente Bs
            var fac2 = $('#fac2'); //Factura #2 del cliente Bs
            var fac3 = $('#fac3'); //Factura #3 del cliente Bs
            var totalFacBs = $('#totalFacBs'); //Monto total calculado en Bs
            var totalFacDs = $('#totalFacDs'); //Monto total calculado en $
            var saldoRestanteBs = $('#saldoRestanteBs');
            var saldoRestanteDs = $('#saldoRestanteDs');

            //Abonos del cliente
            var abono1 = $('#abono1');
            var abono2 = $('#abono2');
            var convAbono1 = $('#convAbono1');
            var totalAbonos = $('#totalAbonos');

            //Campos requeridos traidos del back end
            var tasa = $('#tasa').val(); //Tasa de venta
            var decimales = $('#decimales').val(); //Numero de decimales de la factura
            var tolerancia = $('#tolerancia').val(); //Tolerancia de vuelto al cliente

            //Tasa con formato para el usuario
            var tasaM = $('#tasaM');
            tasaM.attr('value', separarMiles(tasa, decimales));

            //Colocamos el boton de borrado a la escucha del click
            botonLimpiar.click(function() {
                //Borra el resultado, elimina las clases existentes y pasa el foco a la factura 1
                resultado.removeClass('bg-danger text-white').val('-');

                fac1.focus();

                //Formateo de variables auxiliares
                auxBs = 0;
                restanteBs = 0;
                restanteDs = 0;
            });

            //Transformamos los campos back end a valores flotantes para poder operar con ellos
            tasa = parseFloat(tasa);
            decimales = parseFloat(decimales);
            tolerancia = parseFloat(tolerancia);

            //Gestionador de eventos
            $('#fac1, #fac2, #fac3, #abono1, #abono2').on({
                keypress: function(e) {
                    if(e.keyCode == 13) {//Metodo para cambiar el foco con la tecla intro
                        elementoActivo = document.activeElement.id;

                        switch(elementoActivo) {
                            case 'fac1': fac2.focus(); break;
                            case 'fac2': fac3.focus(); break;
                            case 'fac3': abono1.focus(); break;
                            case 'abono1': abono2.focus(); break;
                            case 'abono2': botonLimpiar.focus(); break;
                            default: fac1.focus();
                        }
                    }
                    else if(e.keyCode == 48) {//Metodo para evitar ceros al principio
                        if(e.target.value == '') {
                            e.preventDefault();
                        }
                    }
                },

                //Gestionador de calculos
                blur: function(e) {
                    switch(e.target.id) {
                        case 'fac1':
                        case 'fac2':
                        case 'fac3':
                          calcularFactura(fac1, fac2, fac3, totalFacBs, totalFacDs, tasa, decimales, tolerancia, saldoRestanteBs, saldoRestanteDs, resultado);
                        break;

                        case 'abono1':
                        case 'abono2':
                          calcularAbono(abono1, abono2, convAbono1, totalAbonos, totalFacBs, tasa, decimales, tolerancia, saldoRestanteBs, saldoRestanteDs, resultado);
                        break;
                    }
                },

                //Gestionador de tasa a mostrar
                focus: function(e) {
                    if(e.target.id == 'fac1') {
                        tasaM.attr('value', separarMiles(tasa, decimales));
                    }
                }
            });
        });
        /********************* INICIO DE LA EJECUCION DEL SCRIPT *********************/
    </script>
@endsection

<?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    $RutaUrl = FG_Mi_Ubicacion();
    //$RutaUrl = 'FAU';
    $SedeConnection = $RutaUrl;
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = "SELECT id,CodigoCaja FROM VenCaja WHERE estadoCaja = 2 ORDER BY CodigoCaja ASC";
    $result1 = sqlsrv_query($conn,$sql1);

    /*
        TITULO: ValidarFecha
        PARAMETROS: [$FechaTasaDolar] fecha actual
                    [$Moneda] la moneda a buscar
        FUNCION: Realizar la busqueda de la tasa segun la fecha, en caso de no ser la fecha del dia, se haran tantas iteraciones hacia atras como sean necesarias hasta encontrar una tasa valida
        RETORNO: Un array conteniendo la fecha y la tasa encontrada
    */

    function ValidarFecha($FechaTasaDolar,$Moneda) {
        $arrayValidaciones = array(2);
        $FechaTasaDolar = date("Y-m-d",strtotime($FechaTasaDolar."- 1 days"));
        $TasaDolar = FG_Tasa_FechaConversion($FechaTasaDolar,$Moneda);
        $arrayValidaciones[0] = $FechaTasaDolar;
        $arrayValidaciones[1] = $TasaDolar;
        return $arrayValidaciones;
    }

    $Moneda = 'Dolar';
    $FechaTasaDolar = new DateTime("now");
    $FechaActual = $FechaTasaDolar = $FechaTasaDolar->format("Y-m-d");
    $TasaDolar = FG_Tasa_FechaConversion($FechaTasaDolar,$Moneda);

    while(is_null($TasaDolar)) {
        $arrayResult =  ValidarFecha($FechaTasaDolar,$Moneda);
        $FechaTasaDolar = $arrayResult[0];
        $TasaDolar = $arrayResult[1];
    }
?>

@section('content')
    <!-- Modal Fecha -->
    @if($FechaTasaDolar != $FechaActual)
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-warning" id="exampleModalCenterTitle">
                            <i class="fas fa-exclamation-triangle"></i>&nbsp;Advertencia
                        </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <h4 class="h6">
                            La tasa de venta no est&aacute; actualizada, contacte a su supervisor.
                        </h4>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-warning" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Valores Negativos -->
    <div class="modal fade" id="errorModalCenter" tabindex="-1" role="dialog" aria-labelledby="errorModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="errorModalCenterTitle">
                        <i class="fas fa-exclamation-circle"></i>&nbsp;Error
                    </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h4 class="h6">No se permiten valores negativos</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Error En Rango Dolares -->
    <div class="modal fade" id="errorModalRango" tabindex="-1" role="dialog" aria-labelledby="errorModalRangoTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="errorModalRangoTitle">
                        <i class="fas fa-exclamation-circle"></i>&nbsp;Error
                    </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h4 class="h6">Los Abonos en dolares deben ser menores a 2000</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Error Sin Factura Temporal -->
    <div class="modal fade" id="errorModalFacturaTemp" tabindex="-1" role="dialog" aria-labelledby="errorModalRangoTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="errorModalRangoTitle">
                        <i class="fas fa-exclamation-circle"></i>&nbsp;Error
                    </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h4 class="h6">Usted no posee facturas en transito</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Error Sin Caja Seleccionada -->
    <div class="modal fade" id="errorModalCajaSelect" tabindex="-1" role="dialog" aria-labelledby="errorModalRangoTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="errorModalRangoTitle">
                        <i class="fas fa-exclamation-circle"></i>&nbsp;Error
                    </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <h4 class="h6">No tienes una caja seleccionada, recuerda seleccionarla y protegerla</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <a name="Inicio"></a>
    <hr class="row align-items-start col-12">
    <h5 class="text-info">
        <i class="fas fa-money-bill-alt"></i>
        C&Aacute;LCULO DE FACTURA EN DIVISA
        <button  onclick="actualizar();" style="display:inline; float:right;" role="button" class="btn btn-outline-success btn-sm"data-placement="top"><i class="fa fa-sync">&nbsp;Actualizar</i></button>
    </h5>
    <hr class="row align-items-start col-12">

    <table id="tablaSugerencia" class="table table-borderless table-hover">
        <thead class="thead-dark" align="center">
            <th scope="col" colspan="4"><b>PAGOS SUGERIDOS</b></th>
        </thead>
        <tbody align="right">
          <tr>
            <td style="width:25%">Caja:</td>
            <td>
              <input id="nombreCaja" type="text" class="form-control" disabled>
            </td>
            <td style="width:25%">Clinte:</td>
            <td>
              <input id="nombreCliente" type="text" class="form-control" disabled>
            </td>
          </tr>
          <tr>
            <td>Total Factura Bs (Con IVA): </td>
            <td align="left">
              <input id="TotalFacBsSug" type="text" class="form-control" disabled style="display: inline; width:75%;">
              <input type="button" onclick="copiar();" id="btn-copiar" value="Copiar" class="btn btn-outline-success btn-sm form-control" style="display: inline; width: 20%;">
              <input type="hidden" value="" id="totalBsCopy">
            </td>
            <td>Total Factura <?php echo SigDolar?>:</td>
            <td>
              <input id="TotalFacDsSug" type="text" class="form-control" disabled>
            </td>
          </tr>
          <tr>
            <td>Parte en <?php echo SigVe?>:</td>
            <td>
              <input id="ParteBsSug" type="text" class="form-control" disabled>
            </td>
            <td>Parte en <?php echo SigDolar?>:</td>
            <td>
              <input id="ParteDsSug" type="text" class="form-control" disabled>
            </td>
          </tr>
        </tbody>
    </table>

    <form name="cuadre" class="form-group">
        <table class="table table-borderless table-hover">
            <thead class="thead-dark" align="center">
                <th scope="col" colspan="2"><b>FACTURAS DEL CLIENTE</b></th>
                <th scope="col" colspan="2"><b>INFORMACI&Oacute;N</b></th>
            </thead>

            <tbody align="right">
                <tr>
                    <td>Total Factura Bs (Con IVA) #1:</td>

                    <td>
                        <input type="number" step="0.01" min="0" placeholder="0,00" name="fac1" id="fac1" class="form-control bg-warning" autofocus>
                    </td>

                    <td>Tasa de Cambio:</td>

                    <?php
                        if($FechaTasaDolar != $FechaActual) {
                    ?>

                    <td>
                        <input type="text" id="tasaM" class="form-control bg-danger text-white"  disabled>
                        <input type="hidden" value="{{$TasaDolar}}" id="tasa">
                    </td>

                    <?php
                        }
                        else {
                    ?>

                    <td>
                        <input type="text" id="tasaM" class="form-control bg-success text-white"  disabled>
                        <input type="hidden" value="{{$TasaDolar}}" id="tasa">
                    </td>

                    <?php
                        }
                    ?>
                </tr>

                <tr>
                    <td>Total Factura Bs (Con IVA) #2:</td>

                    <td>
                        <input type="number" step="0.01" min="0" placeholder="0,00" name="fac2" id="fac2" class="form-control bg-warning">
                    </td>

                    <td>Fecha Tasa de Cambio:</td>

                    <?php
                        if($FechaTasaDolar != $FechaActual) {
                    ?>

                    <td>
                        <input type="text" value="{{date('d-m-Y',strtotime($FechaTasaDolar))}}" id="fecha" class="form-control bg-danger text-white" disabled>
                    </td>

                    <?php
                        }
                        else {
                    ?>

                    <td>
                        <input type="text" value="{{date('d-m-Y',strtotime($FechaTasaDolar))}}" id="fecha" class="form-control bg-success text-white" disabled>
                    </td>

                    <?php
                        }
                    ?>
                </tr>

                <tr>
                    <td>Total Factura Bs (Con IVA) #3:</td>

                    <td>
                        <input type="number" step="0.01" min="0" placeholder="0,00" name="fac3" id="fac3" class="form-control bg-warning">
                    </td>

                    <td>Cantidad Decimales:</td>

                    <td>
                        <input type="number" min="0" max="2" placeholder="0" value="2" id="decimales" class="form-control" disabled>
                    </td>
                </tr>

                <tr>
                    <td>Total Facturas Bs (Con IVA):</td>

                    <td>
                        <input type="text" placeholder="0,00" id="totalFacBs" class="form-control" disabled>
                    </td>

                    <td>Tolerancia Vuelto en Bs:</td>

                    <td>
                        <input type="number" step="0.01" min="0" placeholder="0,00" value="0" id="tolerancia" class="form-control" disabled>
                    </td>
                </tr>

                <tr>
                    <td>Total Factura $:</td>

                    <td>
                        <input type="text" placeholder="0,00" id="totalFacDs" class="form-control" disabled>
                    </td>

                    <td > Caja Actual: </td>

                    <td>
                      <select id="cajaActual" class="form-control bg-info text-white" style="display:inline; width: 65%">
                        <option value="0">Seleccione una caja</option>
                          <?php
                            while($row = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
                          ?>
                          <option value="<?php echo $row['id']; ?>"><?php echo $row['CodigoCaja'];?></option>
                          <?php
                          }
                          ?>
                      </select>
                      <input type="button" onclick="protegido();" id="btn-proteger" value="Proteger" class="btn btn-outline-success btn-sm form-control" style="display: inline; width: 30%;">
                      <input type="hidden" value="0" id="proteger">
                    </td>
                </tr>
            </tbody>

            <thead class="thead-dark" align="center">
                <th scope="col" colspan="2"><b>ABONOS DEL CLIENTE</b></th>
                <th scope="col" colspan="2"><b>SALDOS RESTANTES</b></th>
            </thead>

            <tbody align="right">
                <tr>
                    <td>Abono #1 en $:</td>

                    <td>
                        <input type="number" step="0.01" min="0" placeholder="0,00" name="abono1" id="abono1" class="form-control bg-warning">
                    </td>

                    <td>Saldo Restante en Bs:</td>

                    <td>
                        <input type="text" placeholder="0,00" id="saldoRestanteBs" class="form-control" disabled>
                    </td>
                </tr>

                <tr>
                    <td>Abono #2 en Bs:</td>

                    <td>
                        <input type="number" step="0.01" min="0" placeholder="0,00" name="abono2" id="abono2" class="form-control bg-warning">
                    </td>

                    <td>Saldo Restante en $:</td>

                    <td>
                        <input type="text" placeholder="0,00" id="saldoRestanteDs" class="form-control" disabled>
                    </td>
                </tr>

                <tr>
                    <td>Conversion Abono #1 en Bs:</td>

                    <td>
                        <input type="text" placeholder="0,00" id="convAbono1" class="form-control" disabled>
                    </td>

                    <td colspan="2">
                        <input type="text" placeholder="-" class="form-control" id="resultado" disabled>
                    </td>
                </tr>

                <tr>
                    <td>Total Abonos Bs:</td>

                    <td>
                        <div class="row">
                            <div class="col-9 p-0">
                                <input type="hidden" name="" id="abonoOculto" value="">
                                <input type="text" placeholder="0,00" id="totalAbonos" class="form-control" disabled>
                            </div>
                            <div class="col-3 p-1">
                                <button type="button" onclick="copiarTexto('totalAbonos')" class="btn btn-outline-success btn-sm form-control" style="display: inline;">Copiar</button>
                            </div>
                            
                            
                        </div>
                    </td>

                    <td class="text-center">
                        <button type="reset" name="btn-borrarN" id="btn-borrarN" class="btn btn-success">
                            Borrar y empezar de nuevo
                        </button>
                    </td>

                    <td class="text-center">
                        <a href="#ver-manual" title="Ir al manual de usuario" class="btn btn-primary">
                            Ver instrucciones
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

    <br/><br/>

    <div id="verificadorPagos">
        <div class="text-center">
            <img width="100px" class="mb-5" src="/assets/img/cargando.gif" alt="">
        </div>
    </div>
        <!-- php del vuelto por pago movil -->
        @php
            use compras\Configuracion;
            $montoMaximo=Configuracion::where('variable','=', 'MontoMaximoPM$')->first()->valor;
            $minutosMaximos=Configuracion::where('variable','=', 'TiempoMaximoUltFac')->first()->valor;
            $total_factura = 0;
            $numero_factura = 0;
            $total_factura_pagado = 0;
            $cliente = "";
            $telefono = 0;
            //$tipo_cliente = "V";
            $cedula_cliente = "";
            $caja = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $monto = "";

                $bancos = [
                    '0102' => 'Banco de Venezuela',
                    '0104' => 'Banco Venezolano de Crédito',
                    '0105' => 'Mercantil',
                    '0108' => 'Provincial',
                    '0114' => 'Bancaribe',
                    '0115' => 'Banco Exterior',
                    '0128' => 'Banco Caroní',
                    '0134' => 'Banesco',
                    '0138' => 'Banco Plaza',
                    '0151' => 'Banco Fondo Común',
                    '0156' => '100% Banco',
                    '0157' => 'Banco del Sur',
                    '0163' => 'Banco del Tesoro',
                    '0166' => 'Banco Agrícola de Venezuela',
                    '0168' => 'Bancrecer',
                    '0169' => 'Mi Banco',
                    '0171' => 'Banco Activo',
                    '0172' => 'Bancamiga',
                    '0174' => 'Banplus',
                    '0175' => 'Banco Bicentario',
                    '0177' => 'Banfanb',
                    '0191' => 'Banco Nacional de Crédito'
                ];
        @endphp
    <div class="text-center">
        <button class="btn btn-outline-success actualizarPagos"><i class="fa fa-sync"></i> Actualizar</button>
        @if($montoMaximo>0)
            <button class="btn btn-outline-info" data-toggle="modal" data-target="#darVuelto">Dar vuelto</button>
        @endif
    </div>

    <br/><br/>

    <table class="table table-bordered table-striped" style="margin-bottom: -1px">
        <thead class="thead-dark" align="center">
            <th scope="col"><b>VUELTOS</b></th>
        </thead>

        <tbody id="tablaVueltos">

            <tr>
                <td>
                    <div class="row rowMontos"></div>
                    <div class="row rowDatos"></div>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark" align="center">
            <th scope="col"><b>INSTRUCCIONES</b></th>
        </thead>

        <tbody>
            <tr>
                <td>
                    * Solo debes colocar informacion en los campos <span class="bg-warning text-dark"><b>AMARILLOS<b></span>
                </td>
            </tr>

            <tr>
                <td>
                    * El boton de borrado solo afecta los campos en <span class="bg-warning text-dark"><b>AMARILLO<b></span>
                </td>
            </tr>

            <tr>
                <td>
                    * Si el cliente presenta deuda, lo veras en color <span class="bg-danger text-white"><b>ROJO<b></span>
                </td>
            </tr>

            <tr>
                <td>* Verifica que la <b>tasa</b> sea la del dia en curso.</td>
            </tr>

            <tr>
                <td>
                    * El campo de <b>abonos en dolares</b> solo acepta montos menores a 2000$.
                </td>
            </tr>

            <tr>
                <td>* <b>Nuestro separador de decimales es la coma (,)</b></td>
            </tr>
        </tbody>
    </table>

    <div class="text-center">
        <a href="#Inicio" title="Volver al inicio" class="btn btn-primary">Volver al inicio</a>
    </div>

    

    <!-- Modal Vuelto Pago Movil -->
    @if($montoMaximo>0)
        <div class="modal fade" id="darVuelto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="vueltoVDC" action="/vuelto/vdc" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dar vuelto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="darVueltoBody">
                    <!-- Cuerpo del modal -->
                    <!-- Mensaje Transaccion exitosa Pago Movil -->
                    <div class="tpago-existoso-container">
                        <center>
                            <h4>
                                <i class="fa fa-check text-success"></i>
                                <br>
                                ¡Transacción exitosa!
                            </h4>

                            <div class="numero-referencia-container"></div>
                        </center>
                    </div>
                    <div class="alert alert-danger" id="tpago-error-container">
                        <center>
                            <h4 >
                                <i class="fa fa-exclamation-triangle text-danger"></i>
                                <br>
                                ¡Error en la transaccion!<br>
                                <span id="tpago-error-text"></span>
                            </h4>

                            <div class="numero-referencia-container"></div>
                        </center>
                    </div>
                    <div class="tpago-form-container">
                        
                        <!--datos factura-->
                        <input type="hidden" id="numero_factura_pago_movil" name="numero_factura" value="{{ $numero_factura }}">
                        <input type="hidden" id="caja_pago_movil" name="caja" value="{{ $caja }}">
                        @csrf

                        <!--Datos de la factura pago movil-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    
                                    <button type="button" class="btn btn-outline-success btn-sm" id="actualizarPagoMovil"><i class="fa fa-sync">&nbsp;Actualizar</i></button>
                                    
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    
                                    <label for="total_factura" id="total_factura_PM">Total Factura: Bs. {{ $total_factura }}</label>
                                    
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="numero_factura" id="numero_factura_PM">Número factura : {{ $numero_factura }}</label>
                                    
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="total_pagado" id="total_pagado_PM">Total Pagado : {{ $total_factura_pagado }}</label>
                                    
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="cliente" id="cliente_PM">Cliente : {{ $cliente }}</label>
                                    
                                </div>
                            </div>
                        </div>

                        <!--Telefono de pago movil-->
                        <div class="form-group">
                            <label for="telefono_cliente">Teléfono celular del cliente</label>
                            <input type="text" 
                                id="telefono_PM"
                                name="telefono_cliente" 
                                class="form-control" value="{{ $telefono }}" 
                                onkeypress="return /[0-9]/i.test(event.key)"
                                minlength="11"
                                maxlength="11"
                                placeholder="04240055854"
                                required="required"
                                >
                                
                        </div>
                        
                        <!--bancos-->
                        <div class="form-group">
                            <label for="banco_destino">Banco de destino</label>
                            <select name="banco_destino" class="form-control" required="required">
                                <option value=""></option>
                                @foreach($bancos as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!--Cedula de pago movil-->
                        <div class="form-group">
                            <label for="cedula_cliente">Cédula del cliente</label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select name="tipo_cliente" required class="form-control" id="tipo_documento_pagoMovil"
                                    onchange="tipoDocumento('tipo_documento_pagoMovil','documento_cliente_pagoMovil')"                   >
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                        <option value="J">J</option>
                                    </select>
                                </div>
                                
                                <input type="text" class="form-control" value="{{ $cedula_cliente }}" 
                                    id="documento_cliente_pagoMovil"
                                    name="cedula_cliente"
                                    onkeypress="return /[0-9]/i.test(event.key)"
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    minlength="7"
                                    maxlength="8"
                                    min="1000000"
                                    max="99999999"
                                    required="required"
                                >

                            </div>                 
                        </div>

                        <!--Monto de pago movil-->
                        <div class="form-group">
                            <label for="monto">Monto del vuelto</label>
                            <input class="form-control" 
                            id="monto_PM"
                            type="number" 
                            onkeypress="return /[0-9,.]/i.test(event.key)"
                            name="monto" 
                            value="{{ $monto }}"                        
                            step="0.01" 
                            min="0.01" 
                            max="{{ $monto }}" 
                            required="required"
                            >
                        </div>
                        
                        <ul style="background-color:lightgray;border-radius:25px;">
                            <span style="text-align:center;"><strong>Requisitos para hacer un pago movil:</strong></span><br>
                            <li>1.-Sólo un pago movil por cliente en el Día</strong></li>
                            <li>2.-Sólo un pago movil por factura en el Día</strong></li>
                            <li>3.-El monto del pago movil debe ser<strong> menor a Bs. {{$TasaDolar*$montoMaximo}} ({{$montoMaximo}}$)</strong></li>
                            <li>4.-La factura se debe haber emitido en<strong> menos de {{$minutosMaximos}} minutos</strong></li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <!--Procesar pago movil y boton cerrar modal-->
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit"  class="btn btn-primary btn-procesar-tpago" id="btn-procesar-pago">Procesar</button>
                </div>
                </form>
            </div>
        </div>
        </div>
    @endif
@endsection

@section('scriptsPie')
    <script type="text/javascript">
      $('#tablaSugerencia').hide();
      $('#exampleModalCenter').modal('show');

      let cajaActualPro = "";


      function copiar(){
        var totalBsCopy = $('#totalBsCopy').val();
        $('#fac1').val(totalBsCopy);
        $('#fac1').focus();
      }

      function protegido(){
        var proteger = $('#proteger').val();
        if(proteger==0){
            $('#cajaActual').attr('disabled','disabled');
            $('#proteger').val(1);
            $('#btn-proteger').val("Desproteger");
            cajaActualPro = $('#cajaActual').val();
        }
        else if(proteger==1){
            $('#cajaActual').removeAttr('disabled');
            $('#proteger').val(0);
            $('#btn-proteger').val("Proteger");
            cajaActualPro = $('#cajaActual').val();
        }
      }

      $("#btn-borrarN").click(function(){
        $("#cajaActual option[value='"+ cajaActualPro +"']").attr("selected",true);
        $('#tablaSugerencia').hide();
      });

      function dominio(SedeConnectionJs){
        var dominio = '';
        switch(SedeConnectionJs) {
            case 'FTN':
                dominio = 'http://cpharmaftn.com/';
                return dominio;
            break;
            case 'FLL':
                dominio = 'http://cpharmafll.com/';
                return dominio;
            break;
            case 'FAU':
                dominio = 'http://cpharmafau.com/';
                return dominio;
            break;
            case 'GP':
                dominio = 'http://cpharmatest.com/';
                return dominio;
            break;
            case 'ARG':
                dominio = 'http://cpharmade.com/';
                return dominio;
            break;
            case 'DBs':
                dominio = 'http://cpharmagpde.com/';
                return dominio;
            break;
            case 'KDI':
                dominio = 'http://cpharmakdi.com/';
                return dominio;
            break;
            case 'FSM':
                dominio = 'http://cpharmafsm.com/';
                return dominio;
            break;
        }
    }
        //modificacion
      //var SedeConnectionJs = '<?php echo $RutaUrl;?>';
      var dominio = <?php echo $RutaUrl;?>;
      const URLConsulFac = ''+dominio+'assets/functions/funConsFactDivisa.php';

      function actualizar(){
        
        //Inicio de la busqueda y el armado de la tabla
          var cajaId =  parseInt($('#cajaActual').val());
          var parametro = {
          "cajaId":cajaId
          };

          if (cajaId == 0) {
            $('#errorModalCajaSelect').modal('show');
            return false;
          }
          
          //Incio Armado tablaFactura
          $.ajax({
            data: parametro,
            url: URLConsulFac,
            type: "POST",
            success: function(data) {
              var respuesta = JSON.parse(data);

              console.log(respuesta);
              
             if(respuesta['NombreCaja']!=null){
                var nombreCaja = respuesta['NombreCaja'];
                var nombreCliente = respuesta['NombreCliente'];
                var TotalFacBsSug = respuesta['TotalFactura'];
                var TasaDolar = '<?php echo $TasaDolar;?>';
                var TotalFacDsSug = (Math.ceil((TotalFacBsSug/TasaDolar) * 100)) / 100;
                var ParteDsSug = (Math.trunc((TotalFacDsSug/5))*5);
                var ParteDsSug10 = (ParteDsSug == 5) ? (Math.trunc((TotalFacDsSug/5))*5) : (Math.trunc((TotalFacDsSug/10))*10);
                var ParteBsSug = (TotalFacBsSug-(ParteDsSug*TasaDolar));
                var ParteBsSug10 = (TotalFacBsSug-(ParteDsSug10*TasaDolar));
                //var ParteBsSug = ((TotalFacDsSug%5)*TasaDolar);


                redondeo100 = (Math.trunc((TotalFacDsSug/100))*100);

                i5 = 5 + redondeo100;
                i10 = 10 + redondeo100;
                i20 = 20 + redondeo100;
                i30 = 30 + redondeo100;
                i40 = 40 + redondeo100;
                i50 = 50 + redondeo100;
                i60 = 60 + redondeo100;
                i70 = 70 + redondeo100;
                i80 = 80 + redondeo100;
                i90 = 90 + redondeo100;

                var redondeoAbajo = (Math.trunc((TotalFacDsSug/5))*5);
                var redondeoArriba = (Math.ceil(TotalFacDsSug/10)*10);

                redondeoAbajo = (redondeoAbajo == 5) ? redondeoAbajo : (Math.trunc((TotalFacDsSug/10))*10);

                $('#tablaVueltos').find('.rowDatos').html('');
                $('.rowMontos').html('');

                if (redondeoAbajo != 0) {
                    $('.rowMontos').html(`
                        <div class="col">
                            <b>Total factura:</b> ${separarMiles(TotalFacBsSug, 2)}

                            <b>Parte en $:</b> ${separarMiles(ParteDsSug10, 2)}

                            <b>Parte en Bs.S.:</b> ${separarMiles(ParteBsSug10, 2)}
                        </div>
                    `);

                    if (redondeoAbajo == i5) {
                        $('#tablaVueltos').find('.rowDatos').append(`
                            <div class="col">
                                <b>Redondeado en base a ${i5}$:</b><br>
                                * Que el cliente nos de ${redondeo100+50}$ y le damos 2 de 20$ y uno de 5$ (o 5$ por pago movil)<br>
                                * Que el cliente nos de ${redondeo100+60}$ y le damos 1 de 50 y uno de 5$  (o 5$ por pago movil)<br>
                                * Que el cliente nos de ${redondeo100+100}$ y le damos 1 de 50$ 2 de 20$ y 1 de 5$  (o 5$ por pago movil)
                            </div>
                        `);
                    }

                    if (redondeoAbajo == i10 || redondeoArriba == i10) {
                        $('#tablaVueltos').find('.rowDatos').append(`
                            <div class="col">
                                <b>Redondeado en base a ${redondeo100+10}$:</b><br>
                                * Que el cliente nos de ${redondeo100+50}$  y le damos 2 de 20$<br>
                                * Que el cliente nos de ${redondeo100+60}$  y la damos 1 de 50$<br>
                                * Que el cliente nos de ${redondeo100+100}$ y le damos 1 50$ y 2 de 20$
                            </div>
                        `);
                    }

                    if (redondeoAbajo == i20 || redondeoArriba == i20) {
                        $('#tablaVueltos').find('.rowDatos').append(`
                            <div class="col">
                                <b>Redondeado en base a ${redondeo100+20}$:</b><br>
                                * Que el cliente nos de ${redondeo100+60}$ y le damos 2 de 20$<br>
                                * Que el cliente nos de ${redondeo100+100}$ y le damos 4 de 20$
                            </div>
                        `);
                    }

                    if (redondeoAbajo == i30 || redondeoArriba == i30) {
                        $('#tablaVueltos').find('.rowDatos').append(`
                            <div class="col">
                                <b>Redondeado en base a ${redondeo100+30}$:</b><br>
                                * Que el cliente nos de ${redondeo100+50}$  le damos 20$<br>
                                * Que el cliente nos de ${redondeo100+80}$  y le damos 1 de 50$<br>
                                * Que el cliente nos de ${redondeo100+100}$  le damos 1 50$ y uno de 20$<br>
                                * Que el cliente nos de ${redondeo100+110}$  y le damos 4 de 20$
                            </div>
                        `);
                    }

                    if (redondeoAbajo == i40 || redondeoArriba == i40) {
                        $('#tablaVueltos').find('.rowDatos').append(`
                            <div class="col">
                                <b>Redondeado en base a ${redondeo100+40}$:</b><br>
                                * Que el cliente nos de ${redondeo100+60}$  y le damos 1 de 20$<br>
                                * Que el cliente nos de ${redondeo100+100}$  y le damos 3 de 20$<br>
                                * Que el cliente nos de ${redondeo100+110}$, le damos 1 50$ y uno de 20$
                            </div>
                        `);
                    }

                    if (redondeoAbajo == i50 || redondeoArriba == i50) {
                        $('#tablaVueltos').find('.rowDatos').append(`
                            <div class="col">
                                <b>Redondeado en base a ${redondeo100+50}$:</b><br>
                                * Que el cliente nos de ${redondeo100+100}$ y le damos 1 de 50$<br>
                                * Que el cliente nos de ${redondeo100+110}$ y le damos 3 de 20$
                            </div>
                            `);
                    }

                    if (redondeoAbajo == i60 || redondeoArriba == i60) {
                        $('#tablaVueltos').find('.rowDatos').append(`
                            <div class="col">
                                <b>Redondeado en base a ${redondeo100+60}$:</b><br>
                                * Que el cliente nos de ${redondeo100+100}$  y le damos 2 de 20$<br>
                                * Que el cliente nos de ${redondeo100+110}$ y le damos 50$
                            </div>
                        `);
                    }

                    if (redondeoAbajo == i70 || redondeoArriba == i70) {
                        $('#tablaVueltos').find('.rowDatos').append(`
                            <div class="col">
                                <b>Redondeado en base a ${redondeo100+70}$:</b><br>
                                * Que el cliente nos de ${redondeo100+110}$  y le damos 2 de 20$<br>
                                * Que el cliente nos de ${redondeo100+120}$  y le damos 1 de 50$
                            </div>
                        `);
                    }

                    if (redondeoAbajo == i80 || redondeoArriba == i80) {
                        $('#tablaVueltos').find('.rowDatos').append(`
                            <div class="col">
                                <b>Redondeado en base a ${redondeo100+80}$:</b><br>
                                * Que el cliente nos de ${redondeo100+100}$ y le damos 20$<br>
                                * Que el cliente nos de ${redondeo100+130}$ y le damos 50$
                            </div>
                        `);
                    }

                    if (redondeoAbajo == i90 || redondeoArriba == i90) {
                        $('#tablaVueltos').find('.rowDatos').append(`
                            <div class="col">
                                <b>Redondeado en base a ${redondeo100+90}$:</b><br>
                                * Que el cliente nos de ${redondeo100+110}$  y le damos 1 de 20$<br>
                                * Que el cliente nos de ${redondeo100+140}$  y le damos 1 de 50$<br>
                                * Que el cliente nos de ${redondeo100+150}$ y le damos 3 de 20$
                            </div>
                        `);
                    }
                }



                $('#nombreCaja').val(nombreCaja);
                $('#nombreCliente').val(nombreCliente);
                $('#totalBsCopy').val(TotalFacBsSug);
                $('#TotalFacBsSug').val(separarMiles(TotalFacBsSug,2));
                $('#TotalFacDsSug').val(separarMiles(TotalFacDsSug,2));
                $('#ParteDsSug').val(separarMiles(ParteDsSug,2));
                $('#ParteBsSug').val(separarMiles(ParteBsSug,2));
                $('#tablaSugerencia').show();
              }
              else{
                $('#tablaSugerencia').hide();
                $('#errorModalFacturaTemp').modal('show');
              }
            }
          });
          //Fin Armado tablaFactura
        //Fin de la busqueda y el armado de la tabla
      }
    </script>
@endsection

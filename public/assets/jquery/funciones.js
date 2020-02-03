function ajustarTamano () {
	$('.contApp').css( 'height', parseInt($(window).height() ) - parseInt( $('.busqueda_div_principal').height() ) - parseInt($('.barraHistorial').height()) - parseInt($('.Dash').height()) -15 ) ;
}


// funcion ocultar existencia en o
function ocultarCeros () {
	if ($('#ocultarExistencia').is(":checked"))
	{
	 $('#tablaresultado .Cantdip').each( function()
		{ 
			if ( parseInt($(this).text()) == 0 ) {
				$(this).parent().hide();
			}
		});
	$('#tablaResultadoExtras .Cantdip').each( function()
		{ 
			if ( parseInt($(this).text()) == 0 ) {
				$(this).parent().hide();
			}
		});
	
	} else {
		$('#tablaresultado .Cantdip').each( function()
		{ 
				$(this).parent().show();
		});
		$('#tablaResultadoExtras .Cantdip').each( function()
		{ 
				$(this).parent().show();
		});
	}
}


function acomodarColorM (list) {
	var i = 0;
	var j = 0;
	jQuery(list).each(function (index) 
    {
		j=i/2;
		if (j % 1 == 0) {
				jQuery(this).removeClass('Color1 Color2');
				jQuery(this).addClass('Color1');
			
		}
		else{
			jQuery(this).removeClass('Color1 Color2');
				jQuery(this).addClass('Color2');
		}
		i++;
		
	});
}


function acomodarColorW (list) {
	var i = 0;
	var j = 0;
	jQuery(list).each(function (index) 
    {
		j=i/2;
		if (j % 1 == 0) {
				jQuery(this).addClass('Color3');
			
		}
		else{
				jQuery(this).addClass('Color4');
		}
		i++;
		if( jQuery(' .contCompo', this).css('height') > jQuery(' .ConDes', this).css('height') ) {
			jQuery(' .NOcompo', this).css( 'height',jQuery(' .contCompo', this).css('height'));
		} else {
			jQuery(' .NOcompo', this).css( 'height',jQuery(' .ConDes', this).css('height'));
		}
		
	});
}


function centrarDiv ( div , exc ) {
	posicion = ( jQuery(window).height() - jQuery(div).height() - exc) / 2
	jQuery(div).css('top' , posicion );
}

function validarEnt ( obj , tipo, idError , msgError ) {
	
	switch (tipo) {
		case 'tne':
			error = /^[\s\wáéíóúñüàè]+$/.test(jQuery(obj).val());
			break
	
		case 'hora':
			error = /^[01]\d$/.test( jQuery(obj).val() )
			break
			
		case 'minuto':
			error = /^[012345]\d$/.test(jQuery(obj).val())
			break
		
		case 'entero':
			error = /^\d+$/.test( jQuery(obj).val() )
			break	
			
	}
	
	
	if ( error == false) {
		jQuery(obj).css('border' , '#F00 2px solid');
		jQuery(obj).css('background-color' , '#F1F1F1');
		errorTrue (msgError , idError);
		return 1;
	} else {
		jQuery(obj).css('border' , '2px solid #000');
		jQuery(obj).css('background-color' , '#fff');
		errorFalse ( idError );
		return 0;
	}
}

function consultaAjax (par , dest, borrarCont , contDesInfo ) {
	
	jQuery('.espere').css('display' , 'block' );
		jQuery.ajax({
			type: "POST",
			url: dest,
			data: par,
			success: function(msg){
				errorFalse('errorAjax');
				if ( borrarCont == 1)
					jQuery(contDesInfo).empty();
						
				jQuery(contDesInfo).append(msg);	
				jQuery(".opMe").each(function (index) 
					{ 	
						if( jQuery(' .contCompo', this).css('height') > jQuery(' .ConDes', this).css('height') ) {
							jQuery(' .NOcompo', this).css( 'height',jQuery(' .contCompo', this).css('height'));
						} else {
							jQuery(' .NOcompo', this).css( 'height',jQuery(' .ConDes', this).css('height'));
						}
					});	
				ocultarCeros();
			} ,
			error: function(){
				errorTrue('Error en la carga de Informacion', 'errorAjax');
			}
		});
		
}

function consultaAjaxRE (par , dest, borrarCont , contDesInfo ) {
	
	jQuery('.cargando').css('display' , 'block' );
	jQuery('.cancel').css('display' , 'none' );
	jQuery('.listo').css('display' , 'none' );
		jQuery.ajax({
			type: "POST",
			url: dest,
			data: par,
			cache: 0,
			success: function(msg){
				errorFalse('errorAjax');
				if ( borrarCont == 1)
					jQuery(contDesInfo).empty();
						
				jQuery(contDesInfo).prepend(msg);	
				jQuery(".opMe").each(function (index) 
					{ 	
						if( jQuery(' .contCompo', this).css('height') > jQuery(' .ConDes', this).css('height') ) {
							jQuery(' .NOcompo', this).css( 'height',jQuery(' .contCompo', this).css('height'));
						} else {
							jQuery(' .NOcompo', this).css( 'height',jQuery(' .ConDes', this).css('height'));
						}
					});	
				jQuery('.cargando').css('display' , 'none' );
				jQuery('.cancel').css('display' , 'none' );
				jQuery('.listo').css('display' , 'block' );
			} ,
			error: function(){
				jQuery('.cancel').css('display' , 'block' );
				jQuery('.cargando').css('display' , 'none' );
				jQuery('.listo').css('display' , 'none' );
			}
		});
		
}

function insertarAjax (par, dest) {
    
		jQuery.ajax({
			type: "POST",
			url: dest,
			data: par,
			success: function(msg){
				//alert(msg);	
			} ,
			error: function(){
				errorTrue('Error guardando estadistica', 'errorAjax');
			}
		});
	
}

function errorTrue ( msg, id ) {
	jQuery('.espere').css('display' , 'none' );
	id2="."+id;
	jQuery(id2).remove();
	jQuery('#error').append("<div class='"+id+"'>"+msg+"</div>");
}

function  errorFalse ( id ) {
	jQuery('.espere').css('display' , 'none' );
	id="."+id;
	jQuery(id).remove();
}


function cargarFile ( ruta, expRe , error ) {
	
	if ( error == 0) {
		formdata = new FormData(); 
		len = ruta.files.length;
		if (len == 1) {
			
			errorFalse ( 'errorSelectImg' );
			jQuery(ruta).css('border' , '2px solid #000');
			jQuery(ruta).css('background-color' , '#fff');
			var  img, reader, file, error;  
			
			
				file = ruta.files[0];  
				var nombreImg = file.name;
				if (!file.type.match(expRe)) {  
					error = 1;
					errorTrue("Tipo de archivo no valido" , "tipoArchivo");
					return error;
		
				}  else { 
					errorFalse('tipoArchivo');
					reader = new FileReader();  
					reader.readAsDataURL(file);  		
					formdata.append("images[]", file);  
					
					jQuery.ajax({  
						url: "upload.php",  
						type: "POST",  
						data:formdata,  
						processData: false,  
						contentType: false,  
						success:function (res) {
							errorFalse ('errorLoadImagen' );
							if(res == '0'){
								error = 1;
								errorTrue ( 'No fue posible cargar la imagen' , 'errorLoadImagen' );
								return error;
							} else{
								errorFalse ('errorLoadImagen' );	
							}
						} ,
						error: function(){
							error = 1;
							errorTrue ( 'No fue posible cargar la imagen' , 'errorLoadImagen' );
							return error;
						} 
						
					}); 
				}
				
		} else {
			jQuery(ruta).css('border' , '#F00 2px solid');
			jQuery(ruta).css('background-color' , '#F1F1F1');
			errorTrue ( 'Seleccione una imagen' , 'errorSelectImg' );
			error = 1;
			return error;
		}
		return nombreImg;
	} else 
		error = 1;
}


jQuery(function($){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});


function mueveReloj(){ 
   	momentoActual = new Date() 
   	hora = momentoActual.getHours() 
   	minuto = momentoActual.getMinutes() 
   	segundo = momentoActual.getSeconds() 
	dia = momentoActual.getDate()
	mes = momentoActual.getMonth()
	ano = momentoActual.getFullYear()
	var mt = "AM";
	mes++;
	// Pongo el formato 12 horas
	if (hora > 12) {
	mt = "PM";
	hora = hora - 12;
	}
	if (hora == 0) hora = 12;
	// Pongo minutos y segundos con dos digitos
	if (minuto <= 9) minuto = "0" + minuto;
	if (segundo <= 9) segundo = "0" + segundo;
	
	if (mes <= 9) mes = "0" + mes;
	
	if (dia <= 9) dia = "0" + dia;
	// En la variable 'cadenareloj' puedes cambiar los colores y el tipo de fuente

   	horaImprimible = dia +'/' + mes +'/' +ano+ ' ' +hora + ":" + minuto + ":" + segundo + ' ' + mt

   	jQuery('.fecha').empty().append( horaImprimible);
	
	setTimeout("mueveReloj()",1000) 
} 


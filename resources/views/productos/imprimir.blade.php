<!DOCTYPE html>
<html>
<head>
	<title>Optica Angeles</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{ asset('public/imagenes/icono.png') }}" />
	<script type="text/javascript" src="{{ asset('public/js/jquery.js') }}"></script>
	<script type="text/javascript" src="{{ asset('public/js/jquery-barcode.min.js') }}"></script>
	<link rel="stylesheet" type="text/css" href="{{ asset('public/css/font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/fontawesome/css/font-awesome.css') }}">
</head>
<body id="body-impresion">

	<div id="content-configuraciones">
		<div>
			<ul>
				<li>Verifique el estado e instalación del rollo de tinta.</li>
				<li>Verifique que los stickers estén Instalados</li>
				<li>Calibre la Impresora de acuerdo al tamaño de los stickers.</li>
				<li>Realice Impresión previa de prueba</li>
			</ul>
		</div>
		<div id="realizar-prueba" onclick="crearStickersPrueba();"><i class="fa fa-print"></i> Impresión de Prueba</div>

		
        
        
	</div>

	
	<div id="imprimir" onclick="window.print();"><i class="fa fa-print"></i> <br>Imprimir</div>
	
	<div id="content-etiquetas">
	</div>
	
	<div id="alerta-impresion">
    	Si la impresión fue exitosa, proceda a imprimir los stickers. Por favor revise la vista previa de la impresión si los stickers se ajustan a los tamaños establecidos. De lo contrario póngase en contacto con el técnico encargado del software.
	</div>
	<div id="boton-crear" onclick="crearStickers();">Crear Stickers de Impresión</div>


	

</body>
<style type="text/css">
	*{
		padding:0px;
		margin:0px;
		box-sizing: border-box;
	}
	@page{
   		margin: 0;
   		box-sizing: border-box;
	}

	@font-face {
        font-family: open sans; 
        src: url('../../public/fuentes/OpenSans-Regular.ttf');                      
    }

	#content-configuraciones{
		width: 100%;
		float: left;
	}

	#realizar-prueba{
		font-family: open sans;
        width:250px;
        float: left;
        padding:5px;
        background-color: rgba(0,200,200,1);
        color:white;
        font-size:14px;
        letter-spacing: -0.4px;
        font-weight: 500;
        text-align: center;
        cursor:pointer;
        margin-left: 20px;
    }
    #alerta-impresion{
    	display: none;
    	width: 100%;
    	float: left;
        font-family: open sans;
		font-size:15px;
		letter-spacing: -0.4px;
		list-style: none;
		color:rgba(50,50,50,1);
		padding:20px;
    }
    #boton-crear{
    	display: none;
    	font-family: open sans;
        width:250px;
        float: left;
        padding:5px;
        background-color: rgba(0,200,200,1);
        color:white;
        font-size:14px;
        letter-spacing: -0.4px;
        font-weight: 500;
        text-align: center;
        cursor:pointer;
        margin-left: 20px;
    }
    #imprimir{
    	margin-bottom: 20px;
        display: none;
        width:100px;
        height: 100px;
        margin-right: auto;
        margin-left: auto;
        left:0;
        right: 0;
        padding-top:14px;
        background-color: rgba(0,200,200,1);
        color:white;
        text-align: center;
        cursor:pointer;
        overflow: hidden;
        font-family: open sans;
        //letter-spacing: -0.4px;
        font-weight: 400;
    }

    #imprimir i{
    	font-size: 40px;
    }

    #content-etiquetas{
    	width:calc(90mm + 20px);
    	padding:10px;
    	margin-left: auto;
    	margin-right: auto;
    	left: 0;
    	right: 0;
    	overflow: hidden;
    	//box-shadow: 0px 0px 5px rgba(50,50,50,1);
    	//min-height: 500px;
    }

	.etiqueta{
		width: 90mm;
		height: 41px;
		float: left;
		box-sizing: border-box;
		font-size:8px;
		box-shadow: 0px 0px 5px rgba(50,50,50,1);
		margin-bottom: 10px;
	}

	.content-codigo{
		width: 25%;
		//margin-left: 50%;
		margin-left:1mm;
		height: 100%;
		float: left;
		padding-top:1mm;
	}

	.content-barcode{
		width: 100%;
		float: left;
		height:6mm;

	}

	.barcodeTarget, #canvasTarget{
		width: 2.2cm!important;
		height: 6mm;
	}.barcodeTarget::-webkit-scrollbar {
		display: none;
	}

	.barcodeTarget object{
		width: 2.2cm;
		height: 6mm;
	}

	.content-numero{
		width: 100%;
		font-family: open sans;
		float: left;
		text-align: center;
		font-size: 7px;
		line-height: 8px;
		height: 3mm;
	}

	.content-descripcion{
		width: 25%;
		height: 100%;
		float: left;
		max-height: 10mm;
		padding:2px;
		text-align: center;
		font-family: open sans;
		line-height: 6px;
		font-size:6px;
		hyphens: auto;
		overflow-y: auto;
		font-weight:bold;
	}.content-descripcion::-webkit-scrollbar {
		display: none;
	}

	ul{
		padding:20px;
	}

	li{
		font-family: open sans;
		font-size:15px;
		letter-spacing: -0.4px;
		list-style: none;
		color:rgba(50,50,50,1);
	}

	.precio{
		width: 100%;
		float: left;
		text-align: center;
		font-weight: bold;
		font-size:10px;
		margin-bottom:2px;
	}
</style>


<script type="text/javascript">
    
	function generateBarcode(value,id){

        var btype = "code128";
        var renderer = "bmp";
		var quietZone = false;
		
        var settings = {
			output:renderer,
			bgColor: "FFFFFF",
			color: "000000",
			barWidth: 1,
			barHeight: 50,
        };

		$(id).show().barcode(value, btype, settings);
    }

    
    function crearStickers(){

		document.getElementById('imprimir').style.display = "block";
    	document.getElementById('content-configuraciones').style.display = "none";
    	document.getElementById('boton-crear').style.display = "none";
    	document.getElementById('alerta-impresion').style.display = "none";

	    codigoBarras = localStorage.getItem('codigo');
	    if(codigoBarras != null){
	        codigoBarras = JSON.parse(codigoBarras);
	    }else{
	        codigoBarras = [];
	    }

	    console.log(codigoBarras);

	    $("#content-etiquetas").empty();
	    for(var i=0; i<codigoBarras.length; i++){

	    	for(var j=0;j<codigoBarras[i].cantidad; j++){

			    $("#content-etiquetas").append(`
			        <div class="etiqueta">
						<div class="content-codigo">
								<div class="content-barcode">
										<div id="barcodeTarget-`+i+j+`" class="barcodeTarget"></div>
								</div>
								<div class="content-numero">
									`+codigoBarras[i].code128+`
								</div>
						</div>
						<div class="content-descripcion">
							<div class="precio">$ `+codigoBarras[i].precio+`</div>
							`+codigoBarras[i].nombre+`</div>
					</div>
			    `);

			    var id = "#barcodeTarget-"+i+j;

			    generateBarcode(codigoBarras[i].code128,id);
			}
		}
	}



	function crearStickersPrueba(){

	    codigoBarras = localStorage.getItem('codigo');
	    if(codigoBarras != null){
	        codigoBarras = JSON.parse(codigoBarras);
	    }else{
	        codigoBarras = [];
	    }

	    $("#content-etiquetas").empty();
	    for(var i=0; i<1; i++){


		    $("#content-etiquetas").append(`
		        <div class="etiqueta">
					<div class="content-codigo">
							<div class="content-barcode">
									<div id="barcodeTarget-`+i+`" class="barcodeTarget"></div>
							</div>
							<div class="content-numero">
								PRUEBA
							</div>
					</div>
					<div class="content-descripcion">
						<div class="precio">$ `+codigoBarras[i].precio+`</div>
						`+codigoBarras[i].nombre+` skjhfj sdhfsj dfsdf</div>
				</div>
		    `);

		    var id = "#barcodeTarget-"+i;

		    generateBarcode(codigoBarras[i].code128,id);
		}

		document.getElementById('alerta-impresion').style.display = "block";
		document.getElementById('boton-crear').style.display = "block";
		window.print();
	}


</script>
<style type="text/css" media="print">
	@media print{
		body{
			margin:0px!important;
			padding:0px!important;
		}
		@page{
	   		margin: 0!important;
	   		box-sizing: border-box!important;
		}
		
		#content-configuraciones,#boton-crear,#alerta-impresion,#imprimir{
			display: none!important;
		}
		#content-etiquetas{
			width: 100%!important;
			float: left!important;
			margin:0px!important;
			padding:0px!important;
		}
		.etiqueta{
			margin-bottom:0px!important;
			box-shadow: 0px 0px 0px white!important;
		}
	}
</style>



</html>

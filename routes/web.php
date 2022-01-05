<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*::::::::::::::::WELCOME Y LANDING PAGE::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/', 'WelcomeController@index')->name('welcome');
Route::get('/quienes', 'WelcomeController@quienes')->name('quienes');
Route::get('/consulta/{id_venta}', 'WelcomeController@consulta')->name('consulta');
Route::get('/servicios/{id_servicio}', 'WelcomeController@servicios')->name('servicios');
Route::get('/servicios', 'WelcomeController@servicio')->name('servicio');

Route::get('/promocion/{id_promocion}', 'WelcomeController@promocion')->name('promocion');
Route::post('/promocion/redimir', 'WelcomeController@redimir_promocion')->name('promocion.redimir');

Route::get('/pago', 'WelcomeController@pago')->name('pago')->middleware('auth');														//SOLICITA INFORMACION AL QUE VA A PAGAR POR PAYU
Route::post('/citas', 'CitaController@crear_externa')->name('citas.crear.externa');//AJAX
Route::post('/buscar/membresia', 'WelcomeController@buscar_membresia')->name('buscar.membresia');//AJAX
Route::get('/terminos_condiciones/{termino}', 'WelcomeController@terminos_condiciones')->name('terminos_condiciones');
Route::post('/chat/verificar/correo', 'WelcomeController@verificar_correo')->name('chat.verificar.correo');//AJAX
Route::post('/chat/enviar', 'WelcomeController@enviar_chat')->name('chat.enviar');//AJAX
Route::post('/chat/mostrar', 'WelcomeController@mostrar_chat')->name('chat.mostrar');//AJAX
Route::post('/chat/guardar/nombre', 'WelcomeController@guardar_nombre')->name('chat.guardar.nombre');//AJAX


/*:::::::::::::PAGOS REVISAR FUNCIONALIDAD::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/pagar', 'PagarController@generarKey')->name('pagar');																		//ESTO TRAE EL FORM DE PAYU, PASAR A POST
Route::get('/compra', 'PagarController@compra')->name('compra');																		//REDIRIGE A UN RESULTADO DE TRANSACCION EN EL FORMATO PAGINA VIEJO
Route::post('/respuesta', 'PagarController@respuesta')->name('respuesta');																//REVISAR QUE ES, CREO QUE ES LA URL QUE RETORNA DE PAYU


/*:::::::::::::ECOMMERCE EN LANDING REVISAR:::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/ecommerce/index', 'EcommerceController@index')->name('ecommerce.index');													//ESTA VISTA NO EXISTE PERO SERA LA TIENDA EN NUEVO FORMATO
Route::get('/ecommerce/carrito', 'EcommerceController@carrito')->name('ecommerce.carrito');
Route::get('/ecommerce/articulo/{id_articulo}', 'EcommerceController@articulo')->name('ecommerce.articulo');
Route::get('/ecommerce/articulo', 'EcommerceController@articulo')->name('ecommerce.articulo2');
Route::get('/ecommerce/error', 'EcommerceController@error')->name('ecommerce.error');
Route::get('/ecommerce/categorias', 'EcommerceController@categorias')->name('ecommerce.categorias');
Route::get('/ecommerce/categoria/{id_articulo}', 'EcommerceController@categoria')->name('ecommerce.categoria');
Route::get('/ecommerce/categoria_producto/{id_tipo_producto}/{id_especificacion}', 'EcommerceController@categoria_producto')->name('ecommerce.categoria_producto');
Route::get('/ecommerce/marca_producto/{id_tipo_producto}/{id_marca}', 'EcommerceController@marca_producto')->name('ecommerce.marca_producto');
Route::get('/ecommerce/marca/{id_marca}', 'EcommerceController@marca')->name('ecommerce.marca');
Route::get('/ecommerce/pagar', 'EcommerceController@pagar')->name('ecommerce.pagar')->middleware('auth');
Route::get('/ecommerce/pagarPayu', 'EcommerceController@pagarPayu')->name('ecommerce.pagarPayu')->middleware('auth');					//ESTA FALLANDO POR GET
Route::get('/ecommerce/cliente', 'EcommerceController@cliente')->name('ecommerce.cliente')->middleware('auth');
Route::get('/ecommerce/cliente/perfil', 'EcommerceController@cliente_perfil')->name('ecommerce.cliente_perfil')->middleware('auth');
Route::post('/ecommerce/cliente/modificar', 'EcommerceController@cliente_modificar')->name('cliente.modificar')->middleware('auth');;
Route::post('/ecommerce/cliente/productos_compras', 'EcommerceController@productos_compras')->name('cliente.productos_compras')->middleware('auth');
Route::post('/ecommerce/articulo/comentario', 'EcommerceController@articulo_comentario')->name('ecommerce.articulo_comentario');					



Auth::routes();

Route::group(['middleware' => 'administradores'], function () {

Route::get('/home', 'HomeController@index')->name('home');
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::USUARIO::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/perfil', 'UsuariosController@perfil')->name('usuarios.perfil');
Route::post('/usuarios/imagen', 'UsuariosController@imagen')->name('usuarios.imagen');
Route::post('/usuarios/firma', 'UsuariosController@firma')->name('usuarios.firma');
Route::post('/usuarios/contrasena', 'UsuariosController@contrasena')->name('usuarios.contrasena');/*AJAX*/
Route::post('/usuarios/sucursal', 'UsuariosController@sucursal')->name('usuarios.sucursal');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::MODULOS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/modulos/crear', 'ModulosController@crear')->name('modulos.crear');
Route::post('/modulos/crear', 'ModulosController@guardar')->name('modulos.guardar');
Route::get('/modulos/lista', 'ModulosController@lista')->name('modulos.lista');
Route::post('/modulos/editar', 'ModulosController@editar')->name('modulos.editar');
Route::post('/modulos/modificar', 'ModulosController@modificar')->name('modulos.modificar');
Route::post('/modulos/inactivar', 'ModulosController@inactivar')->name('modulos.inactivar');
Route::get('/modulos/editar', 'ModulosController@redirect');
Route::get('/modulos/modificar', 'ModulosController@redirect');
Route::get('/modulos/inactivar', 'ModulosController@redirect');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::CONFIGURACION APPWEB::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::EMPRESA CLIENTE APPWEB:::::::::::::::::::::::::::::::::::::::::*/

Route::get('/configuraciones/cliente/crear', 'ConfiguracionesController@crear_cliente')->name('configuraciones.cliente.crear');
Route::post('/configuraciones/cliente/guardar', 'ConfiguracionesController@guardar_cliente')->name('configuraciones.cliente.guardar');
Route::get('/configuraciones/cliente/editar', 'ConfiguracionesController@editar_cliente')->name('configuraciones.cliente.editar');
Route::post('/configuraciones/cliente/modificar', 'ConfiguracionesController@modificar_cliente')->name('configuraciones.cliente.modificar');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::EMPRESA CLIENTE APPWEB:::::::::::::::::::::::::::::::::::::::::*/

Route::get('/configuraciones/codigoscie10/cargar', 'ConfiguracionesController@cargar_cie10')->name('configuraciones.codigoscie10.cargar');
Route::post('/configuraciones/codigoscie10/cargar', 'ConfiguracionesController@guardar_cie10')->name('configuraciones.codigoscie10.guardar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ESTABLECIMIENTOS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::BODEGAS:::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/bodegas/crear', 'BodegasController@crear')->name('bodegas.crear');
Route::post('/bodegas/crear', 'BodegasController@guardar')->name('bodegas.guardar');
Route::get('/bodegas/lista', 'BodegasController@lista')->name('bodegas.lista');
Route::post('/bodegas/ver', 'BodegasController@ver')->name('bodegas.ver');
Route::post('/bodegas/editar', 'BodegasController@editar')->name('bodegas.editar');
Route::post('/bodegas/modificar', 'BodegasController@modificar')->name('bodegas.modificar');
Route::post('/bodegas/inactivar', 'BodegasController@inactivar')->name('bodegas.inactivar');
Route::post('/bodegas/productos', 'BodegasController@productos')->name('bodegas.productos');
Route::get('/bodegas/buscar_productos', 'BodegasController@buscar_productos')->name('bodegas.buscar.producto');
Route::post('/bodegas/productos/cantidades', 'BodegasController@productosCantidades')->name('bodegas.productos.cantidades');/*AJAX*/
Route::post('/bodegas/ingresos', 'BodegasController@ingresos')->name('bodegas.ingresos');
Route::get('/bodegas/ver', 'BodegasController@redirect');
Route::get('/bodegas/editar', 'BodegasController@redirect');
Route::get('/bodegas/modificar', 'BodegasController@redirect');
Route::get('/bodegas/inactivar', 'BodegasController@redirect');
Route::get('/bodegas/productos', 'BodegasController@redirect');
Route::get('/bodegas/ingresos', 'BodegasController@redirect');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::SUCURSALES::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/sucursales/crear', 'SucursalesController@crear')->name('sucursales.crear');
Route::post('/sucursales/crear', 'SucursalesController@guardar')->name('sucursales.guardar');
Route::get('/sucursales/lista', 'SucursalesController@lista')->name('sucursales.lista');
Route::post('/sucursales/ver', 'SucursalesController@ver')->name('sucursales.ver');
Route::post('/sucursales/editar', 'SucursalesController@editar')->name('sucursales.editar');
Route::post('/sucursales/modificar', 'SucursalesController@modificar')->name('sucursales.modificar');
Route::post('/sucursales/inactivar', 'SucursalesController@inactivar')->name('sucursales.inactivar');
Route::post('/sucursales/empleados', 'SucursalesController@empleados')->name('sucursales.empleados');
Route::post('/sucursales/productos', 'SucursalesController@productos')->name('sucursales.productos');
Route::get('/sucursales/buscar_productos', 'SucursalesController@buscar_productos')->name('sucursales.buscar.producto');
Route::post('/sucursales/productos/cantidades', 'SucursalesController@productosCantidades')->name('sucursales.productos.cantidades');/*AJAX*/
Route::post('/sucursales/ingresos', 'SucursalesController@ingresos')->name('sucursales.ingresos');
Route::post('/sucursales/ver/ingreso', 'SucursalesController@verIngreso')->name('sucursales.ver.ingreso');
Route::post('/sucursales/ingresos/directo', 'SucursalesController@ingresosDirecto')->name('sucursales.ingresos.directo');
Route::post('/sucursales/ver/ingreso/directo', 'SucursalesController@verIngresoDirecto')->name('sucursales.ver.ingreso.directo');
Route::get('/sucursales/ver', 'SucursalesController@redirect');
Route::get('/sucursales/editar', 'SucursalesController@redirect');
Route::get('/sucursales/modificar', 'SucursalesController@redirect');
Route::get('/sucursales/inactivar', 'SucursalesController@redirect');
Route::get('/sucursales/empleados', 'SucursalesController@redirect');
Route::get('/sucursales/productos', 'SucursalesController@redirect');
Route::get('/sucursales/ingresos', 'SucursalesController@redirect');
Route::get('/sucursales/ver/ingreso', 'SucursalesController@redirect');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::INGRESOS::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/ingresos/crear', 'ProductosController@crear')->name('ingresos.crear');
Route::post('/ingresos/crear', 'IngresosController@guardar')->name('ingresos.guardar');
Route::get('/ingresos/lista', 'IngresosController@lista')->name('ingresos.lista');
Route::post('/ingresos/ver', 'IngresosController@ver')->name('ingresos.ver');
Route::get('/ingresos/ver', 'IngresosController@redirect');
Route::post('/ingresos/editar/cantidades', 'IngresosController@editarCantidades')->name('ingresos.editar.cantidades');
Route::get('/ingresos/excel/crear', 'IngresosController@excelCrearIngreso')->name('ingresos.excel.crear');
Route::get('/ingresos/excel/cargar', 'IngresosController@cargarExcel')->name('ingresos.excel.cargar');
Route::post('/ingresos/excel/guardar', 'IngresosController@guardarExcel')->name('ingresos.excel.guardar');

Route::get('/ingresos/sucursal/crear', 'IngresosController@crearIngresoSucursal')->name('ingresos.sucursal.crear');
Route::post('/ingresos/sucursal/crear', 'IngresosController@guardarIngresoSucursal')->name('ingresos.sucursal.guardar');
Route::get('/ingresos/sucursal/lista', 'IngresosController@listaIngresoSucursal')->name('ingresos.sucursal.lista');
Route::post('/ingresos/sucursal/ver', 'IngresosController@verIngresoSucursal')->name('ingresos.sucursal.ver');
Route::get('/ingresos/sucursal/excel/crear', 'IngresosController@excelCrearIngresoSucursal')->name('ingresos.sucursal.excel.crear');
Route::get('/ingresos/sucursal/excel', 'IngresosController@generarExcel')->name('ingresos.sucursal.excel');/*AJAX*/
Route::get('/ingresos/sucursal/excel/cargar', 'IngresosController@cargarExcelSucursal')->name('ingresos.sucursal.excel.cargar');
Route::get('/ingresos/sucursal/excel/tipos', 'IngresosController@tipos')->name('ingresos.sucursal.excel.tipos');/*AJAX*/
Route::post('/ingresos/sucursal/excel/guardar', 'IngresosController@guardarExcelSucursal')->name('ingresos.sucursal.excel.guardar');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::PROVEEDORES:::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/proveedores/crear', 'ProveedoresController@crear')->name('proveedores.crear');
Route::post('/proveedores/crear', 'ProveedoresController@guardar')->name('proveedores.guardar');
Route::get('/proveedores/lista', 'ProveedoresController@lista')->name('proveedores.lista');
Route::post('/proveedores/editar', 'ProveedoresController@editar')->name('proveedores.editar');
Route::post('/proveedores/modificar', 'ProveedoresController@modificar')->name('proveedores.modificar');
Route::post('/proveedores/inactivar', 'ProveedoresController@inactivar')->name('proveedores.inactivar');
Route::get('/proveedores/editar', 'ProveedoresController@redirect');
Route::get('/proveedores/modificar', 'ProveedoresController@redirect');
Route::get('/proveedores/inactivar', 'ProveedoresController@redirect');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::LABORATORIOS::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/laboratorios/crear', 'LaboratoriosController@crear')->name('laboratorios.crear');
Route::post('/laboratorios/crear', 'LaboratoriosController@guardar')->name('laboratorios.guardar');
Route::get('/laboratorios/lista', 'LaboratoriosController@lista')->name('laboratorios.lista');
Route::post('/laboratorios/editar', 'LaboratoriosController@editar')->name('laboratorios.editar');
Route::post('/laboratorios/modificar', 'LaboratoriosController@modificar')->name('laboratorios.modificar');
Route::post('/laboratorios/inactivar', 'LaboratoriosController@inactivar')->name('laboratorios.inactivar');
Route::post('/laboratorios/inactivar/marca', 'LaboratoriosController@inactivarMarca')->name('laboratorios.inactivar.marca');
Route::get('/laboratorios/editar', 'LaboratoriosController@redirect');
Route::get('/laboratorios/modificar', 'LaboratoriosController@redirect');
Route::get('/laboratorios/inactivar', 'LaboratoriosController@redirect');



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::TRASLADOS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::BODEGA -> SUCURSAL:::::::::::::::::::::::::::::::::::::::::*/

/*::SALIDAS::*/
Route::get('/traslados/bodegasucursal/crear/salida', 'TrasladosController@bodegaSucursalCrearSalida')->name('traslados.bodegasucursal.crearsalida');//Crear Salida
Route::post('/traslados/bodegasucursal/crear/salida', 'TrasladosController@bodegaSucursalGuardarSalida')->name('traslados.bodegasucursal.guardarsalida');//Guardar Salida
Route::get('/traslados/bodegasucursal/lista/salidas', 'TrasladosController@bodegaSucursalListaSalidas')->name('traslados.bodegasucursal.listasalidas');//Lista de Salidas
Route::post('/traslados/bodegasucursal/ver/salida', 'TrasladosController@bodegaSucursalVerSalida')->name('traslados.bodegasucursal.versalida');//Ver Detalle Salida
/*::ENTRADAS::*/
Route::get('/traslados/bodegasucursal/entradas/pendientes', 'TrasladosController@bodegaSucursalEntradasPendientes')->name('traslados.bodegasucursal.entradaspendientes');//Lista Entradas Pendientes
Route::post('/traslados/bodegasucursal/crear/entrada', 'TrasladosController@bodegaSucursalCrearEntrada')->name('traslados.bodegasucursal.crearentrada');//Ver y Crear Entrada
Route::post('/traslados/bodegasucursal/guardar/entrada', 'TrasladosController@bodegaSucursalGuardarEntrada')->name('traslados.bodegasucursal.guardarentrada');//Guardar Entrada
Route::get('/traslados/bodegasucursal/lista/entradas', 'TrasladosController@bodegaSucursalListaEntradas')->name('traslados.bodegasucursal.listaentradas');//Lista Entradas Realizadas
Route::post('/traslados/bodegasucursal/ver/entrada', 'TrasladosController@bodegaSucursalVerEntrada')->name('traslados.bodegasucursal.verentrada');//Detalle de Entrada realizada
Route::get('/traslados/bodegasucursal/ver/entrada', 'TrasladosController@bodegaSucursalVerEntrada')->name('traslados.bodegasucursal.verentrada');//Detalle de Entrada realizada redireccion GET desde Guardar Ent.
/*::AJAX::*/
Route::post('/traslados/empleadosbodegaSucursal', 'TrasladosController@traerEmpleadosBodegaSucursal')->name('traslados.empleadosbodegasucursal');/*AJAX*/
Route::post('/traslados/productosbodega', 'TrasladosController@traerProductosBodega')->name('traslados.productosbodega');/*AJAX*/
/*::REDIRECCIONES GET::*/
Route::get('/traslados/bodegasucursal/ver/salida', 'TrasladosController@bodegaSucursalRedirectSalida');
//Route::get('/traslados/bodegasucursal/guardar/salida', 'TrasladosController@bodegaSucursalRedirectSalida');
Route::get('/traslados/bodegasucursal/crear/entrada', 'TrasladosController@bodegaSucursalRedirectEntrada');
Route::get('/traslados/bodegasucursal/guardar/entrada', 'TrasladosController@bodegaSucursalRedirectEntrada');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::SUCURSAL -> SUCURSAL:::::::::::::::::::::::::::::::::::::::*/

/*::SALIDAS::*/
Route::get('/traslados/sucursalsucursal/crear/salida', 'TrasladosController@sucursalSucursalCrearSalida')->name('traslados.sucursalsucursal.crearsalida');//Crear Salida
Route::post('/traslados/sucursalsucursal/crear/salida', 'TrasladosController@sucursalSucursalGuardarSalida')->name('traslados.sucursalsucursal.guardarsalida');//Guardar Salida
Route::get('/traslados/sucursalsucursal/lista/salidas', 'TrasladosController@sucursalSucursalListaSalidas')->name('traslados.sucursalsucursal.listasalidas');//Lista de Salidas
Route::post('/traslados/sucursalsucursal/ver/salida', 'TrasladosController@sucursalSucursalVerSalida')->name('traslados.sucursalsucursal.versalida');//Ver Detalle Salida
/*::ENTRADAS::*/
Route::get('/traslados/sucursalsucursal/entradas/pendientes', 'TrasladosController@sucursalSucursalEntradasPendientes')->name('traslados.sucursalsucursal.entradaspendientes');//Lista Entradas Pendientes
Route::post('/traslados/sucursalsucursal/crear/entrada', 'TrasladosController@sucursalSucursalCrearEntrada')->name('traslados.sucursalsucursal.crearentrada');//Ver y Crear Entrada
Route::post('/traslados/sucursalsucursal/guardar/entrada', 'TrasladosController@sucursalSucursalGuardarEntrada')->name('traslados.sucursalsucursal.guardarentrada');//Guardar Entrada
Route::get('/traslados/sucursalsucursal/lista/entradas', 'TrasladosController@sucursalSucursalListaEntradas')->name('traslados.sucursalsucursal.listaentradas');//Lista Entradas Realizadas
Route::post('/traslados/sucursalsucursal/ver/entrada', 'TrasladosController@sucursalSucursalVerEntrada')->name('traslados.sucursalsucursal.verentrada');//Detalle de Entrada realizada
Route::get('/traslados/sucursalsucursal/ver/entrada', 'TrasladosController@sucursalSucursalVerEntrada')->name('traslados.sucursalsucursal.verentrada');//Detalle de Entrada realizada redireccion GET desde Guardar E.
/*::AJAX::*/
Route::post('/traslados/empleados/sucursal', 'TrasladosController@traerEmpleadosSucursalSucursal')->name('traslados.empleadossucursal');/*AJAX*/
Route::post('/traslados/productosSucursal', 'TrasladosController@traerProductosSucursal')->name('traslados.productossucursal');/*AJAX*/
/*::REDIRECCIONES GET::*/
Route::get('/traslados/sucursalsucursal/ver/salida', 'TrasladosController@sucursalSucursalRedirectSalida');
//Route::get('/traslados/sucursalsucursal/guardar/salida', 'TrasladosController@sucursalSucursalRedirectSalida');
Route::get('/traslados/sucursalsucursal/crear/entrada', 'TrasladosController@sucursalSucursalRedirectEntrada');
Route::get('/traslados/sucursalsucursal/guardar/entrada', 'TrasladosController@sucursalSucursalRedirectEntrada');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::BODEGA -> BODEGA:::::::::::::::::::::::::::::::::::::::*/

/*::SALIDAS::*/
Route::get('/traslados/bodegabodega/crear/salida', 'TrasladosController@bodegaBodegaCrearSalida')->name('traslados.bodegabodega.crearsalida');//Crear Salida
Route::post('/traslados/bodegabodega/crear/salida', 'TrasladosController@bodegaBodegaGuardarSalida')->name('traslados.bodegabodega.guardarsalida');//Guardar Salida
Route::get('/traslados/bodegabodega/lista/salidas', 'TrasladosController@bodegaBodegaListaSalidas')->name('traslados.bodegabodega.listasalidas');//Lista de Salidas
Route::post('/traslados/bodegabodega/ver/salida', 'TrasladosController@bodegaBodegaVerSalida')->name('traslados.bodegabodega.versalida');//Ver Detalle Salida
/*::ENTRADAS::*/
Route::get('/traslados/bodegabodega/entradas/pendientes', 'TrasladosController@bodegaBodegaEntradasPendientes')->name('traslados.bodegabodega.entradaspendientes');//Lista Entradas Pendientes
Route::post('/traslados/bodegabodega/crear/entrada', 'TrasladosController@bodegaBodegaCrearEntrada')->name('traslados.bodegabodega.crearentrada');//Ver y Crear Entrada
Route::post('/traslados/bodegabodega/guardar/entrada', 'TrasladosController@bodegaBodegaGuardarEntrada')->name('traslados.bodegabodega.guardarentrada');//Guardar Entrada
Route::get('/traslados/bodegabodega/lista/entradas', 'TrasladosController@bodegaBodegaListaEntradas')->name('traslados.bodegabodega.listaentradas');//Lista Entradas Realizadas
Route::post('/traslados/bodegabodega/ver/entrada', 'TrasladosController@bodegaBodegaVerEntrada')->name('traslados.bodegabodega.verentrada');//Detalle de Entrada realizada
Route::get('/traslados/bodegabodega/ver/entrada', 'TrasladosController@bodegaBodegaVerEntrada')->name('traslados.bodegabodega.verentrada');//Detalle de Entrada realizada redireccion GET desde Guardar E.
/*::AJAX::*/
Route::post('/traslados/empleadosbodega', 'TrasladosController@traerEmpleadosBodegaBodega')->name('traslados.empleadosbodega');/*AJAX*/
//Route::post('/traslados/productosbodega', 'TrasladosController@traerProductosBodega')->name('traslados.productosbodega');/*AJAX*/
/*::REDIRECCIONES GET::*/
Route::get('/traslados/bodegabodega/ver/salida', 'TrasladosController@bodegaBodegaRedirectSalida');
//Route::get('/traslados/bodegabodega/guardar/salida', 'TrasladosController@bodegaBodegaRedirectSalida');
Route::get('/traslados/bodegabodega/crear/entrada', 'TrasladosController@bodegaBodegaRedirectEntrada');
Route::get('/traslados/bodegabodega/guardar/entrada', 'TrasladosController@bodegaBodegaRedirectEntrada');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::SUCURSAL -> BODEGA:::::::::::::::::::::::::::::::::::::::*/

/*::SALIDAS::*/
Route::get('/traslados/sucursalbodega/crear/salida', 'TrasladosController@sucursalBodegaCrearSalida')->name('traslados.sucursalbodega.crearsalida');//Crear Salida
Route::post('/traslados/sucursalbodega/crear/salida', 'TrasladosController@sucursalBodegaGuardarSalida')->name('traslados.sucursalbodega.guardarsalida');//Guardar Salida
Route::get('/traslados/sucursalbodega/lista/salidas', 'TrasladosController@sucursalBodegaListaSalidas')->name('traslados.sucursalbodega.listasalidas');//Lista de Salidas
Route::post('/traslados/sucursalbodega/ver/salida', 'TrasladosController@sucursalBodegaVerSalida')->name('traslados.sucursalbodega.versalida');//Ver Detalle Salida
/*::ENTRADAS::*/
Route::get('/traslados/sucursalbodega/entradas/pendientes', 'TrasladosController@sucursalBodegaEntradasPendientes')->name('traslados.sucursalbodega.entradaspendientes');//Lista Entradas Pendientes
Route::post('/traslados/sucursalbodega/crear/entrada', 'TrasladosController@sucursalBodegaCrearEntrada')->name('traslados.sucursalbodega.crearentrada');//Ver y Crear Entrada
Route::post('/traslados/sucursalbodega/guardar/entrada', 'TrasladosController@sucursalBodegaGuardarEntrada')->name('traslados.sucursalbodega.guardarentrada');//Guardar Entrada
Route::get('/traslados/sucursalbodega/lista/entradas', 'TrasladosController@sucursalBodegaListaEntradas')->name('traslados.sucursalbodega.listaentradas');//Lista Entradas Realizadas
Route::post('/traslados/sucursalbodega/ver/entrada', 'TrasladosController@sucursalBodegaVerEntrada')->name('traslados.sucursalbodega.verentrada');//Detalle de Entrada realizada
Route::get('/traslados/sucursalbodega/ver/entrada', 'TrasladosController@sucursalBodegaVerEntrada')->name('traslados.sucursalbodega.verentrada');//Detalle de Entrada realizada redireccion GET desde Guardar E.
/*::AJAX::*/
Route::post('/traslados/empleados/sucursal/bodega', 'TrasladosController@traerEmpleadossucursalBodega')->name('traslados.empleadossucursalbodega');/*AJAX*/
Route::post('/traslados/productosSucursal', 'TrasladosController@traerProductosSucursal')->name('traslados.productossucursal');/*AJAX*/
/*::REDIRECCIONES GET::*/
Route::get('/traslados/sucursalbodega/ver/salida', 'TrasladosController@sucursalBodegaRedirectSalida');
//Route::get('/traslados/sucursalsucursal/guardar/salida', 'TrasladosController@sucursalSucursalRedirectSalida');
Route::get('/traslados/sucursalbodega/crear/entrada', 'TrasladosController@sucursalBodegaRedirectEntrada');
Route::get('/traslados/sucursalbodega/guardar/entrada', 'TrasladosController@sucursalBodegaRedirectEntrada');



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::EMPLEADOS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/empleados/crear', 'EmpleadosController@crear')->name('empleados.crear');
Route::post('/empleados/crear', 'EmpleadosController@guardar')->name('empleados.guardar');
Route::get('/empleados/lista', 'EmpleadosController@lista')->name('empleados.lista');
Route::post('/empleados/ver', 'EmpleadosController@ver')->name('empleados.ver');
Route::post('/empleados/editar', 'EmpleadosController@editar')->name('empleados.editar');
Route::post('/empleados/modificar', 'EmpleadosController@modificar')->name('empleados.modificar');
Route::post('/empleados/inactivar', 'EmpleadosController@inactivar')->name('empleados.inactivar');
Route::get('/empleados/ver', 'EmpleadosController@redirect');
Route::get('/empleados/editar', 'EmpleadosController@redirect');
Route::get('/empleados/modificar', 'EmpleadosController@redirect');



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ROLES Y PERMISOS:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::ROLES:::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/roles/crear', 'RolesController@crear')->name('roles.crear');
Route::post('/roles/crear', 'RolesController@guardar')->name('roles.guardar');
Route::get('/roles/lista', 'RolesController@lista')->name('roles.lista');
Route::post('/roles/editar', 'RolesController@editar')->name('roles.editar');
Route::post('/roles/modificar', 'RolesController@modificar')->name('roles.modificar');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::PERMISOS::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/permisos/crear', 'PermisosController@crear')->name('permisos.crear');
Route::post('/permisos/crear', 'PermisosController@guardar')->name('permisos.guardar');
Route::get('/permisos/lista', 'PermisosController@lista')->name('permisos.lista');



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::PRODUCTOS:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::PRODUCTOS:::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/productos/crear', 'ProductosController@crear')->name('productos.crear');
Route::post('/productos/crear', 'ProductosController@guardar')->name('productos.guardar');
Route::get('/productos/lista', 'ProductosController@lista')->name('productos.lista');
Route::post('/productos/ver', 'ProductosController@ver')->name('productos.ver');
Route::post('/productos/editar', 'ProductosController@editar')->name('productos.editar');
Route::post('/productos/especificaciones/editar', 'ProductosController@editarEspecificacion')->name('productos.especificacion.editar');
Route::post('/productos/especificaciones/modificar', 'ProductosController@modificarEspecificacion')->name('productos.especificacion.modificar');
Route::post('/productos/modificar', 'ProductosController@modificar')->name('productos.modificar');
Route::get('/productos/sucursales', 'ProductosController@sucursales')->name('productos.sucursales');/*AJAX*/
Route::get('/productos/bodegas', 'ProductosController@bodegas')->name('productos.bodegas');/*AJAX*/
Route::get('/productos/marca_tipo_productos', 'ProductosController@marcaTipoProductos')->name('productos.marca_tipo_productos');/*AJAX*/
Route::get('/productos/modelo_marcas', 'ProductosController@modeloMarcas')->name('productos.modelo_marcas');/*AJAX*/
Route::get('/productos/clasificaciones_tipo_productos', 'ProductosController@clasificacionesTipoProductos')->name('productos.clasificaciones_tipo_productos');/*AJAX*/
Route::get('/productos/codigoPrecio', 'ProductosController@codigoPrecio')->name('productos.codigoPrecio');/*AJAX*/
Route::get('/productos/codigos','ProductosController@codigosBarra')->name('productos.codigos');
Route::get('/productos/codigos/imprimir','ProductosController@ImprimirCodigos')->name('productos.codigos.imprimir');
Route::post('/productos/editar/precio', 'ProductosController@editarPrecio')->name('productos.editar.precio');/*AJAX*/
Route::post('/productos/editar/marca', 'ProductosController@editarMarca')->name('productos.editar.marca');/*AJAX*/
Route::post('/productos/editar/modelo', 'ProductosController@editarModelo')->name('productos.editar.modelo');/*AJAX*/

Route::get('/productos/nombres', 'ProductosController@nombres')->name('productos.nombres');//GUARDAR NOMBRES DE LOS PRODUCTOS EN EL CAMPO

Route::get('/productos/buscar', 'ProductosController@buscar')->name('productos.buscar');

Route::post('/productos/imagenes/cargar', 'ProductosController@cargar_imagen')->name('productos.imagen.cargar');

// Route::get('/productos/editar/{editar}', 'ProductosController@editar')->name('productos.editar');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::CATEGORIAS::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/categorias/crear', 'CategoriasController@crear')->name('categorias.crear');
Route::post('/categorias/crear', 'CategoriasController@guardar')->name('categorias.guardar');
Route::get('/categorias/lista', 'CategoriasController@lista')->name('categorias.lista');
Route::post('/categorias/tipoproductos', 'CategoriasController@listaTipoProductos')->name('categorias.tipoproductos');//Ver Detalle Salida
Route::post('/categorias/editar', 'CategoriasController@editar')->name('categorias.editar');
Route::post('/categorias/modificar', 'CategoriasController@modificar')->name('categorias.modificar');
Route::post('/categorias/inactivar', 'CategoriasController@inactivar')->name('categorias.inactivar');
Route::get('/categorias/tipoproductos', 'CategoriasController@redirect');
Route::get('/categorias/editar', 'CategoriasController@redirect');
Route::get('/categorias/modificar', 'CategoriasController@redirect');
Route::get('/categorias/inactivar', 'CategoriasController@redirect');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::CLASIFICACIONES::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/clasificaciones/crear', 'ClasificacionesController@crear')->name('clasificaciones.crear');
Route::post('/clasificaciones/crear', 'ClasificacionesController@guardar')->name('clasificaciones.guardar');
Route::get('/clasificaciones/lista', 'ClasificacionesController@lista')->name('clasificaciones.lista');
Route::post('/clasificaciones/ver', 'ClasificacionesController@ver')->name('clasificaciones.ver');//Ver Detalle Salida
Route::post('/clasificaciones/editar', 'ClasificacionesController@editar')->name('clasificaciones.editar');
Route::get('/clasificaciones/editar', 'ClasificacionesController@redirect');
Route::post('/clasificaciones/modificar', 'ClasificacionesController@modificar')->name('clasificaciones.modificar');
Route::get('/clasificaciones/modificar', 'ClasificacionesController@redirect');
Route::post('/clasificaciones/inactivar', 'ClasificacionesController@inactivar')->name('clasificaciones.inactivar');
Route::get('/clasificaciones/inactivar', 'ClasificacionesController@redirect');
Route::post('/clasificaciones/editar/especificacion', 'ClasificacionesController@editarEspecificacion')->name('clasificaciones.editar.especificacion');
Route::post('/clasificaciones/modificar/especificacion', 'ClasificacionesController@modificarEspecificacion')->name('clasificaciones.modificar.especificacion');
Route::post('/clasificaciones/inactivar/especificacion', 'ClasificacionesController@inactivarEspecificacion')->name('clasificaciones.inactivar.especificacion');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::TIPO PRODUCTOS::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/tipo_productos/crear', 'Tipo_ProductosController@crear')->name('tipo_productos.crear');
Route::post('/tipo_productos/crear', 'Tipo_ProductosController@guardar')->name('tipo_productos.guardar');
Route::get('/tipo_productos/lista', 'Tipo_ProductosController@lista')->name('tipo_productos.lista');
Route::post('/tipo_productos/editar', 'Tipo_ProductosController@editar')->name('tipo_productos.editar');
Route::get('/tipo_productos/editar', 'Tipo_ProductosController@redirect');
Route::post('/tipo_productos/modificar', 'Tipo_ProductosController@modificar')->name('tipo_productos.modificar');
Route::get('/tipo_productos/modificar', 'Tipo_ProductosController@redirect');
Route::post('/tipo_productos/productos', 'Tipo_ProductosController@productos')->name('tipo_productos.productos');
Route::post('/tipo_productos/inactivar', 'Tipo_ProductosController@inactivar')->name('tipo_productos.inactivar');

Route::get('/tipo_productos/buscar', 'Tipo_ProductosController@buscar')->name('tipo_productos.buscar');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::MARCAS:::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/marcas/crear', 'MarcasController@crear')->name('marcas.crear');
Route::post('/marcas/crear', 'MarcasController@guardar')->name('marcas.guardar');
Route::get('/marcas/lista', 'MarcasController@lista')->name('marcas.lista');
Route::get('/marcas/cargar', 'MarcasController@cargar')->name('marcas.cargar');
Route::post('/marcas/guardar/carga', 'MarcasController@guardarCarga')->name('marcas.guardar.carga');
Route::post('/marcas/editar', 'MarcasController@editar')->name('marcas.editar');
Route::get('/marcas/editar', 'MarcasController@redirect');
Route::post('/marcas/modificar', 'MarcasController@modificar')->name('marcas.modificar');
Route::get('/marcas/modificar', 'MarcasController@redirect');
Route::post('/marcas/inactivar', 'MarcasController@inactivar')->name('marcas.inactivar');
Route::get('/marcas/inactivar', 'MarcasController@redirect');

Route::get('/marcas/buscar', 'MarcasController@buscar')->name('marcas.buscar');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::MODELOS::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/modelos/crear', 'ModelosController@crear')->name('modelos.crear');
Route::post('/modelos/crear', 'ModelosController@guardar')->name('modelos.guardar');
Route::get('/modelos/lista', 'ModelosController@lista')->name('modelos.lista');
Route::get('/modelos/marca_tipo_productos', 'ModelosController@marcaTipoProductos')->name('modelos.marca_tipo_productos');/*AJAX*/
Route::get('/modelos/modelos_marca', 'ModelosController@modelosMarca')->name('modelos.modelos_marca');/*AJAX*/
Route::post('/modelos/editar', 'ModelosController@editar')->name('modelos.editar');
Route::post('/modelos/modificar', 'ModelosController@modificar')->name('modelos.modificar');
Route::post('/modelos/inactivar', 'ModelosController@inactivar')->name('modelos.inactivar');
Route::get('/modelos/editar', 'ModelosController@redirect');
Route::get('/modelos/modificar', 'ModelosController@redirect');
Route::get('/modelos/inactivar', 'ModelosController@redirect');

Route::get('/modelos/buscar', 'ModelosController@buscar')->name('modelos.buscar');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::PROMOCIONES::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/promociones/crear', 'PromocionesController@crear')->name('promociones.crear');
Route::post('/promociones/crear', 'PromocionesController@guardar')->name('promociones.guardar');
Route::get('/promociones/lista', 'PromocionesController@lista')->name('promociones.lista');



/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::VENTAS:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::VENTAS::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/ventas/crear', 'VentasController@crear')->name('ventas.crear');
Route::post('/ventas/guardar', 'VentasController@guardar')->name('ventas.guardar');
Route::post('/ventas/sucursal', 'VentasController@sucursal')->name('ventas.sucursal');/*AJAX*/
Route::post('/ventas/producto', 'VentasController@producto')->name('ventas.producto');/*AJAX*/
Route::post('/ventas/cliente', 'VentasController@cliente')->name('ventas.cliente');/*AJAX*/
Route::get('/ventas/lista', 'VentasController@lista')->name('ventas.lista');
Route::get('/ventas/ver', 'VentasController@ver')->name('ventas.ver');
Route::get('/ventas/saldo', 'VentasController@saldo')->name('ventas.saldo');
Route::post('/ventas/pagar/{venta}', 'VentasController@pagar')->name('ventas.pagar');
Route::get('/ventas/ventas_facturacion', 'VentasController@ventas_dia')->name('ventas.facturacion');
Route::get('/ventas/{id_venta}', 'VentasController@detalle')->name('ventas.detalle');
Route::get('/caja/cerrar', 'VentasController@cierre_caja')->name('caja.cerrar');
Route::post('/caja/cerrar/guardar', 'VentasController@cierre_caja_guardar')->name('caja.cerrar.guardar');
Route::post('/caja/buscar/registros', 'VentasController@caja_buscar_registros')->name('caja.buscar.registros');/*AJAX*/
Route::get('/caja/lista', 'VentasController@cierre_caja_lista')->name('caja.cierre.lista');
Route::post('/caja/cierres/sucursal', 'VentasController@caja_cierres_sucursal')->name('caja.cierres.sucursal');/*AJAX*/
Route::get('/caja/ingresos/egresos/crear', 'VentasController@ingresos_egresos_crear')->name('caja.ingresos.egresos.crear');
Route::post('/caja/ingresos/egresos/guardar', 'VentasController@ingresos_egresos_guardar')->name('caja.ingresos.egresos.guardar');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::CLIENTES::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/clientes/crear', 'ClientesController@crear')->name('clientes.crear');
Route::post('/clientes/crear', 'ClientesController@guardar')->name('clientes.guardar');
Route::post('/clientes/cliente', 'ClientesController@cliente')->name('clientes.cliente');/*AJAX*/
Route::get('/clientes/lista', 'ClientesController@lista')->name('clientes.lista');
Route::post('/clientes/ver', 'ClientesController@ver')->name('clientes.ver');
Route::post('/clientes/editar', 'ClientesController@editar')->name('clientes.editar');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::COTIZACIONES::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/cotizaciones/crear', 'CotizacionesController@crear')->name('cotizaciones.crear');
Route::post('/cotizaciones/crear', 'CotizacionesController@guardar')->name('cotizaciones.guardar');
Route::get('/cotizaciones/lista', 'CotizacionesController@lista')->name('cotizaciones.lista');
Route::post('/cotizaciones/cliente', 'CotizacionesController@cliente')->name('cotizaciones.cliente');/*AJAX*/
Route::post('/cotizaciones/paciente', 'CotizacionesController@paciente')->name('cotizaciones.paciente');/*AJAX*/
Route::post('/cotizaciones/modelos', 'CotizacionesController@modelos')->name('cotizaciones.modelos');/*AJAX*/
Route::post('/cotizaciones/lentes', 'CotizacionesController@lentes')->name('cotizaciones.lentes');/*AJAX*/
Route::post('/cotizaciones/buscar/lente', 'CotizacionesController@buscarLente')->name('cotizaciones.buscar.lente');/*AJAX*/
Route::get('/cotizaciones/cotizacion', 'CotizacionesController@cotizacion')->name('cotizaciones.cotizacion');
Route::get('/cotizaciones/resultado', 'CotizacionesController@resultado')->name('cotizaciones.resultado');
//Buscar Usuario
Route::get('/usuarios/buscar', 'UsuariosController@buscar')->name('usuarios.buscar');
Route::get('/usuarios/buscar/vendedor', 'UsuariosController@buscarVendedor')->name('usuarios.buscar.vendedor');
Route::get('/usuarios/cambio/precio', 'UsuariosController@verificar_cambio_precio')->name('usuarios.cambio.precio');
Route::get('/usuarios/cambio/vendedor', 'UsuariosController@verificar_cambio_vendedor')->name('usuarios.cambio.vendedor');


/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::EXAMENES:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::EXAMENES VISUALES::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/examenes/crear', 'ExamenesController@crear')->name('examenes.crear');
Route::post('/examenes/crear', 'ExamenesController@guardar')->name('examenes.guardar');
Route::post('/examenes/paciente', 'ExamenesController@paciente')->name('examenes.paciente');/*AJAX*/
Route::post('/examenes/laboratorio/marca', 'ExamenesController@laboratorioMarcas')->name('examenes.laboratorio.marca');/*AJAX*/
Route::post('/examenes/laboratorio/modelo', 'ExamenesController@laboratorioModelos')->name('examenes.laboratorio.modelo');/*AJAX*/
Route::get('/examenes/lista', 'ExamenesController@lista')->name('examenes.lista');
Route::get('/examenes/ver', 'ExamenesController@ver')->name('examenes.ver');
Route::get('/examenes/editar', 'ExamenesController@editar')->name('examenes.editar');

Route::get('/examenes/validar/{id_examen}', 'ExamenesController@validar')->name('examenes.validar');
Route::post('/examenes/validar/{id_examen}', 'ExamenesController@actualizar')->name('examenes.actualizar');

Route::get('/examenes/ver/pdf_examen/{id_examen}', 'ExamenesController@pdf_examen')->name('examenes.pdf.examen');
Route::get('/examenes/ver/pdf_formula/{id_examen}', 'ExamenesController@pdf_formula')->name('examenes.pdf.formula');
Route::get('/examenes/no_validados', 'ExamenesController@examenes_no_validados')->name('examenes.novalidados');
Route::get('/examenes/historia/{id_examen}', 'ExamenesController@historia')->name('examenes.historia');
Route::get('/examenes/prueba', 'ExamenesController@prueba')->name('examenes.prueba');//PRUEBA DOM PDF

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::PACIENTES::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/pacientes/crear', 'PacientesController@crear')->name('pacientes.crear');
Route::post('/pacientes/crear', 'PacientesController@guardar')->name('pacientes.guardar');
Route::post('/pacientes/paciente', 'PacientesController@paciente')->name('pacientes.paciente');/*AJAX*/
Route::get('/pacientes/lista', 'PacientesController@lista')->name('pacientes.lista');
Route::post('/pacientes/ver', 'PacientesController@ver')->name('pacientes.ver');
Route::post('/pacientes/editar', 'PacientesController@editar')->name('pacientes.editar');
Route::get('/triages/lista', 'PacientesController@listaTriages')->name('triages.lista');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::DIAGNOSTICOS:::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/diagnosticos/crear', 'DiagnosticosController@crear')->name('diagnosticos.crear');
Route::post('/diagnosticos/crear', 'DiagnosticosController@guardar')->name('diagnosticos.guardar');
Route::post('/diagnosticos/paciente', 'DiagnosticosController@paciente')->name('diagnosticos.paciente');/*AJAX*/
Route::get('/diagnosticos/lista', 'DiagnosticosController@lista')->name('diagnosticos.lista');
Route::post('/diagnosticos/editar', 'DiagnosticosController@editar')->name('diagnosticos.editar');
Route::get('/diagnosticos/editar', 'DiagnosticosController@redirect');
Route::post('/diagnosticos/modificar', 'DiagnosticosController@modificar')->name('diagnosticos.modificar');
Route::post('/diagnosticos/inactivar', 'DiagnosticosController@inactivar')->name('diagnosticos.inactivar');
Route::get('/diagnosticos/inactivar', 'DiagnosticosController@redirect');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::CIRUGIAS:::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/cirugias/crear', 'CirugiasController@crear')->name('cirugias.crear');
Route::post('/cirugias/crear', 'CirugiasController@guardar')->name('cirugias.guardar');
Route::post('/cirugias/paciente', 'CirugiasController@paciente')->name('cirugias.paciente');/*AJAX*/
Route::get('/cirugias/lista', 'CirugiasController@lista')->name('cirugias.lista');
Route::post('/cirugias/editar', 'CirugiasController@editar')->name('cirugias.editar');
Route::get('/cirugias/editar', 'CirugiasController@redirect');
Route::post('/cirugias/modificar', 'CirugiasController@modificar')->name('cirugias.modificar');
Route::post('/cirugias/inactivar', 'CirugiasController@inactivar')->name('cirugias.inactivar');
Route::get('/cirugias/inactivar', 'CirugiasController@redirect');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::CITAS::::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/citas/lista', 'CitaController@lista')->name('citas.lista');
Route::get('/citas/lista/hoy', 'CitaController@listaHoy')->name('citas.lista.hoy');
Route::get('/citas/crear', 'CitaController@crear')->name('citas.crear');
Route::post('/citas/crear', 'CitaController@guardar')->name('citas.guardar');
Route::get('/citas/editar/{id_cita}', 'CitaController@editar')->name('citas.editar');
Route::put('/citas/editar/{id_cita}', 'CitaController@actualizar')->name('citas.actualizar');




/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ORDENES::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::APARTADOS::::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/ordenes/apartados/lista', 'OrdenesController@lista_apartados')->name('ordenes.apartados.lista');
Route::post('/ordenes/apartados/cambiar', 'OrdenesController@apartados_cambiar')->name('ordenes.apartados.cambiar');
Route::post('/ordenes/apartados/sucursal', 'OrdenesController@apartados_sucursal')->name('ordenes.apartados.sucursal');//AJAX

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::PENDIENTES::::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/ordenes/pendientes/lista', 'OrdenesController@lista_pendientes')->name('ordenes.pendientes.lista');
Route::post('/ordenes/pendientes/cambiar', 'OrdenesController@pendientes_cambiar')->name('ordenes.pendientes.cambiar');
Route::post('/ordenes/pendientes/sucursal/laboratorio', 'OrdenesController@pendientes_sucursal_laboratorio')->name('ordenes.pendientes.sucursal.laboratorio');//AJAX

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::PRODUCCION:::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/ordenes/produccion/lista', 'OrdenesController@lista_produccion')->name('ordenes.produccion.lista');
Route::post('/ordenes/produccion/cambiar', 'OrdenesController@produccion_cambiar')->name('ordenes.produccion.cambiar');
Route::post('/ordenes/produccion/sucursal/laboratorio', 'OrdenesController@produccion_sucursal_laboratorio')->name('ordenes.produccion.sucursal.laboratorio');//AJAX
Route::get('/ordenes/produccion/historial/envios', 'OrdenesController@produccion_historial_envios')->name('ordenes.produccion.historial.envios');
Route::post('/ordenes/produccion/historial/envios/sucursal', 'OrdenesController@produccion_historial_envios_sucursal')->name('ordenes.produccion.historial.envios.sucursal');//AJAX
Route::get('/ordenes/produccion/detalle/historial/envios/{id_historial}', 'OrdenesController@produccion_detalle_historial_envios')->name('ordenes.produccion.detalle.historial.envios');
Route::get('/ordenes/produccion/detalle/historial/envios', 'OrdenesController@produccion_detalle_historial_envios')->name('ordenes.produccion.detalle.historial.envios.aux');
Route::get('/ordenes/produccion/historial/ingresos', 'OrdenesController@produccion_historial_ingresos')->name('ordenes.produccion.historial.ingresos');
Route::post('/ordenes/produccion/historial/ingresos/sucursal', 'OrdenesController@produccion_historial_ingresos_sucursal')->name('ordenes.produccion.historial.ingresos.sucursal');//AJAX
Route::get('/ordenes/produccion/detalle/historial/ingresos/{id_historial}', 'OrdenesController@produccion_detalle_historial_ingresos')->name('ordenes.produccion.detalle.historial.ingresos');
Route::get('/ordenes/produccion/detalle/historial/ingresos', 'OrdenesController@produccion_detalle_historial_ingresos')->name('ordenes.produccion.detalle.historial.ingresos.aux');
Route::get('/ordenes/produccion/imprimir/historial/envio/{id_historial}', 'OrdenesController@produccion_imprimir_historial_envio')->name('ordenes.produccion.imprimir.historial.envio');

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::CONTROL CALIDAD:::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/ordenes/calidad/lista', 'OrdenesController@lista_calidad')->name('ordenes.calidad.lista');
Route::post('/ordenes/calidad/cambiar', 'OrdenesController@calidad_cambiar')->name('ordenes.calidad.cambiar');
Route::post('/ordenes/calidad/sucursal', 'OrdenesController@calidad_sucursal')->name('ordenes.calidad.sucursal');//AJAX

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::INGRESADAS:::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/ordenes/ingresadas/lista', 'OrdenesController@lista_ingresadas')->name('ordenes.ingresadas.lista');
Route::post('/ordenes/ingresadas/cambiar', 'OrdenesController@ingresadas_cambiar')->name('ordenes.ingresadas.cambiar');
Route::post('/ordenes/ingresadas/sucursal', 'OrdenesController@ingresadas_sucursal')->name('ordenes.ingresadas.sucursal');//AJAX

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::ENTREGADAS:::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/ordenes/entregadas/lista', 'OrdenesController@lista_entregadas')->name('ordenes.entregadas.lista');
Route::post('/ordenes/entregadas/cambiar', 'OrdenesController@entregadas_cambiar')->name('ordenes.entregadas.cambiar');
Route::post('/ordenes/entregadas/sucursal', 'OrdenesController@entregadas_sucursal')->name('ordenes.entregadas.sucursal');//AJAX

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::ENVIADAS:::::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/ordenes/enviadas/lista', 'OrdenesController@lista_enviadas')->name('ordenes.enviadas.lista');
Route::post('/ordenes/enviadas/cambiar', 'OrdenesController@enviadas_cambiar')->name('ordenes.enviadas.cambiar');
Route::post('/ordenes/enviadas/sucursal', 'OrdenesController@enviadas_sucursal')->name('ordenes.enviadas.sucursal');//AJAX

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::RECHAZADAS:::::::::::::::::::::::::::::::::::::::::::::::::*/

Route::get('/ordenes/rechazadas/lista', 'OrdenesController@lista_rechazadas')->name('ordenes.rechazadas.lista');
Route::post('/ordenes/rechazadas/cambiar', 'OrdenesController@rechazadas_cambiar')->name('ordenes.rechazadas.cambiar');
Route::post('/ordenes/rechazadas/sucursal', 'OrdenesController@rechazadas_sucursal')->name('ordenes.rechazadas.sucursal');//AJAX
Route::get('/ordenes/rechazadas/historial', 'OrdenesController@historial_rechazadas')->name('ordenes.rechazadas.historial');
Route::post('/ordenes/rechazadas/historial/consulta', 'OrdenesController@historial_rechazadas_consulta')->name('ordenes.rechazadas.historial.consulta');//AJAX



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::ENVIOS::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/empresas/envio/crear', 'EmpresasEnvioController@crear')->name('empresas.envio.crear');
Route::post('/empresas/envio/crear', 'EmpresasEnvioController@guardar')->name('empresas.envio.guardar');
Route::post('/empresas/envio/paciente', 'EmpresasEnvioController@paciente')->name('empresas.envio.paciente');/*AJAX*/
Route::get('/empresas/envio/lista', 'EmpresasEnvioController@lista')->name('empresas.envio.lista');
Route::post('/empresas/envio/editar', 'EmpresasEnvioController@editar')->name('empresas.envio.editar');
Route::get('/empresas/envio/editar', 'EmpresasEnvioController@redirect');
Route::post('/empresas/envio/modificar', 'EmpresasEnvioController@modificar')->name('empresas.envio.modificar');
Route::post('/empresas/envio/inactivar', 'EmpresasEnvioController@inactivar')->name('empresas.envio.inactivar');
Route::get('/empresas/envio/inactivar', 'EmpresasEnvioController@redirect');

Route::get('/empresas/envio/crear/reporte', 'EmpresasEnvioController@crear_reporte')->name('empresas.envio.crear.reporte');
Route::post('/empresas/envio/crear/reporte', 'EmpresasEnvioController@guardar_reporte')->name('empresas.envio.guardar.reporte');
Route::get('/empresas/envio/historial/reporte', 'EmpresasEnvioController@lista')->name('empresas.envio.historial.reportes');



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::ADMIN ECOMMERCE:::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/adminecommerce/cargar', 'AdminEcommerceController@cargar')->name('adminecommerce.cargar');
Route::post('/adminecommerce/cargar', 'AdminEcommerceController@guardar')->name('adminecommerce.guardar');
Route::get('/adminecommerce/lista', 'AdminEcommerceController@lista')->name('adminecommerce.lista');
Route::get('/adminecommerce/crear/excel', 'AdminEcommerceController@crearExcel')->name('adminecommerce.crear.excel');
Route::get('/adminecommerce/cargar/excel', 'AdminEcommerceController@cargarExcel')->name('adminecommerce.cargar.excel');
Route::get('/adminecommerce/ventas', 'AdminEcommerceController@ventas')->name('adminecommerce.ventas');
Route::post('/adminecommerce/ventas/productos', 'AdminEcommerceController@ventas_productos')->name('adminecommerce.ventas.productos');
Route::post('/adminecommerce/producto/comentarios', 'AdminEcommerceController@producto_comentarios')->name('adminecommerce.producto_comentarios.ver');
Route::post('/adminecommerce/producto/comentarios/editar', 'AdminEcommerceController@producto_comentarios_editar')->name('adminecommerce.comentarios.editar');



/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::PAGINA WEB::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::::SERVICIOS:::::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/servicios/crear', 'Pagina_WebController@crear_servicios')->name('pagina.servicios.crear');
Route::post('/pagina/servicios/guardar', 'Pagina_WebController@guardar_servicios')->name('pagina.servicios.guardar');
Route::get('/pagina/servicios/lista', 'Pagina_WebController@lista_servicios')->name('pagina.servicios.lista');
Route::post('/pagina/servicios/editar', 'Pagina_WebController@editar_servicio')->name('pagina.servicios.editar');
Route::post('/pagina/servicios/modificar', 'Pagina_WebController@modificar_servicio')->name('pagina.servicios.modificar');
Route::post('/pagina/servicios/inactivar', 'Pagina_WebController@inactivar_servicio')->name('pagina.servicios.inactivar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::SLICK LOGOS MARCAS::::::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/logos_marcas/crear', 'Pagina_WebController@crear_logos_marcas')->name('pagina.logos_marcas.crear');
Route::post('/pagina/logos_marcas/guardar', 'Pagina_WebController@guardar_logos_marcas')->name('pagina.logos_marcas.guardar');
Route::get('/pagina/logos_marcas/lista', 'Pagina_WebController@lista_logos_marcas')->name('pagina.logos_marcas.lista');
Route::post('/pagina/logos_marcas/editar', 'Pagina_WebController@editar_logos_marcas')->name('pagina.logos_marcas.editar');
Route::post('/pagina/logos_marcas/modificar', 'Pagina_WebController@modificar_logos_marcas')->name('pagina.logos_marcas.modificar');
Route::post('/pagina/logos_marcas/inactivar', 'Pagina_WebController@inactivar_logos_marcas')->name('pagina.logos_marcas.inactivar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::SLICK INFOS::::::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/infos/crear', 'Pagina_WebController@crear_infos')->name('pagina.infos.crear');
Route::post('/pagina/infos/guardar', 'Pagina_WebController@guardar_infos')->name('pagina.infos.guardar');
Route::get('/pagina/infos/lista', 'Pagina_WebController@lista_infos')->name('pagina.infos.lista');
Route::post('/pagina/infos/editar', 'Pagina_WebController@editar_infos')->name('pagina.infos.editar');
Route::post('/pagina/infos/modificar', 'Pagina_WebController@modificar_infos')->name('pagina.infos.modificar');
Route::post('/pagina/infos/inactivar', 'Pagina_WebController@inactivar_infos')->name('pagina.infos.inactivar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::::::MEMBRESIAS:::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/membresias/crear', 'Pagina_WebController@crear_membresias')->name('pagina.membresias.crear');
Route::post('/pagina/membresias/guardar', 'Pagina_WebController@guardar_membresias')->name('pagina.membresias.guardar');
Route::get('/pagina/membresias/lista', 'Pagina_WebController@lista_membresias')->name('pagina.membresias.lista');
Route::post('/pagina/membresias/editar', 'Pagina_WebController@editar_membresias')->name('pagina.membresias.editar');
Route::post('/pagina/membresias/modificar', 'Pagina_WebController@modificar_membresias')->name('pagina.membresias.modificar');
Route::post('/pagina/membresias/inactivar', 'Pagina_WebController@inactivar_membresias')->name('pagina.membresias.inactivar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::PROMOCIONES PAGINA WEB:::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/promociones_pagina/crear', 'Pagina_WebController@crear_promociones_pagina')->name('pagina.promociones_pagina.crear');
Route::post('/pagina/promociones_pagina/guardar', 'Pagina_WebController@guardar_promociones_pagina')->name('pagina.promociones_pagina.guardar');
Route::get('/pagina/promociones_pagina/lista', 'Pagina_WebController@lista_promociones_pagina')->name('pagina.promociones_pagina.lista');
Route::post('/pagina/promociones_pagina/editar', 'Pagina_WebController@editar_promociones_pagina')->name('pagina.promociones_pagina.editar');
Route::post('/pagina/promociones_pagina/modificar', 'Pagina_WebController@modificar_promociones_pagina')->name('pagina.promociones_pagina.modificar');
Route::post('/pagina/promociones_pagina/inactivar', 'Pagina_WebController@inactivar_promociones_pagina')->name('pagina.promociones_pagina.inactivar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::PROMOCIONES PAGINA WEB:::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/carrusel/crear', 'Pagina_WebController@crear_carrusel')->name('pagina.carrusel.crear');
Route::post('/pagina/carrusel/guardar', 'Pagina_WebController@guardar_carrusel')->name('pagina.carrusel.guardar');
Route::get('/pagina/carrusel/lista', 'Pagina_WebController@lista_carrusel')->name('pagina.carrusel.lista');
Route::post('/pagina/carrusel/editar', 'Pagina_WebController@editar_carrusel')->name('pagina.carrusel.editar');
Route::post('/pagina/carrusel/modificar', 'Pagina_WebController@modificar_carrusel')->name('pagina.carrusel.modificar');
Route::post('/pagina/carrusel/inactivar', 'Pagina_WebController@inactivar_carrusel')->name('pagina.carrusel.inactivar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::REDES SOCIALES:::::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/redes/lista', 'Pagina_WebController@lista_redes')->name('pagina.redes.lista');
Route::post('/pagina/redes/editar', 'Pagina_WebController@editar_redes')->name('pagina.redes.editar');
Route::post('/pagina/redes/modificar', 'Pagina_WebController@modificar_redes')->name('pagina.redes.modificar');
Route::post('/pagina/redes/inactivar', 'Pagina_WebController@inactivar_redes')->name('pagina.redes.inactivar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::::SECCION KIDS:::::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/kids/crear', 'Pagina_WebController@crear_imagen_kids')->name('pagina.kids.crear');
Route::post('/pagina/kids/guardar', 'Pagina_WebController@guardar_imagen_kids')->name('pagina.kids.guardar');
Route::get('/pagina/kids/lista', 'Pagina_WebController@lista_imagen_kids')->name('pagina.kids.lista');
Route::post('/pagina/kids/editar', 'Pagina_WebController@editar_imagen_kids')->name('pagina.kids.editar');
Route::post('/pagina/kids/modificar', 'Pagina_WebController@modificar_imagen_kids')->name('pagina.kids.modificar');
Route::post('/pagina/kids/inactivar', 'Pagina_WebController@inactivar_imagen_kids')->name('pagina.kids.inactivar');
Route::get('/pagina/kids/fondo/crear', 'Pagina_WebController@crear_imagen_fondo_kids')->name('pagina.kids.fondo.crear');
Route::post('/pagina/kids/fondo/guardar', 'Pagina_WebController@guardar_imagen_fondo_kids')->name('pagina.kids.fondo.guardar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::SECCION EQUIPO:::::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/equipo/crear', 'Pagina_WebController@crear_imagen_equipo')->name('pagina.equipo.crear');
Route::post('/pagina/equipo/guardar', 'Pagina_WebController@guardar_imagen_equipo')->name('pagina.equipo.guardar');
Route::get('/pagina/equipo/lista', 'Pagina_WebController@lista_imagen_equipo')->name('pagina.equipo.lista');
Route::post('/pagina/equipo/editar', 'Pagina_WebController@editar_imagen_equipo')->name('pagina.equipo.editar');
Route::post('/pagina/equipo/modificar', 'Pagina_WebController@modificar_imagen_equipo')->name('pagina.equipo.modificar');
Route::post('/pagina/equipo/inactivar', 'Pagina_WebController@inactivar_imagen_equipo')->name('pagina.equipo.inactivar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::CONFIGURAR FUENTE::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/fuentes/cargar', 'Pagina_WebController@cargar_fuente')->name('pagina.fuentes.cargar');
Route::post('/pagina/fuentes/guardar', 'Pagina_WebController@guardar_fuente')->name('pagina.fuentes.guardar');
Route::get('/pagina/fuentes/lista', 'Pagina_WebController@lista_fuentes')->name('pagina.fuentes.lista');
Route::get('/pagina/fuentes/cambiar/{id_fuente}', 'Pagina_WebController@cambiar_fuente')->name('pagina.fuentes.cambiar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::::::::::::::::::::::::::::::::::::::::::CONFIGURAR COLORES:::::::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/colores/configurar', 'Pagina_WebController@configurar_colores')->name('pagina.colores.configurar');
Route::post('/pagina/colores/guardar', 'Pagina_WebController@cambiar_colores')->name('pagina.colores.cambiar');


/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::::::::TEXTOS WEB::::::::::::::::::::::::::::::::::::::::::::::*/


Route::get('/pagina/textos/lista', 'Pagina_WebController@lista_textos')->name('pagina.textos.lista');
Route::post('/pagina/textos/editar', 'Pagina_WebController@editar_texto')->name('pagina.textos.editar');
Route::post('/pagina/textos/modificar', 'Pagina_WebController@modificar_texto')->name('pagina.textos.modificar');


/*::::::::::::::::PHP.INI::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::*/

Route::get('/info', function(){
	return view('info');
});

/*:::::::::::::::::::::::::::::::::::::::*/
/*:::::::::::::::::::::::::::::::::::::::*/



});






/*:::::::::::::::::::::::::::::::::::PRUEBAS:::::::::::::::::::::::::::::::::::::*/
Route::get('/pruebas/crear', 'PruebasController@crear')->name('pruebas.crear');
Route::post('/pruebas/crear', 'PruebasController@guardar')->name('pruebas.guardar');
Route::get('/pruebas/lista', 'PruebasController@lista')->name('pruebas.lista');




/*:::::::::::::::::::::::::::::::::::PAYU:::::::::::::::::::::::::::::::::::::*/
Route::post('/payments/pay', 'PaymentController@pay')->name('pay');

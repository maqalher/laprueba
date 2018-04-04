<?php  

// Inocializa la creacion de las tablas nuevas
function lapizzeria_database(){
	// WPDB nos da los metodos para trabajar con tablas
	global $wpdb;
	// Agregamos una version
	global $lapizzeria_dbversion;
	$lapizzeria_dbversion = '0.5';

	//Obtenemos el prefijo
	$tabla = $wpdb->prefix.'reservaciones';

	//obtenemos el collation de la instalacion	
	$charset_collate = $wpdb->get_charset_collate();

	// Agregamos la estructura de la base de datos
	$sql = "CREATE TABLE $tabla(
		id int(9) NOT NULL AUTO_INCREMENT,
		nombre varchar(50) NOT NULL,
		fecha datetime NOT NULL,
		correo varchar(50) DEFAULT '' NOT NULL,
		telefono varchar(10) NOT NULL,
		mensaje longtext NOT NULL,
		PRIMARY KEY(id)
	) $charset_collate; ";

	// Se necesita dbDelta para ejecutar el SQL y esta en la siguiente direccion
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	//	Agregamos la verison de la BD para compararla con futuras actaulizaciones
	add_option('lapizzeria_dbversion', $lapizzeria_dbversion);

	// ACTUALIZAR EN CASO DE SER NECESARIO
	$version_actual = get_option('lapizzeria_dbversion');

	// Comparamos las 2 versiones
	if($lapizzeria_dbversion != $version_actual){
		$tabla = $wpdb->prefix.'reservaciones';
	
		// Aqui realizarias las actualizaciones
		$sql = "CREATE TABLE $tabla(
			id int(9) NOT NULL AUTO_INCREMENT,
			nombre varchar(50) NOT NULL,
			fecha datetime NOT NULL,
			correo varchar(50) DEFAULT '' NOT NULL,
			telefono varchar(10) NOT NULL,
			mensaje longtext NOT NULL,
			PRIMARY KEY(id)
		) $charset_collate; ";
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		//	Actualizamos a la version actual en caso de que asi sea
		update_option('lapizzeria_dbversion', $lapizzeria_dbversion);
	}

}
add_action('after_setup_theme', 'lapizzeria_database');


// Funcion para comparar que la version instalada es igual a la base de datos
function lapizzeriadb_revisar(){
	global $lapizzeria_dbversion;
	if(get_site_option('lapizzeria_dbversion') != $lapizzeria_dbversion){
		lapizzeria_database();
	}
}
add_action('plugins_loaded', 'lapizzeriadb_revisar');
?>

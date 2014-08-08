<?php
ini_set("zlib.output_compression", "On");

header('Content-type: application/javascript');

$con = mysql_connect("data.cargografias.org","cargomysql","presidentecarlo");
if (!$con){
	die('Could not connect: ' . mysql_error());
}
mysql_set_charset("UTF8", $con);

mysql_select_db ("cargografiasdata");


$sql = "select  " .
"cargo_id as id,c.fecha_inicio as fechainicio, c.fecha_fin as fechafin, " .
"c.persona_id as persona_id, partido_id, territorio_id, cargo_nominal_id " .
"from cargo c ";

print "__cargos_data={cargos:" . getJSONArray($sql);

$sql = "select persona_id as id, nombre, apellido from persona ";
print ",personas:" . getJSONArray($sql);

$sql = "select  " .
"cn.cargo_nominal_id as id, cn.nombre, cn.duracion,  " .
"cl.nombre as clase, " .
"ti.nombre as tipo " .
"from cargo_nominal cn " .
"left join clase_cargo cl on cl.clase_cargo_id = cn.clase_cargo_id  " .
"left join tipo_cargo ti on ti.tipo_cargo_id = cn.tipo_cargo_id  ";
print ",cargosnominales:" . getJSONArray($sql);

$sql = "select  " .
"partido_id as id, nombre " .
"from partido " ;
print ",partidos:" . getJSONArray($sql);

$sql = "select " .
"territorio_id as id, nombre " .
"from territorio " ;
print ",territorios:" . getJSONArray($sql);

print "}";

function getJSONArray($sql){
	$sth = mysql_query($sql);
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
	    $rows[] = $r;
	}
	return json_encode($rows);
}


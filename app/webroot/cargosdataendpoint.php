<?php
ini_set("zlib.output_compression", "On");

$sql = "select  " .
"c.fecha_inicio as fechainicio, c.fecha_fin as fechafin, " .
"p.nombre, p.apellido,  " .
"cn.nombre as cargo, cn.duracion as duracioncargo, " .
"par.nombre as partido, " .
"cl.nombre as cargoclase, " .
"ti.nombre as cargotipo,  " .
"te.nombre as territorio " .
"from cargo c  " .
"left join persona p on p.persona_id = c.persona_id " .
"left join cargo_nominal cn on cn.cargo_nominal_id = c.cargo_nominal_id " .
"left join partido par on par.partido_id = c.partido_id  " .
"left join clase_cargo cl on cl.clase_cargo_id = cn.clase_cargo_id " .
"left join tipo_cargo ti on ti.tipo_cargo_id = cn.tipo_cargo_id " .
"left join territorio te on te.territorio_id = c.territorio_id ";

header('Content-type: application/javascript');

$con = mysql_connect("data.cargografias.org","cargomysql","presidentecarlo");
if (!$con){
	die('Could not connect: ' . mysql_error());
}
mysql_select_db ("cargografiasdata");

$sth = mysql_query($sql);
$rows = array();
while($r = mysql_fetch_assoc($sth)) {
    $rows[] = $r;
}

print "__cargos_data = " . json_encode($rows) . ";";


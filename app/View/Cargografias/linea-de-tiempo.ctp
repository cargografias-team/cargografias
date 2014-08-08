<?php
$this->Html->css('linea-de-tiempo', null, array('inline' => false));

$this->Html->script('lib/d3.v3.1.9.min', array('inline' => false));

$this->Html->script('d3/d3-cargografia', array('inline' => false));

$this->Html->script('linea-de-tiempo', array('inline' => false));

?>

<div class="alert">
	Trabajo en progreso...
</div>	

<?php 
echo $this->Chosen->select('cargo', array(
    $cargos
));
?>

<?php 
echo $this->Chosen->select('partido', array(
    $partidos
));
?>

<?php 
echo $this->Chosen->select('territorio', array(
    $territorios
));
?>

<a id="refresh_btn" class="btn btn-success">Filtrar</a>

<a id="clear_btn" class="btn btn-primary">Limpiar</a>

<p id="linea-loader">Cargando...</p>

<div id="timeline"></div>
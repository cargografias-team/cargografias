<?php
$this->Html->css('linea-de-tiempo-legacy', null, array('inline' => false));

//http://test.soviet.com.ar/cargo/tabletop.js
$this->Html->script('http://test.soviet.com.ar/cargo/tabletop.js', array('inline' => false));

$this->Html->script('lib/d3.v3.1.9.min', array('inline' => false));
$this->Html->script('linea-de-tiempo-legacy', array('inline' => false));

?>

<div class="alert">
	Este ejemplo utiliza un googleDoc como fuente de info. Aún no usa la nueva API.
</div>	

<div id="timeline"></div>
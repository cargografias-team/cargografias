<?php
$this->Html->css('presidenciables-chile', null, array('inline' => false));

//http://test.soviet.com.ar/cargo/tabletop.js
$this->Html->script('http://test.soviet.com.ar/cargo/tabletop.js', array('inline' => false));

$this->Html->script('lib/d3.v3.1.9.min', array('inline' => false));
$this->Html->script('presidenciables-chile', array('inline' => false));

?>

<div class="alert">
	Esta visualización está usando una versión modificada sobre la 0.1 de plugin de cargografías para D3, desarrollado para <a href="http>//poderopedia.org">poderopedia.org</a>
</div>	

<h1>Cargografía Presidenciables Chile 2013</h1>
<h2>Detalle de cargos de los candidatos a presidente en 2013. </h2>
<div id="referencias-container" class="row">
	<div class="span6">
		<p>
			Pase el mouse sobre los cargos y nombres de los políticos para más información.
		</p>
		<p>
			Referencias de colores para los tipos de cargo:
		</p>
	</div>
	<!--div class="span6 "  style="background-color:black;">
		<p>Datos:</p>
		<div>
			<img src="/img/logo-beta-poderopedia.png"/>			
		</div>
	</div-->
</div>

	<hr/>

	<div id="referencias-container" class="row">
		<div class="span2">
			<span class="referencia CARGO_PUBLICO">Públicos</span> 
		</div>
		<div class="span2">
			<span class="referencia CARGO_GRUPOS">En Grupos</span> 
		</div>
		<div class="span2">
			<span class="referencia CARGO_EMPRESA">En Empresas</span> 
		</div>
		<div class="span2">
			<span class="referencia CARGO_ONG">En ONG's</span> 
		</div>
		<div class="span2">
			<span class="referencia CARGO_INTERNACIONAL">Internacionales</span> 
		</div>
		<div class="span2">
			<span class="referencia CARGO_THINK_TANKS">Think Tanks</span> 
		</div>
	</div>

	<hr/>

<div id="timeline"></div>
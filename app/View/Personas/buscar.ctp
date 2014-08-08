<!-- esto esta en buscar.ctp (NO el element)  -->
<?php
$this->Html->script('lib/watch', array('inline' => false));
$this->Html->script('lib/jquery.tmpl.min', array('inline' => false));
$this->Html->script('lib/jquery-ui.min.js', array('inline' => false));

// Cargografias timeline + cargografias dependencies
$this->Html->script('lib/underscore-min.js', array('inline' => false));
$this->Html->script('lib/d3.v3.1.9.min.js', array('inline' => false));
$this->Html->script('gz/cargosdataendpoint3.js', array('inline' => false));
$this->Html->script('lib/CargografiasTimeline.js?v=2', array('inline' => false));

$this->Html->script('buscar', array('inline' => false));
echo $this->Html->script('https://www.google.com/jsapi', array('inline' => true));
$this->Html->css('personas', null, array('inline' => false));
$this->Html->css('jquery-ui', null, array('inline' => false));
$this->Html->css('CargografiasTimeline', null, array('inline' => false));

?>
<div class="row" id="buscador">
	<div class="span12 accordion-group">
		<div class="alert-success accordion-heading alert ">
			<h3 class="titulo-tab">
				Filtro de personas</h3>
				<div class="clearfix"></div>
  		</div>
	</div>
</div>
<div class="row">
			<?php echo $this->element('Personas/buscar/filtro'); ?>
			<div id='search-resultados' class="span5">
				<?php echo $this->element('Personas/buscar/resultados'); ?>
		</div>
</div>


<div class="span4" style="display:none" >
	<h3 class="alert alert-warning" >Visualizando</h3>
	<?php echo $this->element('Personas/buscar/seleccionados'); ?>
</div>
<div class="row" id="linea-de-tiempo">
	<div class="span12 accordion-group">
		<div class="alert-error accordion-heading alert ">
			<h3 class="titulo-tab">
				Linea de tiempo</h3>
			<ul class="nav nav-pills alert-error titulo-tab-ul" >
			  <li class="active">
			  	<a id="ordenCargo" href="#">Ordenar por Cargo</a>
			  </li>
			  <li>
			    <a id="ordenNombre" href="#">Ordenar por Nombre</a>
			  </li>
		</ul>


			<div class="clearfix"></div>
  		</div>
		<div id="timelineContainer"></div>
	</div>
</div>



<div class="accordion span12" id="comparacion-reports">
</div>
<div class="accordion span12" id="persona-reports">
</div>

<div class="clearfix"></div>


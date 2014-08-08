<?php
$this->Html->css('hitos-historicos', null, array('inline' => false));

$this->Html->script('lib/watch', array('inline' => false));

$this->Html->script('lib/jquery.tmpl.min', array('inline' => false));

$this->Html->script('lib/d3.v3.1.9.min', array('inline' => false));

$this->Html->script('d3/d3-organigrama', array('inline' => false));

$this->Html->script('hitos-historicos', array('inline' => false));

?>

<div class="alert">
	Trabajo en progreso...
</div>
<h1>Hitos históricos</h1>
<h2>Seleccione un hito de la historia para ver los políticos presentes en ese momento.</h2>

<div class="row">
	<div class="span9">
		<?php 
		echo $this->Chosen->select('hito', array(
			    $hitos
			),
			array('data-placeholder' => 'Seleciona un hito...', 'class' => 'span9')
		);
		?>
	</div>
	<div class="span1">
		<button id="expand-hitos-container" type="button" class="btn btn-primary btn-mini" data-toggle="collapse" data-target="#hito-container">
		  <i class="icon-plus icon-white"></i>
		</button>
	</div>
	<div class="span1">
		<div id="hitos-loader1" class="hito-loader">
		</div>
	</div>
	<div class="span1">
		<div id="hitos-loader2" class="hito-loader">
		</div>
	</div>
</div>

<div id="hito-container" class=""></div>
<hr/>


<div id="cargos"></div>

<div class="row">
	<div class="span12">
		<div id="paginate-container" class="well"></div>
	</div>
</div>		

<script id="hito_template" type="text/template">
	<hr/>
	<div class="row">
		<div class="span3">
			<h2>${titulo}</h2>
			<p>Ocurrido el ${fecha}</p>
		</div>
		<div class="span9">
			<p>${detalle}</p>
		</div>
	</div>
</script>

<script id="paginate_template" type="text/template">
	Página ${page} de ${pageCount} | Viendo 
	{{if page*limit>count }}
		${count} 
	{{else}}
		${page*limit}
	{{/if}}
	de ${count} registros
	{{if nextPage}}
		| <a class="load-new-page btn btn-primary" rel="${page+1}">Ver más resultados</a>
	{{/if}}
</script>

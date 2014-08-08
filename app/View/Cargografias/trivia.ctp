<?php
$this->Html->css('trivia', null, array('inline' => false));

$this->Html->script('lib/jquery.tmpl.min', array('inline' => false));

$this->Html->script('lib/watch', array('inline' => false));

$this->Html->script('trivia', array('inline' => false));

?>

<div class="row">
	<h2 class="text-center span12">¿Cuánto conoce usted sobre historia política Argentina? Comprúebelo jugando...</h2>
</div>
<div id="trivia-presentacion" class="row">
	<p class="text-center span12">
		Seleccione un nivel de dificultad:
	</p>
	<hr/>
	<div class="span4 text-center">
		<p><a class="btn btn-success btn-large jugar_btn" rel="1">Doña Rosa</a></p>
		<p>Dificultad: <strong>BAJA</strong></p>
		<p>Se preguntará sobre:</p>
		<p><strong>Presidente, Vicepresidente, Gobernador</strong></p>	
	</div>
	<div class="span4 text-center">
		<p><a class="btn btn-warning btn-large jugar_btn" rel="2">Rolando Graña</a></p>
		<p>Dificultad: <strong>INTERMEDIA</strong></p>
		<p>Se preguntará sobre:</p>
		<p><strong>Presidente, Vicepresidente, Gobernador<br/>
			Ministro, Diputado, Senador, Intendente</strong></p>	
	</div>
	<div class="span4 text-center">
		<p><a class="btn btn-danger btn-large jugar_btn" rel="3">Andy Tow</a></p>
		<p>Dificultad: <strong>ALTA</strong></p>
		<p>Se preguntará sobre:</p>
		<p><strong>TODOS los cargos de nuestra Base de Datos.</strong></p>
	</div>
</div>

<div class="row">
	<div id="progress-bar" class="progress span12">
	  
	</div>
</div>

<div id="trivia-playground" class="row">

	<div id="trivia" class="span8">
	</div>

	<div id="trivia-resultado" class="span12">
		<h2 class="text-center">En el nivel <strong data-bind="level"></strong> usted ha conseguido <strong data-bind="points"></strong> puntos en las <strong data-bind="qty"></strong> preguntas que le realizamos.</h2>
		<div class="final_container">
		</div>
		<p class="text-center">
			<a href="https://twitter.com/share" class="twitter-share-button" data-lang="es" data-text="HOLA">Tweet</a>
		</p>
		<p class="text-center">
			<a id="reiniciar_btn" class="btn btn-success btn-large">Jugar otra vez!!</a>
		</p>
	</div>

	<div id="trivia-panel" class="span4">
		<h2 class="alert alert-success">Resultados</h2>
		<div class="well">
			<p>Nivel: <strong data-bind="level"></strong></p>
			<p>Intento nro: <strong data-bind="qty"></strong></p>
			<p>Puntos: <strong data-bind="points"></strong></p>
		</div>
		<div id="trivia-loader">
		</div>
	</div>

</div>

<script id="bar_template" type="text/template">
	<div class="bar bar-{{if correcto==true}}success{{else}}danger{{/if}}" style="width: 10%"></div>
</script>

<script id="final_template" type="text/template">
	<table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Pregunta</th>
          <th>Respuesta correcta</th>
          <th>Su respuesta</th>
        </tr>
      </thead>
      <tbody>
		{{each respuestas}}
			<tr>
	          <td>${$index+1}</td>
	          <td>{{html $value.pregunta}}</td>
	          <td>${$value.respuesta}</td>
	          <td>
	          	{{if $value.correcto == true }}
	          		<a class="btn btn-success indicador-respuesta" data-toggle="tooltip" title="${$value.userRespuesta}">Correcta!</a>
	          	{{else}}
	          		<a class="btn btn-danger indicador-respuesta" data-toggle="tooltip" title="${$value.userRespuesta}">Error!</a>
	          	{{/if}}
	          </td>
	        </tr>
		{{/each}}
	   	</tbody>
    </table>
</script>

<script id="quiz_template" type="text/template">
	<h2 class="alert alert-warning">Pregunta sobre "${tipo}"</h2>
	<div class="row">
		<h2 id="quiz_pregunta" class="text-center span8">
			{{if tipo == 'persona'}}
				¿Quién ocupó el cargo de <b>${correcto.CargoNominal.nombre}</b> en <b>${correcto.Territorio.nombre}</b> durante el período <b>${correcto["0"].fecha_inicio} - ${correcto["0"].fecha_fin}</b>?
			{{/if}}	
			{{if tipo == 'cargo'}}
				¿Cuál fue el cargo que ocupó <b>${correcto.Persona.nombre} ${correcto.Persona.apellido}</b> en <b>${correcto.Territorio.nombre}</b> durante el período <b>${correcto["0"].fecha_inicio} - ${correcto["0"].fecha_fin}</b>?
			{{/if}}	
			{{if tipo == 'periodo'}}
				¿Durante qué período <b>${correcto.Persona.nombre} ${correcto.Persona.apellido}</b> ocupó el cargo <b>${correcto.CargoNominal.nombre}</b> en <b>${correcto.Territorio.nombre}</b>?
			{{/if}}
		</h2>
		<p class="text-center">
			{{each opciones}}
					{{if tipo == 'persona'}}
						<a href="javascript:;" class="btn btn-large respuesta" data-valid="{{if $value.Persona.persona_id == correcto.Persona.persona_id}}true{{else}}false{{/if}}">
							${$value.Persona.nombre} ${$value.Persona.apellido}
						</a>
					{{/if}}	
					{{if tipo == 'cargo'}}
						<a href="javascript:;" class="btn btn-large respuesta" data-valid="{{if $value.CargoNominal.cargo_nominal_id == correcto.CargoNominal.cargo_nominal_id}}true{{else}}false{{/if}}">
							${$value.CargoNominal.nombre}
						</a>	
					{{/if}}	
					{{if tipo == 'periodo'}}
						<a href="javascript:;" class="btn btn-large respuesta" data-valid="{{if $value["0"].fecha_inicio+$value["0"].fecha_fin == correcto["0"].fecha_inicio+correcto["0"].fecha_fin}}true{{else}}false{{/if}}">
							${$value["0"].fecha_inicio} - ${$value["0"].fecha_fin}
						</a>		
					{{/if}}	
			{{/each}}
		</p>
	</div>
	<div class="row text-center">
		<a id="siguiente_btn" class="btn btn-large btn-info disabled span7">Siguiente</a>
	</div>	

</script>
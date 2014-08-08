<div class="span11 accordion-group" style="margin: 6px 0px 32px 20px;padding: 10px;background-color: #fefefe;" itemscope itemtype="http://schema.org/Organization">
	<div class="span9" >
				<img  itemprop="logo" width="300px" src="img/logo.svg">
	</div>
	<div class="span7"  >
	  	<h2 class="" style="margin-top: 0px;">La línea de tiempo de Funcionarios Argentinos</h2>
	  	<span itemprop="name">Cargografías</span> es una linea de tiempo que muestra los cargos públicos que tuvo cada funcionario a lo largo de su vida. Es una fuente de consulta que busca facilitar el analisis y comprensión de la historia reciente argentina.<br><br>
	  	<a href="/personas/buscar"><img src="img/caputra_timeline.JPG"></a>
		<a class="btn btn-danger btn-large" href="/personas/buscar" style="width: 238px;font-size: 17px;display: block;margin-top: 10px;margin-bottom: 20px;">
	  		BUSCAR Y COMPARAR <i class="icon-remove icon-chevron-right icon-white"></i>
	  	</a>
		<div class="row span6">
		  		<p dir="ltr">Cargografías aspira a una arquitectura de datos que pueda reflejar, sintetizar y simplificar el entramado gubernamental. Una forma de humanizar esta estructura es a fuerza de nombres y apellidos, partidos, cargos y funciones.</p>
		  		<br>
		  	</div><div class="row span6">
		 		<blockquote>
		 			<p dir="ltr">No es una historia, pero sí un recorrido. </p>
		 			<p dir="ltr">No es una denuncia, pero sí un dato.</p>
		 			<p dir="ltr">El eje rector son los funcionarios públicos.</p>
		 			<p dir="ltr">Los destinatarios, los ciudadanos.</p>
		 		</blockquote>
		 	</div><div class="row span6">
		 		<br><br>
		 		<p dir="ltr">Cargografías busca agrupar en una sola herramienta, datos publicos que se encuentran dispersos o inaccesibles, para brindarlos a la comunidad.</p>
		 		<div class="alert alert-info" style="line-height: 25px;padding: 20px 30px;">
				        <strong>PLANES FUTUROS</strong>
				  <br> • Dormir y ponernos al día con nuestros trabajos 'reales'
				  <br> • Mejorar la interfase y la performance del sitio
				  <br> • Sumar otras fuentes de datos (<a href="http://interactivos.lanacion.com.ar/declaraciones-juradas" target="_blank">DDJJ de La Nacion Data</a>, por ej)
				  <br> • Conseguir financiación para pagar gastos (hosting, galletitas, horas de programadores)
				  <br> • Mostrar las Bio de los funcionarios (fechas, foto, CV)
				  <br> • Relacionar los cargos por bloques y votaciones (<a href="http://www.decadavotada.com.ar" target="_blank">Década Votada</a>)
				  <br> • Visualizar los partidos y frentes políticos
				  <br><strong> • Liberar nuestros datos, y hacer una API de consulta </strong>
				  <br> • Permitir al visitante corregir datos o aportar nuevos facilmente
				  <br>
				    <br>
				  ¿Tenés mas ideas?  ¿Ganas de sumarte?   Contactanos <a href="https://docs.google.com/a/soviet.com.ar/forms/d/1NoOYENvhHXqpLO3WpB8l6R8ofJkJiShLlx2A_DfrNd0/viewform" target="_blank">aqui</a>  <br>  
				</div>
		 		<br>
		 		<p dir="ltr">Cargografias es un proyecto experimental, y busca evolucionar, crecer, aprender y compartir. Es por eso que trabaja con código abierto y busca no competir con otros proyectos sino coexistir y compartir.</p>
		 		<br>
		 		<p dir="ltr">La base de datos de Cargografias, fue creada sumando y curando diversas fuentes. No esta completa y se seguira completando y corrigiendo continuamente. Asimismo, la herramienta de visualizacion y consulta, seguira creciendo y agregando funciones.</p>
		 		<br>
		 		<p dir="ltr">Cargografías esta siempre dispuesto a corregir, aumentar y editar la base de datos para que la información sea fiel a la realidad y accesible a todos los ciudadanos.</p><br><br><br><br>
		 	</div>
		 </div>

		<div class="row span3 alert">
			Cargografias en los Medios:<br>
			  •	<a href="http://www.elotromate.com/tecnologia/se-lo-que-hicieron-en-el-gobierno-pasado/" target="_blank">Sé lo que hicieron el gobierno pasado</a> <br>
			  •	<a href="http://www.telam.com.ar/notas/201308/30636-cargografias-un-proyecto-argentino-para-conocer-a-los-candidatos-de-las-elecciones.html" target="_blank">De la MediaParty, Telam</a><br>
			  •	<a href="http://agepeba.org/lectura.asp?id=6814" target="_blank">De visita por la UNLP</a><br>
		</div>
		<div class="row span3">
			<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/Cargografias" data-widget-id="390473790817193984">Tweets por @Cargografias</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
	</div>	

<div class="row counters">
	<div class="span4 text-center">
		<div class="well">
			<h2 class="text-center"><?php echo number_format($count_personas, 0, ',', '.'); ?></h2>
			<h3>Políticos | Funcionarios</h3>
		</div>
	</div>

	<div class="span4 text-center">
		<div class="no-well">
		    <h2 class="text-center"><?php echo number_format($count_cargos, 0, ',', '.'); ?></h2>
		    <h3>Cargos | Períodos</h3>
		</div>
	</div>

	<div class="span4 text-center">
		<div class="well">
		    <h2 class="text-center"><?php echo $count_hitos; ?></h2>
		    <h3>Hitos históricos</h3>
		</div>
	</div>

</div>

<div class="row counters">

	<div class="span4 text-center">
		<div class="no-well">
			<h2 class="text-center"><?php echo $count_partidos; ?></h2>
			<h3>Partidos políticos</h3>
		</div>
	</div>

	<div class="span4 text-center ">
		<div class="well">
		    <h2 class="text-center"><?php echo $count_cargos_nominales; ?></h2>
		    <h3>Cargos nominales</h3>
		</div>
	</div>

	<div class="span4 text-center">
		<div class="no-well">    
		    <h2 class="text-center"><?php echo $count_territorios; ?></h2>
		    <h3>Territorios | Jurisdicciones</h3>
		</div>
	</div>

</div>

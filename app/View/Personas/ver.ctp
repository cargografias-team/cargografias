<?php 
	$this->Html->css('personas', null, array('inline' => false));
	echo $this->Html->script('https://www.google.com/jsapi', array('inline' => true));
?>

<h2>
	<?php echo $report->data['Persona']['nombre'] . ' ' . $report->data['Persona']['apellido']; ?>
</h2>
<div id="persona_<?php echo $report->data['Persona']['persona_id']?>" class="accordion-body collapse in">
	<div class="personas index span12">
		<table class="table" cellpadding="0" cellspacing="0">
			<?php foreach($report->columns as $column => $value) : ?>
				<tr>
					<td><?php echo $column; ?>&nbsp;</td>
					<td><?php echo $value; ?>&nbsp;</td>
				</tr>
			<?php endforeach; ?>
		</table>
		<div class='charts'>
			<?php
					$id = "draw_" . md5(uniqid(rand(), true));
			 	 	echo $this->GChart->start($id);
			 	 	echo $this->GChart->visualize($id, $report->territorios_chart);
			 		
					$id = "draw_" . md5(uniqid(rand(), true));
					echo $this->GChart->start($id);
					echo $this->GChart->visualize($id, $report->partidos_chart);
	
					$id = "draw_" . md5(uniqid(rand(), true));
					echo $this->GChart->start($id);
					echo $this->GChart->visualize($id, $report->poderes_chart);
	
					$id = "draw_" . md5(uniqid(rand(), true));
					echo $this->GChart->start($id);
					echo $this->GChart->visualize($id, $report->electivos_chart);
			?>	
		</div>
		<table class="table" cellpadding="0" cellspacing="0">
			<tr>
				<th>Cargo</th>
				<th>Desde</th>
				<th>Hasta</th>
				<th>Territorio</th>
				<th>Partido</th>
				<th>Poder</th>
			</tr>
			<?php foreach($report->cargos as $cargo) : ?>
				<tr>
					<td><?php echo $cargo['CargoNominal']['nombre']?></td>
					<td><?php echo $cargo['fecha_inicio']?></td>
					<td><?php echo $cargo['fecha_fin']?></td>
					<td><?php echo $cargo['Territorio']['nombre']?></td>
					<td><?php echo isset($cargo['Partido']['nombre']) ? $cargo['Partido']['nombre'] : '(no hay datos)';?></td>
					<td><?php echo $cargo['CargoNominal']['TipoCargo']['nombre']?></td>
				</tr>
			<?php endforeach; ?>

		</table>
	</div>
</div>

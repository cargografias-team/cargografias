<!-- esto esta en ver.ctp -->
<div class="accordion-group">
	<div class="accordion-heading clearfix">
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#persona-reports" href="#persona_<?php echo $report->data['Persona']['persona_id']?>">
			<i class="icon-user"></i>
			<?php echo $report->data['Persona']['nombre'] . ' ' . $report->data['Persona']['apellido']; ?> <i class="icon-chevron-down"></i>
		</a>
		<?php echo $this->Html->link('Permalink', array('action'=>'ver', 'id'=>$report->data['Persona']['persona_id']), array('target'=>'_BLANK', 'class'=>'permalink')); ?>
		<a class="accordion-close" href="#">
			<i class="icon-remove"></i>
		</a>
	</div>
	<div id="persona_<?php echo $report->data['Persona']['persona_id']?>" class="accordion-body collapse in">
		<div class="accordion-inner">

			<div class="personas index span12">
				<table class="table" cellpadding="0" cellspacing="0">
					<?php foreach($report->columns as $column => $value) { ?>
					    <?php if ($value == "0") { continue; } ?>
						<tr>
							<td><?php echo $column; ?>&nbsp;</td>
							<td><?php echo $value; ?>&nbsp;</td>
						</tr>
					<?php } ?>
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
				<table class="table table-bordered table-striped table-hover" cellpadding="0" cellspacing="0">
					<tr>
						<th>Cargo</th>
						<th>Desde</th>
						<th>Hasta</th>
						<th>Territorio</th>
						<th>Partido</th>
					</tr>
					<?php foreach($report->cargos as $cargo) : ?>
						<tr <?php
						 	switch ($cargo['CargoNominal']['TipoCargo']['nombre']) {
								case 'Ejecutivo':
									echo 'class="Ejecutivo"';
									break;
								case 'Legislativo':
									echo 'class="Legislativo"';
									break;
								case 'Judicial':
									echo 'class="Judicial"';
									break;	
								default:
									# code...
									break;
							}?>
						>
							<td><?php echo $cargo['CargoNominal']['nombre']//+' '+$cargo['CargoExtendido']['cargo_especifico']?></td>
							<td><?php echo $cargo['fecha_inicio']?></td>
							<td><?php echo $cargo['fecha_fin']?></td>
							<td><?php echo $cargo['Territorio']['nombre']?></td>
							<td><?php echo isset($cargo['Partido']['nombre']) ? $cargo['Partido']['nombre'] : '(no hay datos)';?></td>
						</tr>
					<?php endforeach; ?>

				</table>
			</div>


		</div>
	</div>
</div>

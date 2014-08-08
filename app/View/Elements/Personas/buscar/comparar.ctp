<!-- esto esta en comparar.ctp -->
<div class="accordion-group">
	<div class="accordion-heading clearfix">
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#persona-reports" href="#persona_<?php echo $accodion_id = md5(uniqid(rand(), true)); ?>">
			<?php echo 'Comparacion de personas seleccionadas'; ?>
		</a>
	</div>
	<div id="persona_<?php echo $accodion_id; ?>" class="accordion-body collapse in">
		<div class="accordion-inner">

			<div class="personas index">
				<table class="table" cellpadding="0" cellspacing="0">
					<th>
						<td></td>
						<?php foreach($reports[0]->columns as $column => $value) : ?>
							<td><?php echo $column; ?></td>
						<?php endforeach; ?>
					</th>
					<?php foreach($reports as $report) : ?>
						<tr>
							<td>
								<?php echo h($report->data['Persona']['nombre']) . ' ' . h($report->data['Persona']['apellido']); ?>
							</td>
							<td>
								<a data-id="<?php echo h($report->data['Persona']['persona_id']); ?>" style="float:right" class="btn btn-mini eliminar-buscar btn-danger">
									<i class="icon-remove icon-white"></i>
								</a>
								<a class="btn btn-mini btn-primary" style="float:right" href="<?php echo Router::url(array('action'=>'ver', 'id'=>$report->data['Persona']['persona_id'])); ?>" id="ver-persona-<?php echo $report->data['Persona']['persona_id'];?>" target="_blank"> Ver <i class="icon-user icon-white"></i></a>
							</td>
							<?php foreach($report->columns as $column => $value) : ?>
								<td><?php echo $value; ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>

		</div>
	</div>
</div>
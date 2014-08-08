<div class="partidos index">
	<h2><?php echo __('Partidos'); ?></h2>
	<table class="table" cellpadding="0" cellspacing="0">
		<tr>
			<th>Partido</th>
			<th>Nombre</th>
			<th>Sigla</th>
		</tr>
		<?php foreach ($partidos as $partido): ?>
			<tr>
				<td><?php echo h($partido['Partido']['partido_id']); ?>&nbsp;</td>
				<td><?php echo h($partido['Partido']['nombre']); ?>&nbsp;</td>
				<td><?php echo h($partido['Partido']['sigla']); ?>&nbsp;</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>

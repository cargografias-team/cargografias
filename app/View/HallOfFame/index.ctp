<h1>Hall of Fame - Estadisticas</h1>
<table class="table" cellpadding="0" cellspacing="0">
	<tr>
		<th>Persona</th>
		<th><?php echo $this->Paginator->sort('HallOfFameStat.anios', 'Años totales en cargos') ?></th>
		<th><?php echo $this->Paginator->sort('HallOfFameStat.anios_en_mismo_cargo', 'Años en un mismo cargo') ?></th>
		<th><?php echo $this->Paginator->sort('HallOfFameStat.num_cargos_distintos', 'Numero de cargos distintos') ?></th>
		<th><?php echo $this->Paginator->sort('HallOfFameStat.num_reelecciones', 'Numero de reelecciones consecutivas') ?></th>
	</tr>
	<?php foreach($stats as $stat) : ?>
		<tr>
			<td>
				<?php echo h($stat['Persona']['nombre']) . ' ' . h($stat['Persona']['apellido']); ?>
			</td>
			<td>
				<?php echo $stat['HallOfFameStat']['anios']; ?>
			</td>
			<td>
				<?php echo $stat['HallOfFameStat']['anios_en_mismo_cargo']; ?>
			</td>
			<td>
				<?php echo $stat['HallOfFameStat']['num_cargos_distintos']; ?>
			</td>
			<td>
				<?php echo $stat['HallOfFameStat']['num_reelecciones']; ?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<?php echo $this->Paginator->numbers(); ?>
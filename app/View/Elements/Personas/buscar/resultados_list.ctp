<!-- esto esta en resultados_list.ctp -->
<?php foreach ($personas as $persona): ?>
	<tr>
		<td><?php echo h($persona['Persona']['apellido']); ?>&nbsp;</td>
		<td><?php echo h($persona['Persona']['nombre']); ?>&nbsp;</td>
		<td class="actions">
			<a class="btn btn-mini btn-primary hidden" href="<?php echo Router::url(array('action'=>'ver', 'id'=>$persona['Persona']['persona_id'])); ?>" id="ver-persona-<?php echo $persona['Persona']['persona_id'];?>" data-remote="1" > Ver <i class="icon-user icon-white"></i></a>
			<input type="hidden" value='<?php echo json_encode($persona['Persona']); ?>' />
			<a class="btn btn-mini agregar-buscar btn-warning" href="#">Agregar <i class="icon-chevron-up icon-white"></i></a>
		</td>
	</tr>
<?php endforeach; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		Cargografías Admin:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->script('lib/jquery-2.0.0.min');
		echo $this->Html->script('lib/jquery.form');
		echo $this->Html->script('lib/jquery.colorbox-min');
		
		echo $this->Html->script('admin');
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('admin');
		echo $this->Html->css('colorbox');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
		<?php if(!isset($popup)){ ?>
			<div id="header">
				<a class="logo" href="/admin">
					<h1>CargoGrafías</h1>
				</a>
				
				<?php if(isset($authUser)){ ?>
					<a href="/logout" class="logout">Hola <?php echo $authUser['username'] ?> -salir-</a>
					<ul class="menu">
						<li><a href="/admin/cargos">Cargo</a></li>
						<li><a href="/admin/cargoNominals">Cargo Nominal</a></li>
						<li><a href="/admin/claseCargos">Clase Cargo</a></li>
						<li><a href="/admin/fuentes">Fuente</a></li>
						<li><a href="/admin/hitos">Hito</a></li>
						<li><a href="/admin/motivoFins">Motivo fin</a></li>
						<li><a href="/admin/partidos">Partido</a></li>
						<li><a href="/admin/patrimonios">Patrimonio</a></li>
						<li><a href="/admin/personas">Persona</a></li>
						<li><a href="/admin/territorios">Territorio</a></li>
						<li><a href="/admin/tipoCargos">Tipo Cargo</a></li>
						<li><a href="/admin/users">Usuarios</a></li>
						<li><a href="/admin/openData">Regenerar OpenData</a></li>
						<li><?php echo $this->Html->link('Hall of Fame', '/admin/hall_of_fame'); ?></li>
					</ul>
				<?php }?>


			</div>		
		<?php } ?>

		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>

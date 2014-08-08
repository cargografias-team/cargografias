<!DOCTYPE html>
<html lang="en" class="">
  <head>
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-18158765-5']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
	<title>
		Cargografías:	Linea de Tiempo de Funcionarios Argentinos<?php //echo $title_for_layout; ?>
	</title>

  	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<?php echo $this->Html->charset(); ?>

	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('bootstrap.min');

    echo $this->Html->css('font-awesome.min.css'); 
    


		echo $this->Html->css('publico');

		echo $this->Html->css('jquery-ui.css');

		echo $this->fetch('meta');
		echo $this->fetch('css');
	?>

    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>

	<?php echo $this->Html->css('bootstrap-responsive.min'); ?>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <?php
		echo $this->Html->script('lib/html5shiv');
		?>
    <![endif]-->
    
    <!--[if IE 7]>
      <?php echo $this->Html->css('font-awesome-ie7.min.css'); ?>
    <![endif]-->
    
  </head>

  <body class="angled stripes">

    <!--div class="modal-backdrop fade in"></div-->

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="/">Cargografías</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="/personas/buscar" alt="buscar y comparar historias políticas">Buscar y Comparar</a><?php //echo $this->Html->link('Buscar y Comparar', array('controller'=>'personas', 'action'=>'buscar')); ?></li>
              <li><a href="/proyecto" alt="acerca de cargografias">El Proyecto</a></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"  alt="aportar a cargografias">Dejanos tu comentario   </a>  
                <div class="dropdown-menu">
                  <iframe src="https://docs.google.com/forms/d/1NoOYENvhHXqpLO3WpB8l6R8ofJkJiShLlx2A_DfrNd0/viewform?embedded=true&amp;hl=es" width="500" height="765" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>
                </div> 
              </li>
              
            </ul>
            <ul class="nav pull-right">
              
              <li><?php echo $this->Html->link('Blog', array('controller'=>'blog', 'action'=>'index')); ?></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Labs <b class="caret"></b></a>  
                <ul class="dropdown-menu">  
                	<li><a href="/cargografia/trivia">Trivia</a></li>
                  	<li><a href="/cargografia/hitos-historicos" alt="Contexto historico de los cargos de gobierno">Hitos Históricos <span class="text-info">| Beta </span></a></li>
                  	<li><a href="/cargografia/presidenciables-chile" alt="adaptación para las elecciones en chile">Presidenciables para Poderopedia <span class="text-info">| Beta </span></a></li>
                  	<li><?php echo $this->Html->link('Hall of Fame', '/hall_of_fame'); ?></li>
                </ul> 
              </li>
              <?php //echo '<li>'.$this->Html->link('OpenData', '/open-data').'</li>'; REMEADO OPEN DATA HASTA QUE TENGAMOS ALGO ?>
              <li><a href="/admin" target="_blank">Admin</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>


    <div class="container">

    <?php if ($this->Session->check('Message.flash')) { ?>  
      <div class="alert">
        <a class="close">×</a>
        <?php echo $this->Session->flash(); ?>
      </div>
    <?php } ?>

		<?php echo $this->fetch('content'); ?>

      <footer class="well">
        <p class="pull-right">
          <a href="https://twitter.com/Cargografias" class="twitter-follow-button" data-show-count="false" data-lang="es" data-size="large" data-dnt="true">Seguir a @Cargografias</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
      
        </p>
        <p style="margin:0px">2013 Cargografías  · 2013 · <a href="/proyecto">Acerca del Proyecto</a> | <a href="http://www.google.com/recaptcha/mailhide/d?k=01C7lOvjG_N7EaBpT3Yp2Lmw==&amp;c=so8gSuVp-5Xe2ocyAzNPdAZ8Uq5kd15DyhceyfZw3qY=" onclick="window.open('http://www.google.com/recaptcha/mailhide/d?k\07501C7lOvjG_N7EaBpT3Yp2Lmw\75\75\46c\75so8gSuVp-5Xe2ocyAzNPdAZ8Uq5kd15DyhceyfZw3qY\075', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300'); return false;" title="Reveal this e-mail address">equip...@cargografias.org</a></p>
      </footer>

    </div> <!-- /container -->

	<?php
		echo $this->Html->script('lib/jquery-2.0.0.min');
    echo $this->Html->script('lib/jquery.hashchange.min');
    echo $this->Html->script('lib/modernizr-2.5.3.min');
    echo $this->Html->script('lib/bootstrap.min');
	echo $this->Html->script('our_lib');
	echo $this->Html->script('publico');
    echo $this->fetch('script');
	 ?>
     
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-8208559-4', 'cargografias.org');
  ga('send', 'pageview');

</script>

  </body>
</html>

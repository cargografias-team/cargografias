<?php 
function getDirectoryList ($directory) 
  {

    // create an array to hold directory list
    $results = array();

    // create a handler for the directory
    $handler = opendir($directory);

    // open directory and walk through the filenames
    while ($file = readdir($handler)) {

      // if file isn't this directory or its parent, add it to the results
      if ($file != "." && $file != "..") {
      	$partes = explode('_', $file);
      	$nombre = $partes[0];
      	$data = array();
      	$data['size'] = filesize($directory.DS.$file);
     	$data['link'] = DS . 'files' . DS . 'open-data' . DS . $file;
      	$data['type'] = pathinfo($directory.DS.$file);
      	$data['type'] = $data['type']['extension'];
      	$data['mod'] = filemtime($directory.DS.$file);
        $results[$nombre][] = $data;
      }

    }

    // tidy up: close the handler
    closedir($handler);

    foreach ($results as $key => $value) {
    	$temp = array_reverse($value);
    	$results[$key] = (count($temp))?array_slice($temp,0,3):$temp;
    }

    // done!
    return $results;

  }

  $csvs = getDirectoryList(WWW_ROOT . DS . 'files' . DS . 'open-data');

 ?>

<div class="row proyecto">

	<div class="span6">
		<img src="/img/keep-calm.png" />
	</div>	

	<div class="span6">
		<h2 class="alert">¡Liberamos toda nuestra data!</h2>
		<p>¡Quieto ahí, hacker! ¡No nos scrapees! Te damos toda nuestra información en unos simpáticos archivos csv.</p>
		<p>Nos costó mucho conseguir toda esta información y te la estamos regalando; usala con criterio y creatividad.</p>
		<p>Todos sabemos que no es la forma más cómoda, pero paciencia. Ya agregaremos más formatos, e incluso pensamos en armar una API. :D</p>
		<hr/>
		
		<h2 class="alert alert-success">Datasets disponibles</h2>
		<div class="accordion" id="accordion2">
		  
		  <?php foreach ($csvs as $file => $list) { ?>
			  <div class="accordion-group">
			    <div class="accordion-heading">
			      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse-<?php echo $file;?>">
			        <?php echo $file;?>
			      </a>
			    </div>
			    <div id="collapse-<?php echo $file;?>" class="accordion-body collapse">
			      <div class="accordion-inner">
			        
			      	<table class="table table-condensed">
					  <thead>
					    <tr>
   					      <th>Fecha</th>
					      <th>Tamaño</th>
					      <th>Extensión</th>
   					      <th><i class="icon-download-alt"></i></th>
					    </tr>
					  </thead>
					  <tbody>
					  	<?php foreach ($list as $i => $v) { ?>
						    <tr>
   						      <td><?php echo date('Y-M-d H:i:s',$v['mod']);?></td>
						      <td><?php echo $v['size'];?></td>
						      <td><?php echo $v['type'];?></td>
   						      <td><a class="btn btn-mini btn-info" href="<?php echo $v['link'];?>"><i class="icon-download-alt icon-white"></i></a></td>
						    </tr>
						<?php } ?> 
					  </tbody>
					</table>


			      </div>
			    </div>
			  </div>
		  <?php } ?>
		  
		</div>
		
	</div>	

</div>	


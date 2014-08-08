<form method="post" action="<?php Router::url('/login')?>" >
	<label for="usuario" >Correo electrónico</label>
	<input id="username" name="User[username]" placeholder="username" type="text" value="" />
	<label for="pass">Contrase&ntilde;a</label>
	<input id="password" name="User[password]" placeholder="password" type="password" value="" />
	<input id="submit" value="submit" type="submit" />
</form>

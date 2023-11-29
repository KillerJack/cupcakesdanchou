<?php
if($logado && empty($_GET['logout']))
{
	echo '<meta http-equiv="refresh" content="0;url=index.php">';
	exit;
}
else if ($logado && !empty($_GET['logout']))
{
	logout();
}
else if($logado == 0 && !empty($_POST['cadastrar']) && empty($_GET['logout']))
{
	register_user();
	exit;
}
else {

	if(isset($_POST['user_login']) && isset($_POST['user_pass']))
	{
		log_user_in();
		die();
	}

?>

<?=template_header('Login')?>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<div class="login content-wrapper">

		<div class="form-wrap">
		    <div class="tabs">
		      <h3 class="login-tab"><a class="active" href="#login-tab-content">Login</a></h3>
		      <h3 class="signup-tab"><a href="#signup-tab-content">Cadastrar</a></h3>
		    </div><!--.tabs-->

		    <div class="tabs-content">
		      <div id="login-tab-content" class="active">
		        <form class="login-form" action="index.php?page=login" method="post">
		          <input type="mail" class="input" id="user_login" autocomplete="on" placeholder="Email" name="user_login">
		          <input type="password" class="input" id="user_pass" autocomplete="off" placeholder="Senha" name="user_pass">
		          <input type="checkbox" class="checkbox" id="remember_me" name="remember_me">
		          <label for="remember_me">Manter-me logado</label>

		          <input type="submit" class="button" value="Login">
		        </form><!--.login-form-->
		        <div class="help-text">
		          <p><a href="#">Esqueceu sua senha?</a></p>
		        </div><!--.help-text-->
		      </div><!--.login-tab-content-->

		      <div id="signup-tab-content">
		        <form class="signup-form" action="" method="post">
		          <input type="email" class="input" id="user_email" autocomplete="off" placeholder="Email" name="user_login">
		          <input type="text" class="input" id="user_name" autocomplete="off" placeholder="Nome" name="user_nome">
		          <input type="password" class="input" id="user_pass" autocomplete="off" placeholder="Senha" name="user_pass">
		          <input type="text" class="input" id="user_endereco" autocomplete="off" placeholder="Endereço" name="user_endereco"></label>
		          <input type="submit" class="button" value="Cadastrar" name="cadastrar">
		        </form><!--.login-form-->
		        <div class="help-text">
		          <p>Ao cadastrar, você concorda com o nosso <a href="#">Termos de Serviço</a></p>
		          
		        </div><!--.help-text-->
		      </div><!--.signup-tab-content-->
		    </div><!--.tabs-content-->
		  </div><!--.form-wrap-->

	</div>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
		  tab = $('.tabs h3 a');

		  tab.on('click', function(event) {
		    event.preventDefault();
		    tab.removeClass('active');
		    $(this).addClass('active');

		    tab_content = $(this).attr('href');
		    $('div[id$="tab-content"]').removeClass('active');
		    $(tab_content).addClass('active');
		  });
		});
	</script>


<?php
}
?>
<?=template_footer()?>
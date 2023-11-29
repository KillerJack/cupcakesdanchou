<?=template_header('Perfil')?>

<?php
if(isset($_POST['update']))
{
	"UPDATE users SET nome=?, email=?, endereco=? WHERE id=?";
	array($_POST['user_nome'], $_POST['user_login'], $_POST['user_endereco'], $_SESSION['userid']);
	$sql = "UPDATE users SET nome=?, email=?, endereco=? WHERE id=?";
	if(!empty($_POST['user_pass']))
	{
		$sql = "UPDATE users SET nome=?, email=?, endereco=?, senha=? WHERE id=?";
		$senha_hash = password_hash($_POST['user_pass'], PASSWORD_DEFAULT);
		$value = array($_POST['user_nome'], $_POST['user_login'], $_POST['user_endereco'], $senha_hash, $_SESSION['userid']);
	}
	else
	{
		$sql = "UPDATE users SET nome=?, email=?, endereco=? WHERE id=?";
		$value = array($_POST['user_nome'], $_POST['user_login'], $_POST['user_endereco'], $_SESSION['userid']);
	}
	// &&  && isset($_POST['user_endereco'])
	// var_dump($value);
	$stmt= $pdo->prepare($sql);
	$stmt->execute($value);
	echo '<div class="placeorder content-wrapper"><h1>CADASTRO ATUALIZADO COM SUCESSO<p>REDIRECIONANDO PARA A PÁGINA DE PERFIL <meta http-equiv="refresh" content="3;url=index.php?page=perfil"></p></h1></div>';
	die();
}


$query = "SELECT * FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['userid']]);
$red = $stmt->fetchAll();

?>

<div class="login content-wrapper">
	<div class="form-wrap" style="width: 400px;">
		<div class="tabs-content">
			<form class="perfil-form" action="" method="post">
			  <label>Email:<input type="email" class="input" id="user_email" autocomplete="off" placeholder="Email" name="user_login" value="<?=$red[0]['email']?>"></label>
			  <label>Nome: </label><input type="text" class="input" id="user_name" autocomplete="off" placeholder="Nome" name="user_nome" value="<?=$red[0]['nome']?>"></label>
			  <label>Endereço: </label><input type="text" class="input" id="user_endereco" autocomplete="off" placeholder="Endereço" name="user_endereco" value="<?=$red[0]['endereco']?>"></label>
			  <label>Senha: </label><input type="password" class="input" id="user_pass" autocomplete="off" placeholder="Senha" name="user_pass" value=""></label>
			  <input type="submit" class="button" value="Atualizar Perfil" name="update">
			</form><!--.login-form-->
		</div>
	</div>
</div>
<?php
function insert_user_token(int $user_id, string $selector, string $hashed_validator, string $expiry): bool
{
    global $pdo;
    $sql = 'INSERT INTO user_tokens(user_id, selector, hashed_validator, expiry)
            VALUES(:user_id, :selector, :hashed_validator, :expiry)';

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':selector', $selector);
    $statement->bindValue(':hashed_validator', $hashed_validator);
    $statement->bindValue(':expiry', $expiry);
    $executed = $statement->execute();
    if($executed){
        return $executed;
    }
    else{
        return ("Error ao adicionar novo registro: ");
        print_r($executed->errorInfo());
    }
    
}

function find_user_token_by_selector(string $selector)
{
    global $pdo;
    $sql = 'SELECT id, selector, hashed_validator, user_id, expiry
                FROM user_tokens
                WHERE selector = :selector AND
                    expiry >= now()
                LIMIT 1';

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':selector', $selector);

    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function delete_user_token(int $user_id): bool
{
    global $pdo;
    $sql = 'DELETE FROM user_tokens WHERE user_id = :user_id';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $user_id);

    return $statement->execute();
}
function find_user_by_token(string $token)
{
    global $pdo;
    $tokens = parse_token($token);

    if (!$tokens) {
        return null;
    }

    $sql = 'SELECT users.id, email
            FROM users
            INNER JOIN user_tokens ON user_id = users.id
            WHERE selector = :selector AND
                expiry > now()
            LIMIT 1';

    $statement = $pdo->prepare($sql);
    $statement->bindValue(':selector', $tokens[0]);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function token_is_valid(string $token): bool { // parse the token to get the selector and validator [$selector, $validator] = parse_token($token);

    $tokens = find_user_token_by_selector($token);
    if (!$tokens) {
        return false;
    }

    return password_verify($validator, $tokens['hashed_validator']);
}

function generate_tokens(): array
{
    $selector = bin2hex(random_bytes(16));
    $validator = bin2hex(random_bytes(32));

    return [$selector, $validator, $selector . ':' . $validator];
}

function parse_token(string $token): ?array
{
    $parts = explode(':', $token);

    if ($parts && count($parts) == 2) {
        return [$parts[0], $parts[1]];
    }
    return null;
}

function remember_me(int $user_id, int $day = 30)
{
    global $pdo;
    [$selector, $validator, $token] = generate_tokens();

    // remove all existing token associated with the user id
    delete_user_token($user_id);

    // set expiration date
    $expired_seconds = time() + 60 * 60 * 24 * $day;

    // insert a token to the database
    $hash_validator = password_hash($validator, PASSWORD_DEFAULT);
    $expiry = date('Y-m-d H:i:s', $expired_seconds);

    if (insert_user_token($user_id, $selector, $hash_validator, $expiry)) {
        setcookie('remember_me', $token, $expired_seconds);
    }
}

function log_user_in()
{
	if(isset($_POST['user_login']) && isset($_POST['user_pass']))
	{
		global $pdo;
		$email = $_POST['user_login'];
		$senha = $_POST['user_pass'];

		$query = "SELECT * FROM users WHERE email = ?";
		$stmt = $pdo->prepare($query);
		$stmt->execute([$email]);
		$red = $stmt->fetchAll();

		if(password_verify($senha, $red[0]['senha']))
		{
			$_SESSION["userid"] = $red[0]['id'];
			if (isset($_POST['remember_me'])) {
	            remember_me($red[0]['id']);
	        }
	        template_header('Login');
			echo '<div class="placeorder content-wrapper"><h1>Logado com sucesso. Seja bem-vindo, '.$red[0]['nome'].'</h1><p>REDIRECIONANDO PARA A PÁGINA INICIAL <meta http-equiv="refresh" content="5;url=index.php"></p> </div>';
		}
		else{
			template_header('Login');
			echo '<div class="placeorder content-wrapper"><h1>Email ou senha incorretos. <meta http-equiv="refresh" content="3;url=index.php?page=login"></h1></div>';
		}
	}
}

function is_user_logged_in(): bool
{
    // check the session
    if (isset($_SESSION['userid'])) {
        return true;
    }

    // check the remember_me in cookie
    $token = filter_input(INPUT_COOKIE, 'remember_me', FILTER_SANITIZE_STRING);

    if ($token && token_is_valid($token)) {

        $user = find_user_by_token($token);

        if ($user) {
            return log_user_in($user);
        }
    }
    return false;
}

function logout(): void
{
    if (is_user_logged_in()) {

        // delete the user token
        delete_user_token($_SESSION['userid']);

        // delete session
        unset($_SESSION['username'], $_SESSION['userid`']);

        // remove the remember_me cookie
        if (isset($_COOKIE['remember_me'])) {
            unset($_COOKIE['remember_me']);
            setcookie('remember_user', null, -1);
        }

        // remove all session data
        session_destroy();

        // redirect to the login page
		header('Location: index.php?page=login');
    }
}

function register_user()
{
	if(isset($_POST['user_login']) && isset($_POST['user_pass']) && isset($_POST['user_nome']))
	{
		global $pdo;
		$name = $_POST['user_nome'];
		// $sobrenome = $_POST['sobrenome_register'];
		$email = $_POST['user_login'];
		$senha = $_POST['user_pass'];
		$endereco = $_POST['user_endereco'];


		if(!empty($name) && !empty($email) && !empty($senha))
		{
			$senha_hashed = password_hash($senha, PASSWORD_DEFAULT);
		}

		// $sql = '';
		$stmt2 = $pdo->prepare("INSERT INTO users (id, nome, email, endereco, senha) VALUES (NULL, ?, ?, ?, ?)");
	    $executed = $stmt2->execute(array($name, $email, $endereco, $senha_hashed));
	    if($executed){
	        $_SESSION["userid"] = $pdo->lastInsertId();
			template_header('Cadastro');
			echo '<div class="placeorder content-wrapper"><h1>CADASTRADO COM SUCESSO<p>REDIRECIONANDO PARA A PÁGINA INICIAL <meta http-equiv="refresh" content="5;url=index.php"></p></h1></div>';

	    }
	    else{
	    	template_header('Erro');
	        echo '<div class="placeorder content-wrapper"><h1>ERRO AO ADICIONAR NOVO CADASTRO<p>REDIRECIONANDO PARA A PÁGINA DE LOGIN <meta http-equiv="refresh" content="3;url=index.php?page=login"></p></h1></div>';
	        // var_dump($executed);
	        // print_r($executed->errorInfo());
	    }
	}
}

?>
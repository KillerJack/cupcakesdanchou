<?php
function pdo_connect_mysql() {
    // Update the details below with your MySQL details
    $DATABASE_HOST = 'mysql0.cupcakesdanchou.store';
    $DATABASE_USER = 'cupcakesdanchou1';
    $DATABASE_PASS = 'nbsaDeMCRIRGXGO3!';
    $DATABASE_NAME = 'cupcakesdanchou1';
    try {
        return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $exception) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to database!');
    }
}
// 

// Template header, feel free to customize this
function template_header($title) {
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;    
echo <<<EOT
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <title>$title</title>
        <link href="style.css?33" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    </head>
    <body>
        <header>
            <div class="content-wrapper">
                <h1><img src="https://i1.wp.com/www.sweetmonday.org/wp-content/uploads/2017/02/cropped-SM-cupcake-icon-transparent-small2.png?ssl=1" width="30px" height="30px" /> Cupcakes Danchou</h1>
                <nav>
                    <a href="index.php">Início</a>
                    <a href="index.php?page=products">Cardápio</a>
EOT;
                    login();
echo <<<EOT
                </nav>
                <div class="link-icons">
                <form class="seach-form" action="index.php?page=result" method="post">
                    <input type="text" class="input" id="user_search" autocomplete="on" placeholder="Pesquisar" name="user_search">
                    <input type="image" src="https://cdn-icons-png.flaticon.com/512/5948/5948534.png" class="inputimage" alt="Pesquisar" name="pesquisar" width="20px" height='20px'>
                </form>
                    <a href="index.php?page=cart">
            <i class="fas fa-shopping-cart"></i><span>$num_items_in_cart</span>
            </a>
                </div>
            </div>
        </header>
        <main>
EOT;
}


// Template footer
function template_footer() {
$year = date('Y');
echo <<<EOT
        </main>
        <footer>
            <div class="content-wrapper">
                <p>&copy; $year, Cruzeiro do Sul, RGM 23191856</p>
            </div>
        </footer>
    </body>
</html>
EOT;
}

function login()
{
	global $logado;
    if(!$logado)
    {
        echo '<a href="index.php?page=login">Login</a>';
    }
    else
    {
    	echo '<a href="index.php?page=perfil">Perfil</a>';
    	echo '<a href="index.php?page=login&logout=true">Logout</a>';
    }
}
?>
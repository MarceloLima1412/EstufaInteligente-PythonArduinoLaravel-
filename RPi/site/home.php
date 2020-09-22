<?php
include "config.php";

// Check user login or not
if(!isset($_SESSION['uname'])){
    header('Location: index.php');
}

// logout
if(isset($_POST['but_logout'])){
    session_destroy();
    header('Location: index.php');
}
?>
<!doctype html>
<html>
    <head></head>
    <body>
        <h1>Estufa A - Server local</h1>
        <link href="style.css" rel="stylesheet" type="text/css">
        <form method='post' action="">
        	
            <ul>
                <li>
                    <a href="https://estufas.000webhostapp.com/">Página WEB</a>
                </li>   
            </ul>
            <ul>
                <li>
                    <a href="https://www.000webhost.com/">Configurações Página Web</a>
                </li>   
            </ul>
            <ul>
                <li>
                    <a href="/phpmyadmin/index.php">PHP MyAdmin</a>
                </li>   
            </ul>
            <br><br>
            <div id="logout">
            	<input type="submit" value="Logout" name="but_logout" id="">
            	<br><br>
        	</div>
        </form>
    </body>
</html>
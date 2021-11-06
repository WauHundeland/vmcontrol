<?php

/* 
 * Copyright (C) 2021 WauHundeland
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();
require 'conf.php';

if (isset($_GET['login'])) {
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $result = $statement->execute(array('email' => $email));
    $user = $statement->fetch();

    //Überprüfung des Passworts
    if ($user !== false && password_verify($passwort, $user['passwort'])) {
        $_SESSION['userid'] = $user['id'];
        die('Login erfolgreich. Weiter zu <a href="index.php">internen Bereich</a>');
    } else {
        $errorMessage = "E-Mail oder Passwort war ungültig<br>";
    }
}
?>
<!DOCTYPE html> 
<html> 
    <head>
        <title>Login</title>    
    </head> 
    <body>

        <?php
        if (isset($errorMessage)) {
            echo $errorMessage;
        }
        ?>

        <form action="?login=1" method="post">
            E-Mail:<br>
            <input type="email" size="40" maxlength="250" name="email"><br><br>

            Dein Passwort:<br>
            <input type="password" size="40"  maxlength="250" name="passwort"><br>

            <input type="submit" value="Abschicken">
        </form> 
    </body>
</html>
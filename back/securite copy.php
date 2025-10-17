<?php
// Eviter les injection de code via le formulaire connexion
if(isset($_POST['submit'])){
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
}
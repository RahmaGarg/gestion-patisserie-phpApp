<?php
session_start();

// Détruire toutes les variables de session.
$_SESSION = array();

// Détruire la session elle-même.
session_destroy();

// Rediriger l'utilisateur vers la page d'accueil (index.php).
header("Location: index.php");
exit();
?>

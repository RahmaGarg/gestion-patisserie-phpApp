<?php
session_start();
$host = "localhost";
$base = "magasin";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$base", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = "SELECT * FROM user WHERE username = :username AND password = :password";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            // L'utilisateur est authentifié avec succès
            $_SESSION['utilisateur'] = $username;

            // Nettoyer les cookies existants lors de la connexion
            setcookie('remember_user', '', time() - 3600, '/');
            setcookie('remember_password', '', time() - 3600, '/');

            if (isset($_POST['rememberme'])) {
                // Si l'utilisateur a coché "Se souvenir de moi", créez des cookies pour le nom d'utilisateur et le mot de passe
                $cookie_name_user = 'remember_user';
                $cookie_name_password = 'remember_password';
                $cookie_value = $username;
                setcookie($cookie_name_user, $cookie_value, time() + 3600 * 24, '/'); // Cookie valable pendant une semaine
                setcookie($cookie_name_password, $password, time() + 3600 * 24, '/'); // Cookie valable pendant une semaine
            }

            header('Location: ../../crudProd.php'); // Redirige l'utilisateur vers la page de gestion des produits
            exit();
        }
    }

    // Vérifiez si la session existe et si l'utilisateur est authentifié.
    if (isset($_SESSION['utilisateur'])) {
        header("Location: ../../crudProd.php");
        exit();
    }

    // Vérifiez si les cookies "remember_user" et "remember_password" existent
    if (isset($_COOKIE['remember_user']) && isset($_COOKIE['remember_password'])) {
        $cookie_username = $_COOKIE['remember_user'];
        $cookie_password = $_COOKIE['remember_password'];
    } else {
        $cookie_username = ''; // Valeur par défaut
        $cookie_password = ''; // Valeur par défaut
    }
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

?>

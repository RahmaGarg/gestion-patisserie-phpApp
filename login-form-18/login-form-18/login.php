<?php
// Incluez votre code de connexion à la base de données ici
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "magasin";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération du nom d'utilisateur et du mot de passe du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validation par rapport à votre base de données
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Après avoir validé le nom d'utilisateur et le mot de passe par rapport à la base de données
    if ($result->num_rows === 1) {
        // Démarrage de la session
        session_start();

        // Configuration des variables de session
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        // Vérification si l'utilisateur a coché "Se souvenir de moi"
        if (isset($_POST['rememberme'])) {
            // Chiffrer les données sensibles avant de les stocker dans les cookies
            $encrypted_username = base64_encode($username); // Vous pouvez utiliser d'autres méthodes de chiffrement
            $encrypted_password = base64_encode($password); // Assurez-vous de stocker de manière sécurisée !

            // Configuration des cookies pour se souvenir de l'utilisateur
            setcookie('remember_user', $encrypted_username, time() + 86400 * 30);
            setcookie('remember_password', $encrypted_password, time() + 86400 * 30);
        } else {
            // Si l'utilisateur ne souhaite pas être retenu, supprimez les cookies existants s'ils existent
            if (isset($_COOKIE['remember_user'])) {
                setcookie('remember_user', '', time() - 3600); // expire le cookie
            }
            if (isset($_COOKIE['remember_password'])) {
                setcookie('remember_password', '', time() - 3600); // expire le cookie
            }
        }

        // Redirection vers la page principale si le nom d'utilisateur et le mot de passe sont valides
        header("Location: ../../crudprod.php");
        exit();
    } else {
        // Affichage d'une alerte si le nom d'utilisateur et le mot de passe ne sont pas valides
        echo "<script>alert('Nom d'utilisateur ou mot de passe invalide'); window.location.href='index.php';</script>";
    }
}

$conn->close();
?>

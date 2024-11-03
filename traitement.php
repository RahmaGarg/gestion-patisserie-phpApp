<?php
session_start();

// Vérifiez si la session existe et si l'utilisateur est authentifié.
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    header("Location: crudProd.php");
    exit();
}

$utilisateurs = array(
    array("username" => "rahma", "password" => "123"),
    array("username" => "ahmed", "password" => "456"),
    array("username" => "mohamad", "password" => "789"),
    array("username" => "safa", "password" => "111"),
    array("username" => "ayman", "password" => "554")
);

if (isset($_POST['username']) && isset($_POST['password'])) {
    $saisiUsername = $_POST['username'];
    $saisiPassword = $_POST['password'];
    $loginSuccess = false;  // Pour vérifier si la connexion a réussi.

    foreach ($utilisateurs as $utilisateur) {
        if ($utilisateur['username'] === $saisiUsername && $utilisateur['password'] === $saisiPassword) {
            // Si les identifiants sont corrects, définissez les variables de session.
            $_SESSION['username'] = $saisiUsername;
            $_SESSION['password'] = $saisiPassword;

            if (isset($_POST["rememberme"]) && $_POST["rememberme"] == "oui") {
                // Si la case "Se souvenir de moi" est cochée, enregistrez l'utilisateur
                // dans un cookie ou une session pour une connexion automatique ultérieure.
                // Assurez-vous de prendre des mesures de sécurité appropriées.
                // Par exemple, ne stockez pas le mot de passe en clair.
                // N'utilisez pas le code ci-dessous en production sans renforcer la sécurité.
        
                setcookie("nom_utilisateur", $nom_utilisateur, time() + 3600 * 24 * 30, "/");
                setcookie("mot_de_passe", $mot_de_passe, time() + 3600 * 24 * 30, "/");
        
                echo "Vous êtes connecté et nous nous souviendrons de vous.";
            } else {
                echo "Vous êtes connecté, mais nous ne nous souviendrons pas de vous.";
            }
        }
        
        }
        
            $loginSuccess = true;
            header("Location: ../../crudProd.php");
            exit();
        }
    }

    // Si la boucle n'a pas réussi, affichez une alerte.
    if (!$loginSuccess) {
        echo '<script>alert("Identifiant ou mot de passe incorrect.");</script>';
    }
}
?>

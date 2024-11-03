<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username']) && isset($_SESSION['password'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "magasin";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Récupérer le code de catégorie en fonction de la catégorie sélectionnée
    $selected_category = $_POST['categorie'];

    $stmt_get_category_code = $conn->prepare("SELECT code FROM categorie WHERE nom = ?");
    $stmt_get_category_code->bind_param("s", $selected_category);
    $stmt_get_category_code->execute();
    $result_category_code = $stmt_get_category_code->get_result();

    if ($result_category_code->num_rows > 0) {
        $row = $result_category_code->fetch_assoc();
        $category_code = $row['code'];

        // Récupérer les données du formulaire
        $désigniation = $_POST['désigniation']; // Corrigez le nom ici
        $prix = $_POST['prix'];
        $qté = $_POST['qté'];

        // Gestion de l'image téléchargée
        $targetDir = "inc/images/"; // Dossier cible pour enregistrer les images
        $fileName = $_FILES["fileToUpload"]["name"]; // Nom du fichier téléchargé
        $targetFile = $targetDir . basename($fileName); // Chemin complet du fichier téléchargé

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
            // Enregistrez le nom du fichier dans la base de données
            $imagePath = $fileName;

            // Préparation de la requête d'insertion
            $stmt_insert_product = $conn->prepare("INSERT INTO produit (désigniation, code_categorie, prix, qté, image) VALUES (?, ?, ?, ?, ?)");
            $stmt_insert_product->bind_param("sssss", $désigniation, $category_code, $prix, $qté, $imagePath);

            if ($stmt_insert_product->execute()) {
                header("Location: crudprod.php");
                exit();
            } else {
                echo "Erreur lors de l'ajout du produit : " . $stmt_insert_product->error;
            }

            $stmt_insert_product->close();
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    } else {
        echo "Aucun code de catégorie correspondant trouvé pour la catégorie sélectionnée.";
    }

    $stmt_get_category_code->close();
    $conn->close();
} else {
    header("Location: /tp/login-form-18/login-form-18/index.php");
    exit();
}
?>

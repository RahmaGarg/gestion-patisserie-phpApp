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

    $product_id = $_POST['code']; // Récupérer l'ID du produit à mettre à jour depuis le formulaire

    // Récupérer les nouvelles données du formulaire
    $new_designation = $_POST['désigniation'];
    $new_category = $_POST['code_categorie'];
    $new_price = $_POST['prix'];
    $new_quantity = $_POST['qté'];

    // Gérer la mise à jour de l'image si une nouvelle image est téléchargée
    if ($_FILES["fileToUpload"]["size"] > 0) {
        $targetDir = "inc/images/";
        $fileName = $_FILES["fileToUpload"]["name"];
        $targetFile = $targetDir . basename($fileName);

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
            $new_imagePath = $fileName;

            // Effectuer la mise à jour avec la nouvelle image
            $stmt_update_product = $conn->prepare("UPDATE produit SET désigniation = ?, code_categorie = ?, prix = ?, qté = ?, image = ? WHERE code = ?");
            $stmt_update_product->bind_param("sssssi", $new_designation, $new_category, $new_price, $new_quantity, $new_imagePath, $product_id);
        } else {
            echo "Erreur lors du téléchargement de la nouvelle image.";
            exit();
        }
    } else {
        // Mise à jour sans changer l'image
        $stmt_update_product = $conn->prepare("UPDATE produit SET désigniation = ?, code_categorie = ?, prix = ?, qté = ? WHERE code = ?");
        $stmt_update_product->bind_param("ssssi", $new_designation, $new_category, $new_price, $new_quantity, $product_id);
    }

    if ($stmt_update_product->execute()) {
        header("Location: crudprod.php");
        exit();
    } else {
        echo "Erreur lors de la mise à jour du produit : " . $stmt_update_product->error;
    }

    $stmt_update_product->close();
    $conn->close();
} else {
    header("Location: /tp/login-form-18/login-form-18/index.php");
    exit();
}
?>

<?php
$host = 'localhost';
$dbname = 'magasin';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productCode = $_POST['code'];
        $designation = $_POST['désigniation'];
        $Qte = $_POST['qté'];
        $prix = $_POST['prix'];
        $code_categorie = $_POST['code_categorie'];

        // Utilisez un chemin relatif pour le répertoire cible
        $targetDir = "images/";

        // Vérifiez si un nouveau fichier image est fourni
        if (!empty($_FILES["image"]["name"])) {
            // Si oui, préparez le chemin du fichier cible
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);

            // Vérifiez si le répertoire existe, sinon, créez-le
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Déplacez le nouveau fichier image
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

            // Mettez à jour les détails du produit avec le nouveau chemin relatif de l'image
            $updateQuery = "UPDATE produit
                            SET désigniation = :désigniation, qté = :qté, prix = :prix, code_categorie = :code_categorie, image = :image
                            WHERE code = :code";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':code', $productCode, PDO::PARAM_STR);
            $updateStmt->bindParam(':désigniation', $désigniation, PDO::PARAM_STR);
            $updateStmt->bindParam(':qté', $qté, PDO::PARAM_INT);
            $updateStmt->bindParam(':prix', $prix, PDO::PARAM_STR);
            $updateStmt->bindParam(':code_categorie', $code_categorie, PDO::PARAM_INT);
            $updateStmt->bindParam(':image', $targetFile, PDO::PARAM_STR);

            $updateStmt->execute();
        } else {
            // Si aucun nouveau fichier image n'est fourni, mettez à jour les autres détails du produit sans changer l'image
            $updateQuery = "UPDATE produit 
                            SET désigniation = :désigniation, qté = :qté, prix = :prix, code_categorie = :code_categorie
                            WHERE code = :code";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->bindParam(':code', $productCode, PDO::PARAM_STR);
            $updateStmt->bindParam(':désigniation', $designation, PDO::PARAM_STR);
            $updateStmt->bindParam(':qté', $Qte, PDO::PARAM_INT);
            $updateStmt->bindParam(':prix', $prix, PDO::PARAM_STR);
            $updateStmt->bindParam(':code_categorie', $code_categorie, PDO::PARAM_INT);

            $updateStmt->execute();
        }

        // Rediriger vers la liste des produits après la mise à jour
        header('Location: crudProd.php');
        exit();
    } else {
        echo 'Invalid request method.';
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>



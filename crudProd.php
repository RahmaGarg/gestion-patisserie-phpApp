<?php
session_start();

// Vérifier si les variables de session username et password ne sont pas définies
if (!isset($_SESSION['username']) && !isset($_SESSION['password'])) {
    // Rediriger vers la page de connexion si les variables de session ne sont pas définies
    echo '<script>alert("Vous devez vous connecter.");</script>';
    echo '<script>window.location.href = "/tp/login-form-18/login-form-18/index.php";</script>';
    exit();
}

// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "magasin";

// Créer une connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Suppression du produit si le paramètre delete_code est présent dans l'URL
if (isset($_GET['delete_code'])) {
    try {
        // Utiliser PDO pour se connecter à la base de données
        $host = "localhost";
        $dbname = "magasin";
        $username = "root";
        $password = "";

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer le code du produit à supprimer depuis l'URL
        $productCodeToDelete = $_GET['delete_code'];

        // Préparer et exécuter la requête de suppression du produit
        $deleteQuery = "DELETE FROM produit WHERE code = :code";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindParam(':code', $productCodeToDelete, PDO::PARAM_INT);
        $deleteStmt->execute();

        // Rediriger vers crudprod.php après la suppression
        header('Location: crudprod.php');
        exit(); // Assurez-vous de terminer le script après la redirection
    } catch (PDOException $e) {
        // Gérer les erreurs éventuelles de la connexion ou de la suppression du produit
        echo "Erreur : " . $e->getMessage();
    }
}

// Récupération des données depuis la base de données pour l'affichage
$stmt = $conn->prepare("SELECT p.désigniation, c.nom AS catégorie, p.prix, p.qté, p.image, p.code FROM produit p INNER JOIN categorie c ON p.code_categorie = c.code");
$stmt->execute();
$result = $stmt->get_result();

// Gestion des erreurs
if ($result === false) {
    die("Erreur dans la requête SQL : " . $conn->error);
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Table</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body>
    <?php include 'inc/header.php'; ?>
    <main>
        <div class="container-xl">
            <h2 style="color: #F78787; margin-bottom: 20px;">Produits disponibles chez nous :</h2>
        </div>
        <div class="container">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- This div will be used to align buttons to the right -->
                    </div>
                    <div class="col-sm-6 text-right" style="margin-bottom: 20px;">
                        <!-- Move the buttons here to align to the right -->
                        <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Product</span></a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover custom-table">
                <thead>
                    <tr>
                        <th style="width: 100px;" class="text-center">Désignation</th>
                        <th style="width: 70px;" class="text-center">Catégorie</th>
                        <th style="width: 70px;" class="text-center">Prix</th>
                        <th style="width: 70px;" class="text-center">Qté</th>
                        <th style="width: 100px;" class="text-center">Image</th>
                        <th style="width: 70px;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td class="text-center">' . $row['désigniation'] . '</td>';
        echo '<td class="text-center">' . $row['catégorie'] . '</td>';
        echo '<td class="text-center">' . $row['prix'] . '</td>';
        echo '<td class="text-center">' . $row['qté'] . '</td>';
        echo '<td class="text-center"><img src="inc/images/' . $row['image'] . '" alt="' . $row['désigniation'] . '" class="img-fluid"></td>';
        echo '<td class="text-center">';
        echo '<a href="edit_product.php?product_id=' . $row['code'] . '"><i class="material-icons" style="color: black;" data-toggle="tooltip" title="Edit">&#xE254;</i></a>';
        echo '<a href="crudProd.php?delete_code=' . $row['code'] . '" class="delete-product" data-toggle="modal" data-target="#deleteEmployeeModal" data-id="' . $row['code'] . '"><i class="material-icons" style="color: black;" data-toggle="tooltip" title="Delete">&#xE872;</i></a>';
        echo '</td>';
        echo '</tr>';
    }
}

 else {
    echo '<tr><td colspan="6" class="text-center">Aucun produit trouvé</td></tr>';
}
?>

                </tbody>
            </table>
        </div>
<!-- Delete Modal HTML -->
<div id="deleteEmployeeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="delete.php" method="post"> <!-- Utilisation du fichier delete.php pour la suppression -->
                <div class="modal-header">                        
                    <h4 class="modal-title">Supprimer le produit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">                    
                    <input type="hidden" id="delete_code" name="delete_code">
                    <p>Êtes-vous sûr de vouloir supprimer ce produit ?</p>                  
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Annuler">
                    <input type="submit" class="btn btn-danger" value="Supprimer">
                </div>
            </form>
        </div>
    </div>
</div>
<?php
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories from the database
$stmt_categories = $conn->prepare("SELECT nom FROM categorie");
$stmt_categories->execute();
$result_categories = $stmt_categories->get_result();

// Store the categories in an array
$categories = [];
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
    }
}

$stmt_categories->close();
// ...
?>
<div id="addEmployeeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="add.php" method="post" enctype="multipart/form-data">
                            <div class="modal-header">                        
                    <h4 class="modal-title">Ajouter un produit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">                    
                    <div class="form-group">
                        <label>désigniation</label>
                        <input type="text" class="form-control" name="désigniation" required>
                    </div>
                    <div class="form-group">
    <label>Catégorie</label>
    <select class="form-control" name="categorie" required>
        <option value="" disabled selected>-- Sélectionner une catégorie --</option>
        <?php foreach ($categories as $category) : ?>
            <option value="<?php echo $category['nom']; ?>"><?php echo $category['nom']; ?></option>
        <?php endforeach; ?>
    </select>
</div>
                    <div class="form-group">
                        <label>prix</label>
                        <textarea class="form-control" name="prix" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>qté</label>
                        <input type="text" class="form-control" name="qté" required>
                    </div>     
                    <div class="form-group">
    <label>Image</label>
    <input type="file" class="form-control" name="fileToUpload" required>
</div>
                   
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Annuler">
                    <input type="submit" class="btn btn-success" value="Ajouter" style="background-color: #f78787;">
                </div>
            </form>
        </div>
    </div>
</div>

    </main>

    <?php include 'inc/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
$(document).ready(function() {
    $('.delete-product').click(function(e) {
        e.preventDefault();
        var productID = $(this).data('id');
        var deleteURL = $(this).attr('href');
        $('#delete_code').val(productID);
        $('#deleteEmployeeModal form').attr('action', deleteURL);
        $('#deleteEmployeeModal').modal('show'); // Affichage de la modal de suppression
    });

    // Rediriger après suppression
    $('#deleteEmployeeModal form').submit(function() {
        window.location.href = 'crudProd.php'; // Redirection vers crudProd.php après la suppression
    });
});
</script>



</body>
</html>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>

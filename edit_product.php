<?php require('inc/header.php'); ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<main>
    <div class="container">
        <h1 class="my-4">Modifier le produit</h1>

        <?php
        $host = 'localhost';
        $dbname = 'magasin';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_GET['product_id'])) {
                $productCode = $_GET['product_id'];

                $query = "SELECT produit.*, categorie.nom 
                          FROM produit
                          JOIN categorie ON produit.code_categorie = categorie.code
                          WHERE produit.code = :code";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':code', $productCode, PDO::PARAM_STR);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    $categoriesQuery = "SELECT code, nom FROM categorie";
                    $categoriesStmt = $pdo->query($categoriesQuery);
                    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <form action="update.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="code" value="<?php echo $productCode; ?>">

                        <div class="form-group">
                            <label>Désignation</label>
                            <input type="text" class="form-control" id="edit_nom" name="désigniation" value="<?php echo $product['désigniation']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Catégorie</label>
                            <select class="form-control" name="code_categorie" required>
                                <option value="" disabled>-- Sélectionner la nouvelle catégorie --</option>
                                <?php
                                foreach ($categories as $category) {
                                    $selected = ($category['code'] == $product['code_categorie']) ? 'selected' : '';
                                    echo '<option value="' . $category['code'] . '" ' . $selected . '>' . $category['nom'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Prix</label>
                            <input type="number" class="form-control" id="edit_prix" name="prix" value="<?php echo $product['prix']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Qté</label>
                            <input type="number" class="form-control" id="edit_qté" name="qté" value="<?php echo $product['qté']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Image Actuelle:</label>
                            <img src="inc/images/<?php echo $product['image']; ?>" alt="Description de l'image" style="width: 10%; height: 10%;">
                        </div>

                        <div class="form-group">
                            <label>Image (Laissez vide pour conserver l'image actuelle)</label>
                            <input type="file" class="form-control" name="fileToUpload">
                        </div>

                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Annuler">
                            <input type="submit" class="btn btn-info" name="save" value="Sauvegarder" style="background-color: #f78787;">
                        </div>
                    </form>
                      
                    <?php
                } else {
                    echo '<p>Produit non trouvé.</p>';
                }
            } else {
                echo '<p>Code produit non fourni.</p>';
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        ?>
    </div>
</main>

<?php require('inc/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Ajoutez d'autres scripts nécessaires ici -->

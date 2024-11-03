
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .table img {
            max-width: 100px; /* Largeur maximale de l'image */
            max-height: 100px; /* Hauteur maximale de l'image */
        }
    </style>
</head>
<body>
    <?php include 'inc/header.php'; ?>
    <main>
        <div class="container-xl">
        <h2 style="color: #F78787; margin-bottom: 20px;">Produits disponibles :</h2>
            <table class="table table-striped table-hover custom-table">
                <thead>
                    <tr>
                        <th style="width: 100px;" class="text-center">Nom</th>
                        <th style="width: 80px;" class="text-center">Prix</th>
                        <th style="width: 150px;" class="text-center">Ingrédients</th>
                        <th style="width: 100px;" class="text-center">Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $products = array(
                        array('id' => 1, 'name' => 'Gâteau au chocolat', 'price' => 19.99, 'ingredients' => 'Chocolat, Farine, Sucre, Œufs', 'image' => 'inc/images/image1.jpg'),
                        array('id' => 2, 'name' => 'Tarte aux fraises', 'price' => 15.99, 'ingredients' => 'Fraises, Pâte feuilletée, Sucre glace', 'image' => 'inc/images/image2.jpg'),
                        array('id' => 3, 'name' => 'Cupcakes à la vanille', 'price' => 12.49, 'ingredients' => 'Vanille, Farine, Beurre, Sucre', 'image' => 'inc/images/image3.jpg'),
                        array('id' => 4, 'name' => 'Cheesecake aux baies rouges', 'price' => 22.99, 'ingredients' => 'Fromage à la crème, Baies, Biscuits Graham', 'image' => 'inc/images/image4.jpg'),
                        array('id' => 5, 'name' => 'Éclair au café', 'price' => 8.99, 'ingredients' => 'Café, Pâte à choux, Crème pâtissière', 'image' => 'inc/images/image5.jpg'),
                    );

                    foreach ($products as $product) {
                        echo '<tr>';
                        echo '<td class="text-center">' . $product['name'] . '</td>';
                        echo '<td class="text-center">' . $product['price'] . '</td>';
                        echo '<td class="text-center">' . $product['ingredients'] . '</td>';
                        echo '<td class="text-center"><img src="' . $product['image'] . '" alt="' . $product['name'] . '"></td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include 'inc/footer.php'; ?>

    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php

require_once __DIR__ . '/../src/Models/Database.php';
require_once __DIR__ . '/../src/Models/WishlistModel.php';
require_once __DIR__ . '/../src/Controllers/WishlistController.php';

use App\Controllers\WishlistController;

// On nettoie $_POST
$_POST = [];

// On simule ton templateEngine par null
$controller = new WishlistController(null);

echo "=== Ajout de 2 offres pour user #42 ===\n";
$_POST['ID_USER'] = 42;
$_POST['ID_OFFER'] = 101;
$controller->addOfferToWishlist();

$_POST['ID_OFFER'] = 102;
$controller->addOfferToWishlist();

echo "=== Lecture de la wishlist #42 ===\n";
print_r($controller->getWishlist(42));

echo "\n=== Suppression d'une offre (102) ===\n";
$_POST['ID_USER'] = 42;
$_POST['ID_OFFER'] = 102;
$controller->removeOfferFromWishlist();

echo "=== Lecture finale #42 ===\n";
print_r($controller->getWishlist(42));

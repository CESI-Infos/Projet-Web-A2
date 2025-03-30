<?php

require_once 'src/Models/WishListModel.php';
require_once 'src/Controllers/WishListController.php';

try {
    $file = __DIR__ . '/storage/wishlist.json'; 
    $wishModel = new WishListModel($file);

    $wishController = new WishListController($wishModel);

    echo "=== TEST ADD OFFER TO WISHLIST ===\n";
    $wishController->addOfferToWishlist(4, 101);
    $wishController->addOfferToWishlist(4, 102);
    $wishController->addOfferToWishlist(5, 200);
    echo "-> Ajout effectué\n";

    echo "\n=== TEST GET WISHLIST FOR USER 4 ===\n";
    $wishlist4 = $wishController->getWishlist(4);
    print_r($wishlist4);

    echo "\n=== TEST REMOVE OFFER (101) FOR USER 4 ===\n";
    $wishController->removeOfferFromWishlist(4, 101);

    $wishlist4 = $wishController->getWishlist(4);
    print_r($wishlist4);

} catch (Exception $ex) {
    echo "Erreur générale : " . $ex->getMessage();
}

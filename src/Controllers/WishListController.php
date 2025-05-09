<?php
namespace App\Controllers;

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/WishlistModel.php';

use App\Controllers\Controller;
use App\Models\WishlistModel;

class WishlistController extends Controller
{
    public function __construct($templateEngine)
    {
        $this->model = new WishlistModel();
        $this->templateEngine = $templateEngine;
    }
    // Add an offer to the wishlist
    public function addOfferToWishlist($idUser)
    {
        $offerId = (int)$_POST['ID_OFFER'];

        $existingOffer = $this->model->getOfferInWishlist($idUser, $offerId);
        if ($existingOffer) {
            header('Location:/');
            exit;
        }

        $this->model->addOfferToWishlist($idUser, $offerId);

        header("Location: /?success=Offre ajoutée à votre wishlist !");
        exit;
    }
    // Remove an offer from the wishlist
    public function removeOfferFromWishlist()
    {
        $userId = (int)$_POST['ID_USER'];
        $offerId = (int)$_POST['ID_OFFER'];
        $this->model->removeOfferFromWishlist($userId, $offerId);

        header('Location: /');
        exit;
    }
    // Retrieve all offers from a user's wishlist
    public function getWishlist($userId)
    {
        return $this->model->getWishlist((int)$userId);
    }

    public function getOfferInWishlist($userId, $offerId)
    {
        return $this->model->getOfferInWishlist((int)$userId, (int)$offerId);
    }
}

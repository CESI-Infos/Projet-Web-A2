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

    public function addOfferToWishlist()
    {
        $userId = (int)$_POST['ID_USER'];
        $offerId = (int)$_POST['ID_OFFER'];

        $existingOffer = $this->model->getOfferInWishlist($userId, $offerId);
        if ($existingOffer) {
            header("Location: /?success=Offre ajoutée à votre wishlist !");
            exit;
        }

        $this->model->addOfferToWishlist($userId, $offerId);

        header("Location: ?uri=/connection&success=Offre ajoutée avec succès !");
        exit;
    }

    public function removeOfferFromWishlist()
    {
        $userId = (int)$_POST['ID_USER'];
        $offerId = (int)$_POST['ID_OFFER'];
        $this->model->removeOfferFromWishlist($userId, $offerId);

        header('Location: /');
        exit;
    }

    public function getWishlist($userId)
    {
        return $this->model->getWishlist((int)$userId);
    }

    public function getOfferInWishlist($userId, $offerId)
    {
        return $this->model->getOfferInWishlist((int)$userId, (int)$offerId);
    }
}

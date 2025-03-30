<?php

class WishListController
{
    private $wishListModel;

    public function __construct(WishListModel $wishListModel)
    {
        $this->wishListModel = $wishListModel;
    }

    public function addOfferToWishlist(int $userId, int $offerId): void
    {
        $this->wishListModel->addOfferToWishlist($userId, $offerId);
    }

    public function removeOfferFromWishlist(int $userId, int $offerId): void
    {
        $this->wishListModel->removeOfferFromWishlist($userId, $offerId);
    }
    
    public function getWishlist(int $userId): array
    {
        return $this->wishListModel->getWishlist($userId);
    }
}

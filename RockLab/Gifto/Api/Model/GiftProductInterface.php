<?php

namespace RockLab\Gifto\Api\Model;

/**
 * Interface GiftProductInterface
 * @package RockLab\Gifto\Api\Model
 */
interface GiftProductInterface
{
    const TABLE_NAME                = 'gift_products';
    const ID_FIELD                  = 'id';
    const MAIN_PRODUCTS             = 'mainProduct';
    const IDS_MAIN_PRODUCTS         = 'idsMainProduct';
    const IDS_BONUS_PRODUCTS        = 'idsGiftProduct';
    const QTY                       = 'qty';
    const BONUS_PRODUCTS            = 'giftProduct';

    /**
     * @param $mainProd
     * @return mixed
     */
    public function setMainProduct($mainProd);

    /**
     * @param $qty
     * @return mixed
     */
    public function setQty($qty);

    /**
     * @param $bonusProducts
     * @return mixed
     */
    public function setBonusProducts($bonusProducts);
    /**
     * @return string
     */
    public function getMainProduct();

    /**
     * @return string
     */
    public function getBonusProducts();
    /**
     * @return int
     */
    public function getQty();
    /**
     * @param $Ids
     * @return mixed
     */
    public function setIdsMainProduct($Ids);
    /**
     * @return string
     */
    public function getIdsMainProduct();

    /**
     * @param $ids
     * @return mixed
     */
    public function setIdsBonusProduct($ids);

    /**
     * @return string
     */
    public function getIdsBonusProduct();

}

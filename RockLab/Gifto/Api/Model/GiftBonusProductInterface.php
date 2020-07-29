<?php

namespace RockLab\Gifto\Api\Model;

/**
 * Interface GiftBonusProductInterface
 * @package RockLab\Gifto\Api\Model
 */
interface GiftBonusProductInterface
{
    const TABLE_NAME                = 'gift_id_bonus_product_connection';
    const GIFT_ID                   = 'gift_id';
    const BONUS_PRODUCT_ID           = 'bonus_product_id';

    /**
     * @param $giftId
     * @return mixed
     */
    public function setGift_id ($giftId);

    /**
     * @return int
     */
    public function getGift_id ();

    /**
     * @param $id
     * @return mixed
     */
    public function setBonusProductId($id);

    /**
     * @return int
     */
    public function getBonusProductId();
}

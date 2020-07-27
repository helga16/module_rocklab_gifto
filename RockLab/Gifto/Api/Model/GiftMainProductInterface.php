<?php

namespace RockLab\Gifto\Api\Model;

/**
 * Interface GiftMainProductInterface
 * @package RockLab\Gifto\Api\Model
 */
interface GiftMainProductInterface
{
    const TABLE_NAME                = 'gift_product_connection';
    const GIFT_ID                   = 'gift_id';
    const MAIN_PRODUCT_ID           = 'main_product_id';

    /**
     * @param $giftId
     * @return mixed
     */
    public function setGiftId ($giftId);

    /**
     * @return int
     */
    public function getGiftId ();

    /**
     * @param $id
     * @return mixed
     */
    public function setMainProductId($id);

    /**
     * @return int
     */
    public function getMainProductId();
}

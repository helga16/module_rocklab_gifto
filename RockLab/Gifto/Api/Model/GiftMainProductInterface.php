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

    /**
     * @param $giftId
     * @return mixed
     */
    public function setGift_id ($giftId);

    /**
     * @return int
     */
    public function getGift_id ();

}

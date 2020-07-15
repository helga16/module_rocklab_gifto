<?php

namespace RockLab\Gifto\Model;

use Magento\Framework\Model\AbstractModel;
use RockLab\Gifto\Api\Model\GiftMainProductInterface;
use RockLab\Gifto\Model\ResourceModel\GiftMainProduct as ResourceModel;

/**
 * Class GiftProduct
 * @package RockLab\Gifto\Model
 */
class GiftMainProduct extends AbstractModel implements GiftMainProductInterface
{
    /**
     * Initialize model
     */
    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @param $giftId
     * @return mixed
     */
    public function setGift_id($giftId)
    {
        $this->setData(self::GIFT_ID,$giftId);
    }

    /**
     * @return int
     */
    public function getGift_id()
    {
       return $this->getData(self::GIFT_ID);
    }
}

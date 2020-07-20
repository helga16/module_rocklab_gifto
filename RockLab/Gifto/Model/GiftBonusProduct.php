<?php

namespace RockLab\Gifto\Model;

use Magento\Framework\Model\AbstractModel;
use RockLab\Gifto\Api\Model\GiftBonusProductInterface;
use RockLab\Gifto\Model\ResourceModel\GiftBonusProduct as ResourceModel;

/**
 * Class GiftBonusProduct
 * @package RockLab\Gifto\Model
 */
class GiftBonusProduct extends AbstractModel implements GiftBonusProductInterface
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

    /**
     * @param $id
     * @return mixed
     */
    public function setBonusProductId($id)
    {
        $this->setData(self::BONUS_PRODUCT_ID, $id);
    }

    /**
     * @return int
     */
    public function getBonusProductId()
    {
        return $this->getData(self::BONUS_PRODUCT_ID);
    }
}

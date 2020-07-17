<?php

namespace RockLab\Gifto\Model;

use Magento\Framework\Model\AbstractModel;
use RockLab\Gifto\Api\Model\GiftProductInterface;
use RockLab\Gifto\Model\ResourceModel\GiftProduct as ResourceModel;

/**
 * Class GiftProduct
 * @package RockLab\Gifto\Model
 */
class GiftProduct extends AbstractModel implements GiftProductInterface
{
    /**
     * Initialize model
     */
    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @param $mainProd
     * @return mixed
     */
    public function setMainProduct($mainProd)
    {
    $this->setData(self::MAIN_PRODUCTS,$mainProd);
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function setIdsMainProduct ($ids)
    {
        $this->setData(self::IDS_MAIN_PRODUCTS,$ids);
    }
    /**
     * @return string
     */
    public function getMainProduct ()
    {
        return $this->getData(self::MAIN_PRODUCTS);
    }

    /**
     * @return string
     */
    public function getIdsMainProduct()
    {
        return $this->getData(self::IDS_MAIN_PRODUCTS);
    }

    /**
     * @param $qty
     * @return mixed
     */
    public function setQty($qty)
    {
        $this->setData(self::QTY, $qty);
    }

    /**
     * @return int
     */
    public function getQty()
    {
        return $this->getData(self::QTY);
    }

    /**
     * @param $bonusProducts
     * @return mixed
     */
    public function setBonusProducts($bonusProducts)
    {
        $this->setData(self::BONUS_PRODUCTS, $bonusProducts);
    }

    public function getBonusProducts()
    {
        return $this->getData(self::BONUS_PRODUCTS);
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function setIdsBonusProduct($ids)
    {
        $this->setData(self::IDS_BONUS_PRODUCTS,$ids);
    }

    public function getIdsBonusProduct()
    {
        return $this->getData(self::IDS_BONUS_PRODUCTS);
    }
}

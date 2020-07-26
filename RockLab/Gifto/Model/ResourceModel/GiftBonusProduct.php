<?php

namespace RockLab\Gifto\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class GiftBonusProduct
 * @package RockLab\Gifto\Model\ResourceModel
 */
class GiftBonusProduct extends AbstractDb
{
    /**
     * Primary key auto increment flag
     *
     * @var bool
     */
    protected $_isPkAutoIncrement = false;
    /**
     * Standard resource model initialization.
     */
    public function _construct()
    {
        $this->_init('gift_id_bonus_product_connection', 'id');
    }
}

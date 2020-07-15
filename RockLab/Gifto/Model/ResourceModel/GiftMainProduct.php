<?php

namespace RockLab\Gifto\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class GiftMainProduct
 * @package RockLab\Gifto\Model\ResourceModel
 */
class GiftMainProduct extends AbstractDb
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
        $this->_init('gift_product_connection', 'main_product_id');
    }
}

<?php

namespace RockLab\Gifto\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class GiftProduct
 * @package RockLab\Gifto\Model\ResourceModel
 */
class GiftProduct extends AbstractDb
{
    /**
     * Standard resource model initialization.
     */
    public function _construct()
    {
        $this->_init('gift_products', 'id');
    }
}

<?php

namespace RockLab\Gifto\Model\ResourceModel\GiftMainProduct;

use RockLab\Gifto\Model\GiftMainProduct as Model;
use RockLab\Gifto\Model\ResourceModel\GiftMainProduct as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package RockLab\Gifto\Model\ResourceModel\GiftProduct
 */
class Collection extends AbstractCollection
{
    /**
     * Standard resource collection initialization.
     * Initialize model and resource model.
     */
    public function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}

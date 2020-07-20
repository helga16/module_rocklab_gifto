<?php

namespace RockLab\Gifto\Model\ResourceModel\GiftBonusProduct;

use RockLab\Gifto\Model\GiftBonusProduct as Model;
use RockLab\Gifto\Model\ResourceModel\GiftBonusProduct as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package RockLab\Gifto\Model\ResourceModel\GiftBonusProduct
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

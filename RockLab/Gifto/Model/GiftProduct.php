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
}

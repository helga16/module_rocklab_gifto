<?php

namespace Nvg\AdditionalAttr\Model\ResourceModel\VendorAttr;

use Nvg\AdditionalAttr\Model\VendorAttr as Model;
use Nvg\AdditionalAttr\Model\ResourceModel\VendorAttr as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Nvg\AdditionalAttr\Model\ResourceModel\VendorAttr
 */
class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
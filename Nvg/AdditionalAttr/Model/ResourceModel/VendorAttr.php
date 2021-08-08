<?php
namespace Nvg\AdditionalAttr\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class VendorAttr
 * @package Nvg\AdditionalAttr\Model\ResourceModel
 */
class VendorAttr extends AbstractDb
{
    public function _construct()
    {
        $this->_init('vendor_source', 'id');
    }
}
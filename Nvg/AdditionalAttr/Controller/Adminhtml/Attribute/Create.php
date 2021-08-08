<?php

namespace Nvg\AdditionalAttr\Controller\Adminhtml\Attribute;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Create
 * @package Nvg\AdditionalAttr\Controller\Adminhtml\Attribute
 */
class Create extends Action
{
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
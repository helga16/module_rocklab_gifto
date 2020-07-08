<?php


namespace RockLab\Gifto\Controller\Adminhtml\Gift;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Create extends Action
{
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}

<?php

namespace RockLab\Gifto\Controller\Adminhtml\Gift;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Index
 * @package RockLab\Gifto\Controller\Adminhtml\GiftList
 */
class Index extends Action
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $page =  $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->setActiveMenu('RockLab_Gifto::GIFT_MANAGER');
        $page->getConfig()->getTitle()->prepend(__('Gift List'));
        return $page;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('RockLab_Gifto::managers_gifts_list_access');
    }
}

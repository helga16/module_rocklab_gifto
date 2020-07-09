<?php

namespace RockLab\Gifto\Controller\Adminhtml\Gift;

use RockLab\Gifto\Api\Repository\GiftRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Class Delete
 * @package RockLab\Gifto\Controller\Adminhtml\Gift
 */
class Delete extends Action
{
    /** @var GiftRepositoryInterface */
    private $repository;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Delete constructor.
     *
     * @param Context                   $context
     * @param GiftRepositoryInterface   $repository
     * @param LoggerInterface           $logger
     */
    public function __construct(
        Context $context,
        GiftRepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->repository   = $repository;
        $this->logger       = $logger;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if (empty($id)) {
            $this->messageManager->addWarningMessage(__("Please select id"));
            return $this->_redirect('*/*/index');
        }
        try {
            $this->repository->deleteById($id);
        } catch (NoSuchEntityException|CouldNotDeleteException $e) {
            $this->logger->info(sprintf("item %d already delete", $id));
        }
        $this->messageManager->addSuccessMessage(sprintf("item %d was deleted", $id));
        $this->_redirect('*/*/index');
    }
}

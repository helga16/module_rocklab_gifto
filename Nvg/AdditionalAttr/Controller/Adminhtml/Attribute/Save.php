<?php

namespace Nvg\AdditionalAttr\Controller\Adminhtml\Attribute;

use Nvg\AdditionalAttr\Model\VendorAttr;
use Nvg\AdditionalAttr\Model\VendorAttrFactory;
use Nvg\AdditionalAttr\Repository\VendorRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Psr\Log\LoggerInterface;

/**
 * Class Save
 * @package Nvg\AdditionalAttr\Controller\Adminhtml\Attribute
 */
class Save extends Action
{
    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var VendorRepository */
    private $repository;

    /** @var DataPersistorInterface */
    private $dataPersistor;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param Context $context
     * @param VendorRepository $repository
     * @param VendorAttrFactory $modelFactory
     * @param DataPersistorInterface $dataPersistor
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        VendorRepository $repository,
        VendorAttrFactory $modelFactory,
        DataPersistorInterface $dataPersistor,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger
    ) {
        $this->repository                = $repository;
        $this->modelFactory              = $modelFactory;
        $this->dataPersistor               = $dataPersistor;
        $this->searchCriteriaBuilder       = $searchCriteriaBuilder;
        $this->logger                      = $logger;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $data = $this->getRequest()->getPostValue();

        if (!empty($data)) {
            /** @var VendorAttr $model */
            $model = $this->modelFactory->create();
            $id = $this->getRequest()->getParam('id');

            if (!empty($id)) {
                try {
                    $model = $this->repository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This item no longer exists.'));
                    $resultRedirect->setPath('*/*/index');
                }
            } else {
                $data['id'] = null;
            }
            $data['date'] = date('y-m-d');
            $model->setData($data);

            try {
                $this->repository->save($model)->getId();
                $this->messageManager->addSuccessMessage(__('You saved the item.'));
                $this->dataPersistor->clear('vendor');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the vendor.'));
            }

            $this->dataPersistor->set('vendor', $data);

            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }

        return $resultRedirect->setPath('*/*/index');
    }
}
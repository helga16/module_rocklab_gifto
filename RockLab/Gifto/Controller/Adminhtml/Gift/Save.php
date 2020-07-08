<?php

namespace RockLab\Gifto\Controller\Adminhtml\Gift;

use RockLab\Gifto\Api\Model\GiftProductInterfaceFactory;
use RockLab\Gifto\Api\Repository\GiftRepositoryInterface;
use RockLab\Gifto\Model\GiftProduct;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Psr\Log\LoggerInterface;

/**
 * Class Save
 * @package RockLab\Gifto\Controller\Adminhtml\Gift
 */
class Save extends Action
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /** @var GiftRepositoryInterface */
    private $repository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /** @var GiftProductInterfaceFactory */
    private $modelFactory;

    /** @var DataPersistorInterface */
    private $dataPersistor;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Save constructor.
     * @param Context $context
     * @param GiftRepositoryInterface $repository
     * @param GiftProductInterfaceFactory $userFactory
     * @param DataPersistorInterface $dataPersistor
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        GiftRepositoryInterface $repository,
        GiftProductInterfaceFactory $modelFactory,
        DataPersistorInterface $dataPersistor,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger
    ) {
        $this->repository       = $repository;
        $this->modelFactory     = $modelFactory;
        $this->dataPersistor    = $dataPersistor;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger           = $logger;
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
        if ($data) {
            /** @var GiftProduct $model */
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
            $arrayProducts = $this->getRequest()->getParam('mainProducts');
            $searchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id',$arrayProducts,'in')->create();
            $mainProducts = $this->productRepository->getList($searchCriteria)->getItems();
            $labelsMainProducts = array_map (function ($item) {
                return $item->getName();
            }, $mainProducts);
            $arrayGiftProduct = $this->getRequest()->getParam('giftProducts');
            $searchCriteriaGift = $this->searchCriteriaBuilder->addFilter('entity_id',$arrayGiftProduct,'in')->create();
            $giftProducts = $this->productRepository->getList($searchCriteriaGift)->getItems();
            $labelsGiftProducts = [];
            foreach ($giftProducts as $gift) {
                $labelsGiftProducts[] = $gift->getName();
            }
            $data['giftProduct'] = implode(', ', $labelsGiftProducts);
            $data['mainProduct'] = implode(', ', $labelsMainProducts);
            $model->setData($data);

            try {
                $this->repository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the item.'.$label));
                $this->dataPersistor->clear('gift');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the user.'));
            }
            $this->dataPersistor->set('gift', $data);

            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }

        return $resultRedirect->setPath('*/*/index');
    }
}

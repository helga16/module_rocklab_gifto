<?php

namespace RockLab\Gifto\Controller\Adminhtml\Gift;

use RockLab\Gifto\Api\Model\GiftProductInterfaceFactory;
use RockLab\Gifto\Api\Model\GiftMainProductInterfaceFactory;
use RockLab\Gifto\Api\Repository\GiftRepositoryInterface;
use RockLab\Gifto\Api\Repository\GiftMainRepositoryInterface;
use RockLab\Gifto\Model\GiftMainProduct;
use RockLab\Gifto\Api\Model\GiftProductInterface as ModelMainProduct;

use RockLab\Gifto\Model\GiftProduct;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use RockLab\Gifto\DataProvider\ProductTitlesProvider;
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

    /** @var GiftMainRepositoryInterface */
    private $repositoryMainProduct;
    /**
     * @var ProductTitlesProvider
     */
    private $productProvider;

    /** @var GiftProductInterfaceFactory */
    private $modelFactory;

    /** @var GiftMainProductInterfaceFactory */
    private $modelGiftMainProductFactory;
    /** @var DataPersistorInterface */
    private $dataPersistor;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Save constructor.
     * @param Context $context
     * @param GiftRepositoryInterface $repository
     * @param GiftProductInterfaceFactory $modelFactory
     * @param DataPersistorInterface $dataPersistor
     * @param ProductTitlesProvider $productProvider
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        GiftRepositoryInterface $repository,
        GiftMainRepositoryInterface $repositoryMainProduct,
        GiftProductInterfaceFactory $modelFactory,
        GiftMainProductInterfaceFactory $modelGiftMainProductFactory,
        DataPersistorInterface $dataPersistor,
        ProductTitlesProvider $productProvider,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger
    ) {
        $this->repository       = $repository;
        $this->repositoryMainProduct = $repositoryMainProduct;
        $this->modelFactory     = $modelFactory;
        $this->modelGiftMainProductFactory = $modelGiftMainProductFactory;
        $this->dataPersistor    = $dataPersistor;
        $this->productProvider = $productProvider;
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
            /**
             * @var GiftMainProduct $modelForgift_product_connection
             */
            $modelForgift_product_connection = $this->modelGiftMainProductFactory->create();

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
            $arrayMainProducts = $this->getRequest()->getParam('idsMainProduct');
            $arrayGiftProducts = $this->getRequest()->getParam('idsGiftProduct');
            $labelsMainProducts = $this->productProvider->prepareProductLabels($arrayMainProducts);
            $labelsGiftProducts = $this->productProvider->prepareProductLabels($arrayGiftProducts);
            $data['giftProduct'] = implode(', ', $labelsGiftProducts);
            $data['idsGiftProduct'] = implode(', ', $arrayGiftProducts);
            $data['mainProduct'] = implode(', ', $labelsMainProducts);
            $data['idsMainProduct'] = implode(', ', $arrayMainProducts);
            $model->setData($data);
            try {
                $gift_id = $this->repository->save($model)->getId();
                $this->messageManager->addSuccessMessage(__('You saved the item.'));
                if(!empty($gift_id)){
                    $dataConnectTable['gift_id'] = $gift_id;
                    $idsMainProducts = $this->repository->getById($gift_id)->getIdsMainProduct();
                    $mainPro = explode(', ', $idsMainProducts);
                    foreach ($mainPro as $item) {
                        $dataConnectTable['main_product_id'] = intval($item);
                        $modelForgift_product_connection->setData($dataConnectTable);
                        $this->repositoryMainProduct->save($modelForgift_product_connection);
                    }
                    $searchCriteriaGifts = $this->searchCriteriaBuilder->addFilter('gift_id',$gift_id)->create();
                    $giftExistCollection = $this->repositoryMainProduct->getList($searchCriteriaGifts)->getItems();
                        if (!empty($giftExistCollection))
                        {
                            /** @var ModelMainProduct $gift */
                            foreach($giftExistCollection as $gift) {
                                $mainProductId = $gift->getMainProductId();
                                if (!in_array($mainProductId, $mainPro))
                                {
                                    $this->repositoryMainProduct->delete($gift);
                                }
                            }
                        }
                }
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

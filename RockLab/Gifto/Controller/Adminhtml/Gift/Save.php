<?php

namespace RockLab\Gifto\Controller\Adminhtml\Gift;

use RockLab\Gifto\Api\Model\GiftProductInterfaceFactory;
use RockLab\Gifto\Api\Model\GiftMainProductInterfaceFactory;
use RockLab\Gifto\Api\Repository\GiftRepositoryInterface;
use RockLab\Gifto\Api\Repository\GiftMainRepositoryInterface;
use RockLab\Gifto\Model\GiftMainProduct;
use RockLab\Gifto\Model\GiftBonusProduct;
use RockLab\Gifto\Api\Model\GiftBonusProductInterfaceFactory as ModelBonusProduct;
use RockLab\Gifto\Api\Repository\GiftBonusRepositoryInterface as RepositoryBonusProduct;
use RockLab\Gifto\Model\GiftProduct;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Api\SearchCriteriaBuilder;
use RockLab\Gifto\DataProvider\ProductTitlesProvider;
use Psr\Log\LoggerInterface;
use Zend_Validate_GreaterThan;

/**
 * Class Save
 * @package RockLab\Gifto\Controller\Adminhtml\Gift
 */
class Save extends Action
{
    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var GiftRepositoryInterface */
    private $repository;

    /** @var GiftMainRepositoryInterface */
    private $repositoryMainProduct;

    /** @var RepositoryBonusProduct */
    private $repositoryBonusProduct;

    /** @var ProductTitlesProvider */
    private $productProvider;

    /** @var GiftProductInterfaceFactory */
    private $modelFactory;

    /** @var GiftMainProductInterfaceFactory */
    private $modelGiftMainProductFactory;

    /** @var ModelBonusProduct */
    private $modelBonusProductFactory;

    /** @var DataPersistorInterface */
    private $dataPersistor;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Save constructor.
     * @param Context $context
     * @param GiftRepositoryInterface $repository
     * @param GiftMainRepositoryInterface $repositoryMainProduct
     * @param RepositoryBonusProduct $repositoryBonusProduct
     * @param GiftProductInterfaceFactory $modelFactory
     * @param GiftMainProductInterfaceFactory $modelGiftMainProductFactory
     * @param ModelBonusProduct $modelBonusProductFactory
     * @param DataPersistorInterface $dataPersistor
     * @param ProductTitlesProvider $productProvider
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        GiftRepositoryInterface $repository,
        GiftMainRepositoryInterface $repositoryMainProduct,
        RepositoryBonusProduct $repositoryBonusProduct,
        GiftProductInterfaceFactory $modelFactory,
        GiftMainProductInterfaceFactory $modelGiftMainProductFactory,
        ModelBonusProduct $modelBonusProductFactory,
        DataPersistorInterface $dataPersistor,
        ProductTitlesProvider $productProvider,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger
    ) {
        $this->repository                = $repository;
        $this->repositoryMainProduct     = $repositoryMainProduct;
        $this->repositoryBonusProduct    = $repositoryBonusProduct;
        $this->modelFactory              = $modelFactory;
        $this->modelGiftMainProductFactory = $modelGiftMainProductFactory;
        $this->modelBonusProductFactory    = $modelBonusProductFactory;
        $this->dataPersistor               = $dataPersistor;
        $this->productProvider             = $productProvider;
        $this->searchCriteriaBuilder       = $searchCriteriaBuilder;
        $this->logger                      = $logger;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
     public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $data = $this->getRequest()->getPostValue();

        if (!empty($data)) {
            /** @var GiftProduct $model */
            $model = $this->modelFactory->create();
            /** @var GiftMainProduct $modelForgift_product_connection */
            $modelForgift_product_connection = $this->modelGiftMainProductFactory->create();
            /** @var GiftBonusProduct $modelBonusProduct */
            $modelBonusProduct = $this->modelBonusProductFactory->create();

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
            $qtyProductRequest = $this->getRequest()->getParam('qty');
            $data['mainProduct'] = $this->productProvider->prepareProductLabels($arrayMainProducts);
            $data['giftProduct'] = $this->productProvider->prepareProductLabels($arrayGiftProducts);
            $data['idsGiftProduct'] = implode(', ', $arrayGiftProducts);
            $data['idsMainProduct'] = implode(', ', $arrayMainProducts);
            $validator = new Zend_Validate_GreaterThan(1);

            if ($validator->isValid($qtyProductRequest)) {
                $model->setData($data);
            } else {
                throw new LocalizedException(__('Please enter correct number'));
            }
            try {
                $giftId = $this->repository->save($model)->getId();
                $this->messageManager->addSuccessMessage(__('You saved the item.'));
                if (!empty($giftId)) {
                    $idsMainProducts = $this->repository->getById($giftId)->getIdsMainProduct();
                    $idsBonusProducts = $this->repository->getById($giftId)->getIdsBonusProduct();
                    $this->repositoryBonusProduct->saveArray($idsBonusProducts, $modelBonusProduct, $giftId);
                    $this->repositoryMainProduct->saveArray($idsMainProducts, $modelForgift_product_connection, $giftId);
                    $this->repositoryBonusProduct
                        ->deleteExistBonusCollection('gift_id', $giftId, $idsBonusProducts);
                    $this->repositoryMainProduct
                         ->deleteExistMainProductCollection('gift_id', $giftId, $idsMainProducts);
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

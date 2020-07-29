<?php

namespace RockLab\Gifto\Repository;

use RockLab\Gifto\Api\Model\GiftBonusProductInterface;
use RockLab\Gifto\Api\Repository\GiftBonusRepositoryInterface;
use RockLab\Gifto\Model\ResourceModel\GiftBonusProduct as ResourceModel;
use RockLab\Gifto\Model\ResourceModel\GiftBonusProduct\Collection;
use RockLab\Gifto\Model\ResourceModel\GiftBonusProduct\CollectionFactory;
use RockLab\Gifto\Model\GiftBonusProductFactory as ModelFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GiftBonusRepository
 * @package RockLab\Gifto\Repository
 */
class GiftBonusRepository implements GiftBonusRepositoryInterface
{
    /** @var ResourceModel */
    private $resource;

    /** @var ModelFactory */
    private $modelFactory;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var CollectionProcessorInterface */
    private $processor;

    /** @var SearchResultsInterfaceFactory */
    private $searchResultFactory;

    /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /**
     * GiftBonusRepository constructor.
     * @param ResourceModel $resource
     * @param ModelFactory $modeFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsInterfaceFactory $searchResultFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ResourceModel $resource,
        ModelFactory $modeFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->resource             = $resource;
        $this->modelFactory         = $modeFactory;
        $this->collectionFactory    = $collectionFactory;
        $this->processor            = $collectionProcessor;
        $this->searchResultFactory  = $searchResultFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param int $id
     * @return GiftBonusProductInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): GiftBonusProductInterface
    {
        $gift = $this->modelFactory->create();
        $this->resource->load($gift, $id);
        if (empty($gift->getId())) {
            throw new NoSuchEntityException(__("Item %1 not found", $id));
        }

        return $gift;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->processor->process($searchCriteria, $collection);
        /** @var SearchResultsInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setItems($collection->getItems());

        return $searchResult;
    }

    /**
     * @param string $searchCriteriaField
     * @param int $searchCriteriaValue
     * @param string $strBonusProducts
     * @throws CouldNotDeleteException
     */
    public function deleteExistBonusCollection ($searchCriteriaField, $searchCriteriaValue, $strBonusProducts)
    {
        $bonusProducts = explode(', ', $strBonusProducts);
        $searchCriteriaGifts = $this->searchCriteriaBuilder->addFilter($searchCriteriaField, $searchCriteriaValue)->create();
        $bonusExistCollection = $this->getList($searchCriteriaGifts)->getItems();
        if (!empty($bonusExistCollection)) {
            /** @var  GiftBonusProductInterface $bonus */
            foreach ($bonusExistCollection as $bonus) {
                $bonusProductId = $bonus->getBonusProductId();
                if (!in_array($bonusProductId, $bonusProducts)) {
                   try {
                     $this->delete($bonus);
                   } catch (\Exception $e) {
                      throw new CouldNotDeleteException('Gift does not delete');
                   }
                }
            }
        }
    }

    /**
     * @param GiftBonusProductInterface $gift
     * @return GiftBonusProductInterface
     * @throws CouldNotSaveException
     */
    public function save(GiftBonusProductInterface $gift): GiftBonusProductInterface
    {
        try {
            $this->resource->save($gift);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__("Item could not save"));
        }

        return $gift;
    }

    /**
     * @param string $strProducts
     * @param GiftBonusProductInterface $model
     * @param int $gift_id
     * @throws CouldNotSaveException
     */
    public function saveArray($strProducts, GiftBonusProductInterface $model, $gift_id)
    {
        $dataConnectTable['gift_id'] = $gift_id;
        $bonusProductsArray = explode(', ', $strProducts);
        foreach ($bonusProductsArray as $item) {
            $searchCriteriaInExistArr = $this->searchCriteriaBuilder
                ->addFilter('gift_id', $gift_id)
                ->addFilter('bonus_product_id', $item)
                ->create();
            $giftProductsExistCollection = $this->getList($searchCriteriaInExistArr)->getItems();
            if (empty($giftProductsExistCollection)) {
                $dataConnectTable['bonus_product_id'] = (int) $item;
                $model->setData($dataConnectTable);
                $this->save($model);
            }
        }
    }

    /**
     * @param GiftBonusProductInterface $gift
     * @return $this|GiftBonusRepositoryInterface
     * @throws CouldNotDeleteException
     */
    public function delete(GiftBonusProductInterface $gift): GiftBonusRepositoryInterface
    {
        try {
        $this->resource->delete($gift);
        } catch (\Exception $e){
            throw new CouldNotDeleteException('Gift does not delete');
        }

        return $this;
    }

    /**
     * @param int $id
     * @return $this|GiftBonusRepositoryInterface
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): GiftBonusRepositoryInterface
    {
        $gift = $this->getById($id);
        $this->delete($gift);

        return $this;
    }
}

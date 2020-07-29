<?php

namespace RockLab\Gifto\Repository;

use RockLab\Gifto\Api\Model\GiftMainProductInterface;
use RockLab\Gifto\Api\Repository\GiftMainRepositoryInterface;
use RockLab\Gifto\Model\ResourceModel\GiftMainProduct as ResourceModel;
use RockLab\Gifto\Model\ResourceModel\GiftMainProduct\Collection;
use RockLab\Gifto\Model\ResourceModel\GiftMainProduct\CollectionFactory;
use RockLab\Gifto\Model\GiftMainProductFactory as ModelFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GiftMainRepository
 * @package RockLab\Gifto\Repository
 */
class GiftMainRepository implements GiftMainRepositoryInterface
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

    /**
     * @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /**
     * GiftMainRepository constructor.
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
     * @return GiftMainProductInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): GiftMainProductInterface
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
     * @param string $strMainProducts
     * @throws CouldNotDeleteException
     */
    public function deleteExistMainProductCollection ($searchCriteriaField, $searchCriteriaValue, $strMainProducts)
    {
        $mainProducts = explode(', ', $strMainProducts);
        $searchCriteria = $this->searchCriteriaBuilder->addFilter($searchCriteriaField, $searchCriteriaValue)->create();
        $mainProductsExistCollection = $this->getList($searchCriteria)->getItems();
        if (!empty($mainProductsExistCollection)) {
            /** @var  GiftMainProductInterface $product */
            foreach ($mainProductsExistCollection as $product) {
                $productId = $product->getMainProductId();
                if (!in_array($productId, $mainProducts)) {
                    try {
                        $this->delete($product);
                    } catch (\Exception $e) {
                        throw new CouldNotDeleteException('Gift does not delete');
                    }
                }
            }
        }
    }

    /**
     * @param GiftMainProductInterface $gift
     * @return GiftMainProductInterface
     * @throws CouldNotSaveException
     */
    public function save(GiftMainProductInterface $gift): GiftMainProductInterface
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
     * @param GiftMainProductInterface $model
     * @param int $gift_id
     * @throws CouldNotSaveException
     */
    public function saveArray($strProducts, GiftMainProductInterface $model, $gift_id)
    {
        $dataConnectTable['gift_id'] = $gift_id;
        $mainProductsArray = explode(', ', $strProducts);
            foreach ($mainProductsArray as $item) {
                $searchCriteriaInExistArr = $this->searchCriteriaBuilder
                    ->addFilter('gift_id',  $gift_id)
                    ->addFilter('main_product_id', $item)
                    ->create();
                $mainProductsExistCollection = $this->getList($searchCriteriaInExistArr)->getItems();
                if (empty($mainProductsExistCollection)) {
                    $dataConnectTable['main_product_id'] = intval($item);
                    $model->setData($dataConnectTable);
                    $this->save($model);
                }
            }
    }

    /**
     * @param GiftMainProductInterface $gift
     * @return $this|GiftMainRepositoryInterface
     * @throws CouldNotDeleteException
     */
    public function delete(GiftMainProductInterface $gift): GiftMainRepositoryInterface
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
     * @return $this|GiftMainRepositoryInterface
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): GiftMainRepositoryInterface
    {
        $gift = $this->getById($id);
        $this->delete($gift);

        return $this;
    }
}

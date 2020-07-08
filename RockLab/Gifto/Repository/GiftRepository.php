<?php

namespace RockLab\Gifto\Repository;

use RockLab\Gifto\Api\Model\GiftProductInterface;
use RockLab\Gifto\Api\Repository\GiftRepositoryInterface;
use RockLab\Gifto\Model\ResourceModel\GiftProduct as ResourceModel;
use RockLab\Gifto\Model\ResourceModel\GiftProduct\Collection;
use RockLab\Gifto\Model\ResourceModel\GiftProduct\CollectionFactory;
use RockLab\Gifto\Model\GiftProductFactory as ModelFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GiftRepository
 * @package RockLab\Gifto\Repository
 */
class GiftRepository implements GiftRepositoryInterface
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
     * GiftRepository constructor.
     * @param ResourceModel $resource
     * @param ModelFactory $modeFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsInterfaceFactory $searchResultFactory
     */
    public function __construct(
        ResourceModel $resource,
        ModelFactory $modeFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultFactory
    ) {
        $this->resource             = $resource;
        $this->modelFactory         = $modeFactory;
        $this->collectionFactory    = $collectionFactory;
        $this->processor            = $collectionProcessor;
        $this->searchResultFactory  = $searchResultFactory;
    }

    /**
     * @param int $id
     * @return GiftProductInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): GiftProductInterface
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
     * @param GiftProductInterface $gift
     * @return GiftProductInterface
     * @throws CouldNotSaveException
     */
    public function save(GiftProductInterface $gift): GiftProductInterface
    {
        try {
            $this->resource->save($gift);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__("Item could not save"));
        }

        return $gift;
    }

    /**
     * @param GiftProductInterface $gift
     * @return $this|GiftRepositoryInterface
     * @throws CouldNotDeleteException
     */
    public function delete(GiftProductInterface $gift): GiftRepositoryInterface
    {
        try {
            $this->resource->delete($gift);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException("Item not delete");
        }

        return $this;
    }

    /**
     * @param int $id
     * @return $this|GiftRepositoryInterface
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): GiftRepositoryInterface
    {
        $gift = $this->getById($id);
        $this->delete($gift);

        return $this;
    }
}

<?php

namespace RockLab\Gifto\Repository;

use RockLab\Gifto\Api\Model\GiftMainProductInterface;
use RockLab\Gifto\Api\Repository\GiftMainRepositoryInterface;
use RockLab\Gifto\Model\ResourceModel\GiftMainProduct as ResourceModel;
use RockLab\Gifto\Model\ResourceModel\GiftMainProduct\Collection;
use RockLab\Gifto\Model\ResourceModel\GiftMainProduct\CollectionFactory;
use RockLab\Gifto\Model\GiftMainProductFactory as ModelFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
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

}

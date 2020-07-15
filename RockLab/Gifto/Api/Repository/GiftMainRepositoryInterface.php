<?php

namespace RockLab\Gifto\Api\Repository;

use RockLab\Gifto\Api\Model\GiftMainProductInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface GiftMainRepositoryInterface
 * @package RockLab\Gifto\Api\Repository
 */
interface GiftMainRepositoryInterface
{
    /**
     * Get gift by ID
     *
     * @param int $id
     * @throws NoSuchEntityException
     * @return GiftMainProductInterface
     */
    public function getById(int $id) : GiftMainProductInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria) : SearchResultsInterface;

    /**
     * @param GiftMainProductInterface $gift
     * @throws CouldNotSaveException
     * @return GiftMainProductInterface
     */
    public function save(GiftMainProductInterface $gift) : GiftMainProductInterface;


}

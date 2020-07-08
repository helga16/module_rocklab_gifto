<?php

namespace RockLab\Gifto\Api\Repository;

use RockLab\Gifto\Api\Model\GiftProductInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface GiftRepositoryInterface
 * @package RockLab\Gifto\Api\Repository
 */
interface GiftRepositoryInterface
{
    /**
     * Get gift by ID
     *
     * @param int $id
     * @throws NoSuchEntityException
     * @return GiftProductInterface
     */
    public function getById(int $id) : GiftProductInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria) : SearchResultsInterface;

    /**
     * @param GiftProductInterface $gift
     * @throws CouldNotSaveException
     * @return GiftProductInterface
     */
    public function save(GiftProductInterface $gift) : GiftProductInterface;

    /**
     * @param GiftProductInterface $gift
     * @throws CouldNotDeleteException
     * @return GiftRepositoryInterface
     */
    public function delete(GiftProductInterface $gift) : GiftRepositoryInterface;

    /**
     * @param int $id
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     * @return GiftRepositoryInterface
     */
    public function deleteById(int $id) : GiftRepositoryInterface;
}

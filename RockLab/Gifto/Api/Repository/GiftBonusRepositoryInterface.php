<?php

namespace RockLab\Gifto\Api\Repository;

use RockLab\Gifto\Api\Model\GiftBonusProductInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface GiftBonusRepositoryInterface
 * @package RockLab\Gifto\Api\Repository
 */
interface GiftBonusRepositoryInterface
{
    /**
     * Get gift by ID
     *
     * @param int $id
     * @throws NoSuchEntityException
     * @return GiftBonusProductInterface
     */
    public function getById(int $id) : GiftBonusProductInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria) : SearchResultsInterface;

    /**
     * @param GiftBonusProductInterface $gift
     * @throws CouldNotSaveException
     * @return GiftBonusProductInterface
     */
    public function save(GiftBonusProductInterface $gift) : GiftBonusProductInterface;

    /**
     * @param GiftBonusProductInterface $gift
     * @return GiftBonusRepositoryInterface
     */
    public function delete(GiftBonusProductInterface $gift) : GiftBonusRepositoryInterface;

    /**
     * @param int $id
     * @return GiftBonusRepositoryInterface
     */
    public function deleteById(int $id) : GiftBonusRepositoryInterface;


}

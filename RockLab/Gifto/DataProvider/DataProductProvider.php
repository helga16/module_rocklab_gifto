<?php

namespace RockLab\Gifto\DataProvider;

use RockLab\Gifto\Model\GiftMainProduct;
use RockLab\Gifto\Repository\GiftRepository;
use RockLab\Gifto\Repository\GiftMainRepository;
use RockLab\Gifto\Repository\GiftBonusRepository;
use Magento\Catalog\Block\Product\View;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class DataProductProvider
 * @package RockLab\Gifto\DataProvider
 */
class DataProductProvider
{
    /** @var GiftRepository */
    private $repository;

    /** @var GiftMainRepository */
    private $repositoryGiftMain;

    /** @var GiftBonusRepository */
    private $repositoryBonus;

    /** @var View */
    private $product;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /**
     * DataProductProvider constructor.
     * @param GiftRepository $repository
     * @param GiftMainRepository $repositoryGiftMain
     * @param GiftBonusRepository $repositoryBonus
     * @param View $product
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        GiftRepository $repository,
        GiftMainRepository $repositoryGiftMain,
        GiftBonusRepository $repositoryBonus,
        View $product,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->repository = $repository;
        $this->repositoryGiftMain = $repositoryGiftMain;
        $this->repositoryBonus = $repositoryBonus;
        $this->product = $product;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param string $conditionValue
     * @param $productId
     * @return int|string
     */
    public function getGiftId($productId, $conditionValue = '')
    {
        if ($conditionValue === 'bonus') {
            $field = 'bonus_product_id';
            $repositoryFiltered = $this->repositoryBonus;
        } else {
            $field = 'main_product_id';
            $repositoryFiltered = $this->repositoryGiftMain;
        }
        $searchCriteria = $this->searchCriteriaBuilder
                               ->addFilter($field, $productId)
                               ->create();
        $collection = $repositoryFiltered->getList($searchCriteria)
                                         ->getItems();
        $giftId = '';

        /** @var GiftMainProduct $item */
        foreach ($collection as $item) {
            $giftId = (int) $item->getGiftId();
        }

        return $giftId;
    }

    /**
     * @param int $idItem
     * @param string $conditionValue
     * @return array
     */
    public function getProductsCollectionInfoById(int $idItem, $conditionValue = ''): array
    {
        if ($conditionValue === 'bonus') {
            $gift_id = $this->getGiftId($idItem, 'bonus');
        } else {
            $gift_id = $this->getGiftId($idItem, '');
        }
        $searchCriteria = $this->searchCriteriaBuilder
                                ->addFilter('id',$gift_id)
                                ->create();
        $collectionItems = $this->repository
                                ->getList($searchCriteria)
                                ->getItems();
        $arrData = [];

        /** @var \RockLab\Gifto\Model\GiftProduct $item */
        foreach ($collectionItems as $item) {
            $arrData['qty'] = $item->getQty();
            $arrData['bonusProducts'] = $item->getBonusProducts();
            $arrData['IdsBonusProducts'] = $item->getIdsBonusProduct();
            $arrData['IdsMainProducts'] = $item->getIdsMainProduct();
        }

        return $arrData;
    }

    /**
     * @param string $conditionValue
     * @return array
     */
    public function getProductDataArray($conditionValue = ''): array
    {
        $productId = $this->product->getProduct()->getId();

        return $this->getProductsCollectionInfoById($productId, $conditionValue);
    }
}

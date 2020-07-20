<?php

namespace RockLab\Gifto\Block;

use Magento\Framework\View\Element\Template;
use RockLab\Gifto\Model\GiftMainProduct;
use RockLab\Gifto\Model\GiftBonusProduct;
use RockLab\Gifto\Repository\GiftRepository;
use RockLab\Gifto\Repository\GiftMainRepository;
use RockLab\Gifto\Repository\GiftBonusRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class GifNote
 * @package RockLab\Gifto\Block
 */
class GifNote extends Template
{
    /**
     * @var GiftRepository
     */
    private $repository;
    /**
     * @var GiftMainRepository
     */
    private $repositoryGiftMain;

    /** @var GiftBonusRepository */
    private $repositoryBonus;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * GifNote constructor.
     * @param Template\Context $context
     * @param GiftRepository $repository
     * @param GiftMainRepository $repositoryGiftMain
     * @param GiftBonusRepository $repositoryBonus
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        GiftRepository $repository,
        GiftMainRepository $repositoryGiftMain,
        GiftBonusRepository $repositoryBonus,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    )
    {
        $this->repository = $repository;
        $this->repositoryGiftMain = $repositoryGiftMain;
        $this->repositoryBonus = $repositoryBonus;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context, $data);
    }

    /**
     * @param int $productId
     * @return int
     */
    public function getGiftId ($productId)
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('main_product_id',$productId)->create();
        $collection = $this->repositoryGiftMain->getList($searchCriteria)->getItems();
        $gift_id = '';
        /** @var GiftMainProduct $item */
        foreach ($collection as $item)
        {
            $gift_id = intval($item->getGift_id());
        }

        return $gift_id;
    }
    public function getGiftIdViaBonus ($bonusId)
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('bonus_product_id',$bonusId)->create();
        $collection = $this->repositoryBonus->getList($searchCriteria)->getItems();
        $gift_id = '';
        /** @var GiftBonusProduct $item */
        foreach ($collection as $item)
        {
            $gift_id = intval($item->getGift_id());
        }

        return $gift_id;
    }

    public function getMainProductsViaBonusProduct ($bonusProductId) {
        $gift_id = $this->getGiftIdViaBonus($bonusProductId);
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('id',$gift_id)->create();
        $collectionItems = $this->repository->getList($searchCriteria)->getItems();
        $arrData = [];
        /** @var \RockLab\Gifto\Model\GiftProduct $item */
        foreach ($collectionItems as $item)
        {
            $arrData['qty'] = $item->getQty();
            $arrData['IdsMainProducts'] = $item->getIdsMainProduct();

        }
        return $arrData;
    }

    /**
     * @param int $productId
     * @return array
     */
    public function getGiftCollectionItems($productId){
        $gift_id = $this->getGiftId($productId);
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('id',$gift_id)->create();
        $collectionItems = $this->repository->getList($searchCriteria)->getItems();
        $arrData = [];
        /** @var \RockLab\Gifto\Model\GiftProduct $item */
        foreach ($collectionItems as $item)
        {
           $arrData['qty'] = $item->getQty();
           $arrData['bonusProducts'] = $item->getBonusProducts();
           $arrData['IdsBonusProducts'] = $item->getIdsBonusProduct();
        }

        return $arrData;
    }
}

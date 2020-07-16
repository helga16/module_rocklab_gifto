<?php

namespace RockLab\Gifto\Block;

use Magento\Framework\View\Element\Template;
use RockLab\Gifto\Model\GiftMainProduct;
use RockLab\Gifto\Repository\GiftRepository;
use RockLab\Gifto\Repository\GiftMainRepository;
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
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * GifNote constructor.
     * @param Template\Context $context
     * @param GiftRepository $repository
     * @param GiftMainRepository $repositoryGiftMain
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        GiftRepository $repository,
        GiftMainRepository $repositoryGiftMain,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    )
    {
        $this->repository = $repository;
        $this->repositoryGiftMain = $repositoryGiftMain;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context, $data);
    }

    /**
     * @param int $productId
     * @return int
     */
    public function getGiftId($productId){
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
        }

        return $arrData;
    }
}

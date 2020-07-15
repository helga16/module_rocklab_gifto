<?php

namespace RockLab\Gifto\Block;

use Magento\Framework\View\Element\Template;
use RockLab\Gifto\Model\GiftMainProduct;
use RockLab\Gifto\Repository\GiftRepository;
use RockLab\Gifto\Repository\GiftMainRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;

class GifNote extends Template
{
    private $repository;
    /**
     * @var GiftMainRepository
     */
    private $repositoryGiftMain;
    private $searchCriteriaBuilder;
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
    public function getGiftId($giftId){
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('main_product_id',$giftId)->create();
$collection = $this->repositoryGiftMain->getList($searchCriteria)->getItems();
        $gift_id = '';
        /**
         * @var GiftMainProduct $item
         */
foreach ($collection as $item){
    $gift_id = intval($item->getGift_id());
}
return $gift_id;
    }

    public function getCollectionItems($value){
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('id',$value)->create();
        $collectionItems = $this->repository->getList($searchCriteria)->getItems();
        $arrData = [];
        /** @var \RockLab\Gifto\Model\GiftProduct $item */
        foreach($collectionItems as $item){
           $arrData['qty'] = $item->getQty();
           $arrData['bonusProducts'] = $item->getBonusProducts();
        }
        return $arrData;
    }
}

<?php

namespace RockLab\Gifto\DataProvider;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class ProductTitlesProvider
 * @package RockLab\Gifto\DataProvider
 */
class ProductTitlesProvider
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * ProductTitlesProvider constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param $arrayIds
     *
     * @return array|null[]
     */
    public function getProductIds ($arrayIds)
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id',$arrayIds,'in')->create();
        $arrayProducts = $this->productRepository->getList($searchCriteria)->getItems();
        $arrayLabelsProducts = array_map (
            function ($item) {
                return $item->getName();
            }, $arrayProducts
        );

        return $arrayLabelsProducts;
    }
}

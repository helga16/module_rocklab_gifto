<?php

namespace RockLab\Gifto\UI\Component\Form;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class ProductOptions
 * @package RockLab\Gifto\UI\Component\Form
 */
class ProductOptions implements OptionSourceInterface
{
    private $repository;
    protected $request;
    protected $productTree;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * ProductOptions constructor.
     * @param ProductRepositoryInterface $repository
     * @param RequestInterface $request
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ProductRepositoryInterface $repository,
        RequestInterface $request,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->repository = $repository;
        $this->request = $request;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return array|null
     */
    public function toOptionArray()
    {
        return $this->getProductTree();
    }

    /**
     * @return array|null
     */
    public function getProductTree(){
        if($this->productTree === null) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $searchResult = $this->repository->getList($searchCriteria);
            if ($searchResult->getTotalCount() > 0) {
                $arrayProducts = $searchResult->getItems();
                foreach ($arrayProducts as $product) {
                    $productId = $product->getId();
                    $productById[$productId] = [
                        'value' => $productId
                    ];
                    $productById[$productId]['label'] = $product->getName();
                }
                $this->productTree = $productById;
            }
        }

        return $this->productTree;
    }
}

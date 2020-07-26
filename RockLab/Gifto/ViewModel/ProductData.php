<?php

namespace RockLab\Gifto\ViewModel;

use Magento\Catalog\Block\Product\View;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use RockLab\Gifto\DataProvider\DataProductProvider;

/**
 * Class ProductData
 * @package RockLab\Gifto\ViewModel
 */
class ProductData implements ArgumentInterface
{
    /**
     * @var View
     */
    private $product;

    /**
     * @var DataProductProvider
     */
    private $productProvider;

    /**
     * ProductData constructor.
     * @param View $product
     * @param DataProductProvider $productProvider
     */
    public function __construct(
        View $product,
        DataProductProvider $productProvider
    )
    {
        $this->product = $product;
        $this->productProvider = $productProvider;
    }

    /**
     * @param string $conditionValue
     * @return array
     */
    public function getProductDataArray($conditionValue = ''): array
    {
        $productId = $this->product->getProduct()->getId();
        return $this->productProvider->getProductsCollectionInfoById($productId, $conditionValue);
    }
}

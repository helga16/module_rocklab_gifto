<?php


namespace RockLab\Gifto\ViewModel;

use Magento\Catalog\Block\Product\View;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class GetSku implements ArgumentInterface
{
    private $product;
    public function __construct(
        View $product
    )
    {
        $this->product = $product;
    }
    public function getSku(){
        return $this->product->getProduct()->getSku();
    }
    public function getProductId(){
        return $this->product->getProduct()->getId();
    }
}

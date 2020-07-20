<?php

namespace RockLab\Gifto\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use RockLab\Gifto\Block\GifNote;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class CheckoutCartSaveAfterObserver
 * @package RockLab\Gifto\Observer
 */
class CheckoutCartAddAfterObserver implements ObserverInterface
{
    private $productRepository;
    private $gifNote;
    public function __construct (
        GifNote $gifNote,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->gifNote = $gifNote;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Observer $observer
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $quote_item = $observer->getEvent()->getData('quote_item');
        $product = $observer->getEvent()->getData('product');
        if($product->getQty() === 1){
            $quote_item->setOriginalCustomPrice(0.00);
            $quote_item->getProduct()->setIsSuperMode(true);
        }
       return $this;
    }
}

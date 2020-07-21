<?php

namespace RockLab\Gifto\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use RockLab\Gifto\Block\GifNote;
use Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Quote\Model\Quote\ItemFactory;

/**
 * Class CheckoutCartSaveAfterObserver
 * @package RockLab\Gifto\Observer
 */
class CheckoutCartSaveAfterObserver implements ObserverInterface
{
    /**
     * @var ItemFactory
     */
    private $quoteItemFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var GifNote
     */
    private $gifNote;

    /**
     * CheckoutCartSaveAfterObserver constructor.
     * @param GifNote $gifNote
     * @param ItemFactory $quoteItemFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct (
        GifNote $gifNote,
        ItemFactory $quoteItemFactory,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->gifNote = $gifNote;
        $this->quoteItemFactory = $quoteItemFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Observer $observer
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $cart = $observer->getEvent()->getData('cart');
        $productsArray = $cart->getQuote()->getAllItems();
        $productsArrayIds = [];
        $generalArr = [];
        foreach ($productsArray as $product) {
            $productsArrayIds[]= $product->getProductId();
            $generalArr[] = [
                'qty'=>$product->getQty(),
                'id'=>$product->getProductId(),
                'quoteItemId'=>$product->getId(),
                'productPrice'=>$product->getPrice()
            ];
        }
        foreach ($generalArr as $quoteItemInfo) {
            if ($quoteItemInfo['productPrice'] !== 0.00) {
                $arrayGifts = $this->gifNote->getProductsCollectionInfoById('', $quoteItemInfo['id']);
                if (!empty($arrayGifts)) {
                    $arrayGiftsIds = explode(', ', $arrayGifts['IdsBonusProducts']);
                    foreach ($arrayGiftsIds as $giftId) {
                        if (!in_array($giftId, $productsArrayIds) && $arrayGifts['qty'] <= $quoteItemInfo['qty']) {
                            $product = $this->productRepository->getById($giftId);
                            $quoteItem = $this->quoteItemFactory->create();
                            $quoteItem->setProduct($product)->addQty(1.00);
                            $quoteItem->setOriginalCustomPrice(0.00);
                            $cart->getQuote()->addItem($quoteItem)->collectTotals()->save();
                        }
                        if (in_array($giftId, $productsArrayIds) && $arrayGifts['qty'] > $quoteItemInfo['qty'])
                        {
                            foreach ($generalArr as $key) {
                                if ($key['id'] == $giftId)
                                {
                                    $findQuotId = $key['quoteItemId'];
                                    $cart->removeItem($findQuotId);
                                }
                            }
                        }
                    }
                }
            }
            if ($quoteItemInfo['productPrice'] === 0.00)
            {
                $mainProductsStr = $this->gifNote->getProductsCollectionInfoById('bonus', $quoteItemInfo['id']);
                if (!empty($mainProductsStr))
                {
                    $mainProductsArr = explode(', ', $mainProductsStr['IdsMainProducts']);
                    if (empty(array_intersect($mainProductsArr, $productsArrayIds)))
                    {
                      $cart->removeItem($quoteItemInfo['quoteItemId']);
                    }
                }
            }
        }
        $cart->save();

        return $this;
    }
}

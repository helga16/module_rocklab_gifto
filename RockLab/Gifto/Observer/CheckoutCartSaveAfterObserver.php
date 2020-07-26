<?php

namespace RockLab\Gifto\Observer;

use Magento\Checkout\Model\Cart;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use RockLab\Gifto\DataProvider\DataProductProvider as DataProvider;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Model\Quote\ItemFactory;

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
     * @var DataProvider
     */
    private $gifNote;

    /**
     * CheckoutCartSaveAfterObserver constructor.
     * @param DataProvider $gifNote
     * @param ItemFactory $quoteItemFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct (
        DataProvider $gifNote,
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
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        /** @var Cart $cart */
        $cart = $observer->getEvent()->getData('cart');
        $productsArray = $cart->getQuote()->getAllItems();
        $productsArrayIds = [];
        foreach ($productsArray as $quoteItemInfo) {
            $productsArrayIds[] = $quoteItemInfo->getProductId();
        }
        foreach ($productsArray as $quoteItemInfo) {
            if (($quoteItemInfo->getPrice()) !== 0.00) {
                $arrayGifts = $this->gifNote->getProductsCollectionInfoById($quoteItemInfo->getProductId());
                if (!empty($arrayGifts)) {
                   $arrayGiftsIds = explode(', ', $arrayGifts['IdsBonusProducts']);
                   foreach ($arrayGiftsIds as $giftId) {
                       if (!in_array($giftId, $productsArrayIds) && $arrayGifts['qty'] <= ($quoteItemInfo->getQty())) {
                          $product = $this->productRepository->getById($giftId);
                          $quoteItem = $this->quoteItemFactory->create();
                          $quoteItem->setProduct($product)->addQty(1.00);
                          $quoteItem->setOriginalCustomPrice(0.00);
                          $cart->getQuote()
                              ->addItem($quoteItem)
                              ->setTotalsCollectedFlag(false)
                              ->collectTotals()
                              ->save();
                      }
                       if (in_array($giftId, $productsArrayIds) && $arrayGifts['qty'] > ($quoteItemInfo->getQty())) {
                         foreach ($productsArray as $key) {
                              if (($key->getProductId()) == $giftId) {
                                 $findQuotId = $key->getId();
                                 $cart->getQuote()
                                     ->removeItem($findQuotId)
                                     ->setTotalsCollectedFlag(false)
                                     ->collectTotals()
                                     ->save();
                             }
                         }
                      }
                   }
                }
            }
            if (($quoteItemInfo->getPrice()) === 0.00) {
                $mainProductsStr = $this->gifNote->getProductsCollectionInfoById(($quoteItemInfo->getProductId()), 'bonus');
                if (!empty($mainProductsStr)) {
                  $mainProductsArr = explode(', ', $mainProductsStr['IdsMainProducts']);
                  if (empty(array_intersect($mainProductsArr, $productsArrayIds))) {
                      $cart->getQuote()
                           ->removeItem($quoteItemInfo->getId())
                           ->setTotalsCollectedFlag(false)
                           ->collectTotals()
                           ->save();
                  }
               }
            }
        }
    }
}

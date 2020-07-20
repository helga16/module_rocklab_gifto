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
    private $quoteItemFactory;
    private $productRepository;
    private $gifNote;
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
        foreach ($generalArr as $idProduct) {
            if ($idProduct['productPrice'] !== 0.00) {
                $arrayGifts = $this->gifNote->getGiftCollectionItems($idProduct['id']);
                if (!empty($arrayGifts)) {
                    $arrayGiftsIds = explode(', ', $arrayGifts['IdsBonusProducts']);
                    foreach ($arrayGiftsIds as $giftId) {
                        if (!in_array($giftId, $productsArrayIds) && $arrayGifts['qty'] <= $idProduct['qty']) {
                            $product = $this->productRepository->getById($giftId);
                            $quoteItem = $this->quoteItemFactory->create();
                            $quoteItem->setProduct($product);
                            $quoteItem->addQty(1.00);
                            $quoteItem->setOriginalCustomPrice(0.00);
                            $quote = $cart->getQuote();
                            $quote->addItem($quoteItem);
                            $quote->collectTotals()->save();

                        }
                        if (in_array($giftId, $productsArrayIds) && $arrayGifts['qty'] > $idProduct['qty']) {
                            foreach ($generalArr as $key) {
                                if ($key['id'] == $giftId) {
                                    $findQuotId = $key['quoteItemId'];
                                    $cart->removeItem($findQuotId);
                                }
                            }
                        }
                        // if (in_array($giftId,$productsArrayIds) && $arrayGifts['qty'] > $idProduct['qty']) {
                        //   $cart->getQuote()->removeItem($idProduct['quoteItemId']);
                        //}
                    }
                }

            }
            if ($idProduct['productPrice'] === 0.00) {
                $mainProductsStr = $this->gifNote->getMainProductsViaBonusProduct($idProduct['id']);
                if (!empty($mainProductsStr)) {
                    $mainProductsArr = explode(', ', $mainProductsStr['IdsMainProducts']);
                    if (empty(array_intersect($mainProductsArr, $productsArrayIds))) {
                    $cart->removeItem($idProduct['quoteItemId']);
                    }
                }
            }
        }
        $cart->save();

        return $this;
    }
}

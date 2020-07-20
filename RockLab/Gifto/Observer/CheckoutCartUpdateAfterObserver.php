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
class CheckoutCartUpdateAfterObserver implements ObserverInterface
{

    private $productRepository;
    private $gifNote;
    public function __construct
    (
        GifNote $gifNote,
    ProductRepositoryInterface $productRepository
    )
    {
        $this->gifNote = $gifNote;
        $this->productRepository = $productRepository;
    }

    public function execute(Observer $observer)
    {
        $cart = $observer->getEvent()->getData('cart');
        $info = $observer->getEvent()->getData('info');
        /*
        $prId = $info->getProductId();
        $pr = $info->getProduct();
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

                        if (in_array($giftId, $productsArrayIds) && $arrayGifts['qty'] > $idProduct['qty']) {
                                foreach ($generalArr as $key){
                                    if ($key['id'] == $giftId){
                                        $findQuotId = $key['quoteItemId'];
                                        $cart->removeItem($findQuotId);
                                    }
                                }
                        }
                    }
                }
            }

        }
        $cart->save();

        return $this;
        */
    }

}

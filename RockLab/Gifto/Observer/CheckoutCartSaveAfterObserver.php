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
class CheckoutCartSaveAfterObserver implements ObserverInterface
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
        $productsArray = $cart->getQuote()->getAllItems();
        $productsArrayIds = [];
        foreach ($productsArray as $product) {
            $qtyProduct = $cart->getQtyRequest($product);
            $productsArrayIds[]= $product->getProductId();
            $qr = $product->getQtyOptions();

        }
        foreach ($productsArrayIds as $idProduct){
            $arrayGifts = $this->gifNote->getGiftCollectionItems($idProduct);
            $qtyMainProducts = $arrayGifts['qty'];
            if(!empty($arrayGifts)){
                $arrayGiftsIds = explode(', ', $arrayGifts['IdsBonusProducts']);
                foreach ($arrayGiftsIds as $giftId){
                    $product = $this->productRepository->getById($giftId);
                    $product->setPrice(0.00);
                    $info = new \Magento\Framework\DataObject();
                    $info->setQty(1);
                    $cart->addProduct($product, $info);



                }

            }
         }
        $cart->save();
        return $this;
    }
}

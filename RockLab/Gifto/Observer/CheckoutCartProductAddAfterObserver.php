<?php

namespace RockLab\Gifto\Observer;

use Magento\Checkout\Model\Session;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use RockLab\Gifto\DataProvider\DataProductProvider as DataProvider;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Model\Quote\ItemFactory;

/**
 * Class CheckoutCartProductAddAfterObserver
 * @package RockLab\Gifto\Observer
 */
class CheckoutCartProductAddAfterObserver implements ObserverInterface
{
    /** @var ItemFactory */
    private $quoteItemFactory;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var DataProvider */
    private $gifNote;

    /** @var Session */
    private $checkoutSession;

    /**
     * CheckoutCartProductAddAfterObserver constructor.
     * @param Session $checkoutSession
     * @param DataProvider $gifNote
     * @param ItemFactory $quoteItemFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Session $checkoutSession,
        DataProvider $gifNote,
        ItemFactory $quoteItemFactory,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->gifNote = $gifNote;
        $this->quoteItemFactory = $quoteItemFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $lastAddedProductId = $observer->getEvent()->getData('quote_item')->getProductId();
        $lastAddedProductQty = $observer->getEvent()->getData('quote_item')->getQty();
        $quote = $this->checkoutSession->getQuote();
        $productsArray = $quote->getAllItems();
        $productsArrayIds = [];
        foreach ($productsArray as $item) {
            $productsArrayIds[] = $item->getProductId();
        }

        $arrayBonusProducts = $this->gifNote->getProductsCollectionInfoById($lastAddedProductId);
        if (!empty($arrayBonusProducts)) {
            $arrayGiftsIds = explode(', ', $arrayBonusProducts['IdsBonusProducts']);
            foreach ($arrayGiftsIds as $giftId) {
                if (!in_array($giftId, $productsArrayIds) && $arrayBonusProducts['qty'] <= $lastAddedProductQty) {
                    $product = $this->productRepository->getById($giftId);
                    $quoteItem = $this->quoteItemFactory->create();
                    $quoteItem->setProduct($product)->addQty(1.00);
                    $quoteItem->setOriginalCustomPrice(0.00);
                    $quote->addItem($quoteItem)
                        ->setTotalsCollectedFlag(false)
                        ->collectTotals()
                        ->save();
                }
            }
        }
    }
}

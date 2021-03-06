<?php

namespace RockLab\Gifto\DataProvider;

use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use RockLab\Gifto\Api\Model\GiftProductInterface;
use RockLab\Gifto\Model\ResourceModel\GiftProduct\CollectionFactory;

/**
 * Class GiftProvider
 * @package RockLab\Gifto\DataProvider
 */
class GiftProvider extends ModifierPoolDataProvider
{
      /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var array
     */
    private $loadedData = [];

    /**
     * GiftProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        /** @var GiftProductInterface $gift */
        foreach ($items as $gift) {
            $changedItem = $gift->getData();
            $changedItem['idsGiftProduct'] = explode(', ',$changedItem['idsGiftProduct']);
            $changedItem['idsMainProduct'] = explode(', ',$changedItem['idsMainProduct']);
            $this->loadedData[$gift->getId()] = $changedItem;
        }
        $data = $this->dataPersistor->get('gift');
        if (!empty($data)) {
            $gift = $this->collection->getNewEmptyItem();
            $data['giftProduct'] = explode(', ',$data['giftProduct']);
            $gift->setData($data);
            $this->loadedData[$gift->getId()] = $gift->getData();
            $this->dataPersistor->clear('gift');
        }

        return $this->loadedData;
    }
}

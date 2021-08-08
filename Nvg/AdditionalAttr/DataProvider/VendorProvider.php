<?php
namespace Nvg\AdditionalAttr\DataProvider;

use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Nvg\AdditionalAttr\Model\ResourceModel\VendorAttr\Collection;
use Nvg\AdditionalAttr\Model\ResourceModel\VendorAttr\CollectionFactory;
use Nvg\AdditionalAttr\Model\VendorAttr;

class VendorProvider extends ModifierPoolDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

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

    public function getData()
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        /** @var VendorAttr $block */
        foreach ($items as $item) {
            $this->loadedData[$item->getId()] = $item->getData();
        }

        $data = $this->dataPersistor->get('vendor');
        if (!empty($data)) {
            $vendor = $this->collection->getNewEmptyItem();
            $vendor->setData($data);
            $this->loadedData[$vendor->getId()] = $vendor->getData();
            $this->dataPersistor->clear('vendor');
        }

        return $this->loadedData;
    }
}
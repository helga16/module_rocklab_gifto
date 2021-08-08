<?php
namespace Nvg\AdditionalAttr\Repository;

use Nvg\AdditionalAttr\Model\VendorAttr;
use Nvg\AdditionalAttr\Model\VendorAttr as ModelFactory;
use Nvg\AdditionalAttr\Model\ResourceModel\VendorAttr as ResourceModel;
use Nvg\AdditionalAttr\Model\ResourceModel\VendorAttr\Collection;
use Nvg\AdditionalAttr\Model\ResourceModel\VendorAttr\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class VendorRepository
{
    /** @var ResourceModel */
    private $resource;

    /** @var ModelFactory */
    private $modelFactory;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var CollectionProcessorInterface */
    private $processor;

    /**
     * @param ResourceModel $resource
     * @param ModelFactory $modeFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceModel $resource,
        ModelFactory $modeFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource             = $resource;
        $this->modelFactory         = $modeFactory;
        $this->collectionFactory    = $collectionFactory;
        $this->processor            = $collectionProcessor;
    }

    /**
     * @param int $id
     * @return ModelFactory
     * @throws NoSuchEntityException
     */
    public function getById(int $id): VendorAttr
    {
        $model = $this->modelFactory->create();
        $this->resource->load($model, $id);
        if (empty($model->getId())) {
            throw new NoSuchEntityException(__("Item %1 not found", $id));
        }

        return $model;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        /** @var Collection $collection */
        return $this->collectionFactory->create()->getItems();
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getByName($name)
    {
        /** @var Collection $collection */
        return $this->collectionFactory->create()->addFieldToFilter('name', ['eq' => $name]);
    }

    /**
     * @param ModelFactory $attr
     * @return ModelFactory
     * @throws CouldNotSaveException
     */
    public function save(VendorAttr $attr): VendorAttr
    {
        try {
            $this->resource->save($attr);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__("Item could not save"));
        }

        return $attr;
    }

    /**
     * @param ModelFactory $attr
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete(VendorAttr $attr): VendorRepository
    {
        try {
            $this->resource->delete($attr);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException("Item not delete");
        }

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): VendorRepository
    {
        $attr = $this->getById($id);
        $this->delete($attr);

        return $this;
    }
}
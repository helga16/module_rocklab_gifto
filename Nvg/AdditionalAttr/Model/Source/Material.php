<?php

namespace Nvg\AdditionalAttr\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Nvg\AdditionalAttr\Repository\VendorRepository;

class Material extends AbstractSource
{
    /**
     * @var VendorRepository
     */
    private $repository;

    public function __construct(VendorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllOptions()
    {
        $collection = $this->repository->getList();
        $arrVal = [];

        foreach ($collection as $item => $val) {
            $arrVal[$item]['label'] = $val['name'];
            $arrVal[$item]['value'] = $val['id'];
        }

        if (!$this->_options) {
            $this->_options = array_values($arrVal);
        }

        return $this->_options;
    }
}
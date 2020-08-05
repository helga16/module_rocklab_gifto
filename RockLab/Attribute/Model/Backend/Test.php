<?php

namespace RockLab\Attribute\Model\Backend;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;

/**
 * Class Test
 * @package ALevel\Attributes\Model\Attribute\Backend
 */
class Test extends AbstractBackend
{
    /** {@inheritDoc} */
    public function validate($object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());


        return true;
    }
}

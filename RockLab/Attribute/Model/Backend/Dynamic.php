<?php

namespace RockLab\Attribute\Model\Backend;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;


class Dynamic extends AbstractBackend
{
    /** {@inheritDoc} */
    public function validate($object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());

        if ($value == '') {
            throw new LocalizedException(__('type the value'));
        }

        return true;
    }
}

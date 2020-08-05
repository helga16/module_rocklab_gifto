<?php

namespace RockLab\Attribute\Model\Frontend;

use Magento\Framework\DataObject;
use Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend;

class Dynamic extends AbstractFrontend
{
    /** {@inheritDoc} */
    public function getValue(DataObject $object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());

        return sprintf("<em>%s</em>", $value);
    }
}

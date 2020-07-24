<?php
/**
 * Pavel Usachev <webcodekeeper@hotmail.com>
 * @copyright Copyright (c) 2019, Pavel Usachev
 */

namespace ALevel\Attributes\Model\Attribute\Backend;

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

        if ($value == 'test') {
            throw new LocalizedException(__('Thow exception'));
        }

        return true;
    }
}
<?php

namespace Nvg\AdditionalAttr\Model;

use Magento\Framework\Model\AbstractModel;
use Nvg\AdditionalAttr\Model\ResourceModel\VendorAttr as ResourceModel;

/**
 * Class VendorAttr
 * @package Nvg\AdditionalAttr\Model
 */
class VendorAttr extends AbstractModel
{
    const ID_FIELD         = 'id';
    const NAME             = 'name';
    const DESCRIPTION      = 'description';
    const LOGO             = 'logo';
    const DATE             = 'date';

    public function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name)
    {
        $this->setData(self::NAME,$name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->setData(self::DESCRIPTION,$description);
    }
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @param $logo
     */
    public function setLogo($logo)
    {
        $this->setData(self::LOGO,$logo);
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->getData(self::LOGO);
    }

}
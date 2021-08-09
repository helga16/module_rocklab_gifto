<?php
namespace Nvg\AdditionalAttr\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\Product\View\Description;
use Nvg\AdditionalAttr\Repository\VendorRepository;

class VendorBlock extends Template
{
    /**
     * @var Description
     */
    protected $description;

    /**
     * @var VendorRepository
     */
    protected $repository;

    /**
     * @param Template\Context $context
     * @param Description $description
     * @param VendorRepository $repository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Description $description,
        VendorRepository $repository,
        array $data = []
    )
    {
        $this->description = $description;
        $this->repository = $repository;
        parent::__construct($context, $data);
    }
    public function getVendorCollection($attr) {
        $product = $this->description->getProduct();

       return $this->repository->getByName($product->getAttributeText('vendor_list'))->getFirstItem()->getData($attr);
    }
}

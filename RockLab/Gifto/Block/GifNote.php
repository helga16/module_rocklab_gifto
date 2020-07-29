<?php

namespace RockLab\Gifto\Block;

use RockLab\Gifto\DataProvider\DataProductProvider;
use Magento\Framework\View\Element\Template;

/**
 * Class GifNote
 * @package RockLab\Gifto\Block
 */
class GifNote extends Template
{
    /**
     * @var DataProductProvider
     */
    private $provider;

    /**
     * GifNote constructor.
     * @param DataProductProvider $provider
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        DataProductProvider $provider,
        Template\Context $context,
        array $data = []
    ) {
        $this->provider = $provider;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getProductDataForBlock()
    {
        return $this->provider->getProductDataArray();
    }
}

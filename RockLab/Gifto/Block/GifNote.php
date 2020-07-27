<?php

namespace RockLab\Gifto\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class GifNote
 * @package RockLab\Gifto\Block
 */
class GifNote extends Template
{
    /**
     * GifNote constructor.
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }
}

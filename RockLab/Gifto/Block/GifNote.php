<?php

namespace RockLab\Gifto\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class GifNote
 * @package RockLab\Gifto\Block
 */
class GifNote extends Template
{
    public function __construct(
        Template\Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }
}

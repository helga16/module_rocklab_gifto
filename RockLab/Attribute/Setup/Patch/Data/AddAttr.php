<?php


namespace RockLab\Attribute\Setup\Patch\Data;

use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\StateException;
use Magento\Catalog\Model\Product;
use RockLab\Attribute\Model\Frontend\Dynamic as FrontModel;
use RockLab\Attribute\Model\Backend\Dynamic as BackendModel;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\DB\TransactionFactory;

class AddAttr implements DataPatchInterface
{
    /** @var EavSetupFactory  */
    private $eavSetupFactory;

    /** @var EavSetup */
    private $eavSetup;

    /** @var EavConfig */
    private $eavConfig;
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        EavConfig $eavConfig
    )
    {
        $this->eavSetupFactory              = $eavSetupFactory;
        $this->eavConfig                    = $eavConfig;
    }

    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }
    public function apply ()
    {
        $setup = $this->eavSetupFactory->create();
        $setup->addAttribute(
            Product::ENTITY,
            'dynamic_attribute',
            [
                'group' => 'General', //Means that we add an attribute to the attribute group “General”, which is present in all attribute sets.
                'type' => 'varchar', //varchar means that the values will be stored in the catalog_eav_varchar table.
                'label' => 'dynamic Attribute', //A label of the attribute (that is, how it will be rendered in the backend and on the frontend).
                'input' => 'text',
                'source' => '',
                'frontend' => FrontModel::class, //defines how it should be rendered on the frontend
                'backend' => BackendModel::class, //allows you to perform certain actions when an attribute is loaded or saved. In our example, it will be validation.
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL, // defines the scope of its values (global, website, or store)
                'is_used_in_grid' => true, //is used in admin product grid
                'is_visible_in_grid' => true, // is visibile column in admin product grid
                'is_filterable_in_grid' => true, // is used for filter in admin product grid
                'visible' => true, //A flag that defines whether an attribute should be shown on the “More Information” tab on the frontend
                'is_html_allowed_on_front' => true, //Defines whether an attribute value may contain HTML
                'visible_on_front' => true // A flag that defines whether an attribute should be shown on product listing
            ]
        );

    }
}

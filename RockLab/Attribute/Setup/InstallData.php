<?php

namespace RockLab\Attribute\Setup;

use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection as OptionCollection;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\StateException;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\Downloadable\Model\Product\Type as DownloadableType;


use RockLab\Attribute\Model\Frontend\Test as TestFrontendModel;
use RockLab\Attribute\Model\Backend\Test as TestBackendModel;


class InstallData implements InstallDataInterface
{
    /** @var EavSetupFactory  */
    private $eavSetupFactory;

    /** @var EavSetup */
    private $eavSetup;

    /** @var EavConfig */
    private $eavConfig;

    /** @var OptionCollectionFactory */
    private $attrOptionCollectionFactory;

    /** @var AttributeRepositoryInterface */
    private $attributeRepositoryInterface;

    /** @var OptionCollection */
    private $optionCollection;



    /**
     * InstallData constructor.
     *
     * @param EavSetupFactory $eavSetupFactory\
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        EavConfig $eavConfig,
        AttributeRepositoryInterface $attributeRepository,
        OptionCollectionFactory $attrOptionCollectionFactory
    ) {
        $this->eavSetupFactory              = $eavSetupFactory;
        $this->eavConfig                    = $eavConfig;
        $this->attributeRepositoryInterface = $attributeRepository;
        $this->attrOptionCollectionFactory  = $attrOptionCollectionFactory;
    }

    /** {@inheritDoc} */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->createSimpleTextAttribute($setup);

        $setup->endSetup();
    }


    private function createSimpleTextAttribute(ModuleDataSetupInterface $setup)
    {
        $this->getEavSetup($setup)
             ->addAttribute(
            Product::ENTITY,
            'attraction_highlights',
            [
                'group' => 'General', //Means that we add an attribute to the attribute group “General”, which is present in all attribute sets.
                'type' => 'varchar', //varchar means that the values will be stored in the catalog_eav_varchar table.
                'label' => 'attraction_highlights', //A label of the attribute (that is, how it will be rendered in the backend and on the frontend).
                'input' => 'text',
                'source' => '',
                'frontend' => TestFrontendModel::class, //defines how it should be rendered on the frontend
                'backend' => TestBackendModel::class, //allows you to perform certain actions when an attribute is loaded or saved. In our example, it will be validation.
                'required' => false,
                'sort_order' => 30,
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


    /**
     * @param ModuleDataSetupInterface $setup
     * @return EavSetup
     */
    private function getEavSetup(ModuleDataSetupInterface $setup)
    {
        if (null === $this->eavSetup) {
            $this->eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        }

        return $this->eavSetup;
    }
}

<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

/**
 * @category   Lof
 * @package    Lof_BasePrice
 * @subpackage Setup
 * @copyright  Copyright (c) 2020 Landofcoder (https://landofcoder.com)
 * @link       https://landofcoder.com
 * @author     Landofcoder <landofcoder@gmail.com>
 */
namespace Lof\BasePrice\Setup;

use Lof\BasePrice\Helper\Data;
use Magento\Catalog\Api\ProductAttributeOptionManagementInterface;
use Magento\Catalog\Model\Product;
use Magento\Config\Model\ResourceModel\Config as ResourceConfig;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var ProductAttributeOptionManagementInterface
     */
    protected $productAttributeOptionManagementInterface;

    /**
     * @var ResourceConfig
     */
    protected $configResource;

    /**
     * @var Config
     */
    protected $eavConfig;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * Constructor
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param ProductAttributeOptionManagementInterface $productAttributeOptionManagementInterface
     * @param ResourceConfig $configResource
     * @param Config $eavConfig
     * @param SerializerInterface $serializer
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ProductAttributeOptionManagementInterface $productAttributeOptionManagementInterface,
        ResourceConfig $configResource,
        Config $eavConfig,
        SerializerInterface $serializer
    ){
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productAttributeOptionManagementInterface = $productAttributeOptionManagementInterface;
        $this->configResource = $configResource;
        $this->eavConfig = $eavConfig;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var $eavSetup EavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        if (version_compare($context->getVersion(), "1.0.5", "<")) {
            //Your upgrade script
            $eavSetup->addAttribute(
                Product::ENTITY,
                'baseprice_custom_price',
                [
                    'type' => 'decimal',
                    'label' => 'Custom Base Price',
                    'input' => 'text',
                    'required' => false,
                    'sort_order' => 5,
                    'visible' => true,
                    'note' => 'Leave empty to disable custom base price for this product',
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'group' => 'Base Price',
                    'used_in_product_listing' => true,
                    'visible_on_front' => false
                ]
            );
            // clean cache so that newly created attributes will be loaded from database
        }

        if (version_compare($context->getVersion(), "1.0.6", "<")) {
            //Your upgrade script
            /** @var $eavSetup EavSetup */
            $eavSetup->addAttribute(
                Product::ENTITY,
                'baseprice_delivery',
                [
                    'type' => 'varchar',
                    'label' => 'Delivery Info',
                    'input' => 'text',
                    'required' => false,
                    'sort_order' => 6,
                    'visible' => true,
                    'note' => 'Leave empty to disable delivery for this product',
                    'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'group' => 'Base Price',
                    'used_in_product_listing' => true,
                    'visible_on_front' => false
                ]
            );
            // clean cache so that newly created attributes will be loaded from database
        }
        $this->eavConfig->clear();
    }
}

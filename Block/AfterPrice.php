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
 * @subpackage Block
 * @copyright  Copyright (c) 2020 Landofcoder (https://landofcoder.com)
 * @link       https://landofcoder.com
 * @author     Landofcoder <landofcoder@gmail.com>
 */
namespace Lof\BasePrice\Block;

/**
 * Class AfterPrice
 * @package Lof\BasePrice\Block
 */
class AfterPrice extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Lof\BasePrice\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;

    /**
     * @var string
     */
    protected $_configurablePricesJson;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Lof\BasePrice\Helper\Data $helper
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Catalog\Helper\Product $catalogProduct
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
        \Lof\BasePrice\Helper\Data $helper,
        \Magento\Catalog\Model\Product $product,
		array $data = []
	){
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_helper = $helper;
        $this->_product = $product;
		parent::__construct($context, $data);
	}

    /**
     * Returns the configuration if module is enabled
     *
     * @return mixed
     */
    public function isEnabled()
    {
        $moduleEnabled = $this->_scopeConfig->getValue(
            'baseprice/general/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $productAmount = $this->getProduct()->getData('baseprice_product_amount');
        $customBasePrice = $this->getProduct()->getData('baseprice_custom_price');

        return $moduleEnabled && (!empty($productAmount) || !empty($customBasePrice));
    }

	/**
	 * Retrieve current product
	 *
	 * @return \Magento\Catalog\Model\Product
	 */
	public function getProduct()
	{
        return $this->_product;
	}

    /**
     * Returns the base price information
     */
    public function getBasePrice()
    {
        return $this->_helper->getBasePriceText($this->getProduct());
    }
}

<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   EcomDev
 * @package    EcomDev_ProductPageShipping
 * @copyright  Copyright (c) 2010 Ecommerce Developer Blog (http://www.ecomdev.org)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Estimate model
 *
 */
class EcomDev_ProductPageShipping_Model_Estimate
{
    /**
     * Customer object,
     * if customer isn't logged in it quals to false
     *
     * @var Mage_Customer_Model_Customer|boolean
     */
    protected $_customer = null;

    /**
     * Sales quote object to add products for shipping estimation
     *
     * @var Mage_Sales_Model_Quote
     */
    protected $_quote = null;

    
    /**
     * Product model
     *
     * @var Mage_Catalog_Model_Product
     */
    protected $_product = null;
    
    /**
     * Product model
     *
     * @var Mage_Catalog_Model_Product
     */
    protected $_productSimple = null;

    /**
     * Estimation result
     *
     * @var array
     */
    protected $_result = array();

    /**
     * Delivery address information
     *
     * @var array
     */
    protected $_addressInfo = null;

    /**
     * Set address info for estimation
     *
     * @param array $info
     * @return EcomDev_ProductPageShipping_Model_Estimate
     */
    public function setAddressInfo($info)
    {
        $this->_addressInfo = $info;
        return $this;
    }
    
    /**
     * Set 'ProductSimple' associated with 'ConfigurableProduct' info for estimation
     *
     * @param array $info
     * @return EcomDev_ProductPageShipping_Model_Estimate
     */
	public function setProductSimple($info)
    {
        $this->_productSimple = $info;
        return $this;
    }
    
    /**
     * Retrieve product simple information
     *
     * @return boolean
     */
    public function getProductSimple()
    {
        return $this->_productSimple;
    }

    /**
     * Retrieve address information
     *
     * @return boolean
     */
    public function getAddressInfo()
    {
        return $this->_addressInfo;
    }

    /**
     * Set a product for the estimation
     *
     * @param Mage_Catalog_Model_Product $product
     * @return EcomDev_ProductPageShipping_Model_Estimate
     */
    public function setProduct($product)
    {
        $this->_product = $product;
        return $this;
    }

    /**
     * Retrieve product for the estimation
     */
    public function getProduct()
    {
        //Verify if the product is configurable, since configurable products doesn’t have weight to estimate
		if($this->_product->isConfigurable()){
		   //For convenience, creates a new variable just for our product
			$configurableProduct = $this->_product;
			
			$this->_product = Mage::getModel('catalog/product')->load($this->getProductSimple());
		}
	    
        return $this->_product;
    }

    /**
     * Retrieve shipping rate result
     *
     * @return array|null
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * Retrieve list of shipping rates
     *
     * @param Mage_Catalog_Model_Product $product
     * @param array $info
     * @param array $address
     * @return EcomDev_ProductPageShipping_Model_Estimate
     */
    public function estimate()
    {
        $product = $this->getProduct();
        $addToCartInfo = (array) $product->getAddToCartInfo();
        $addressInfo = (array) $this->getAddressInfo();


        if (!($product instanceof Mage_Catalog_Model_Product) || !$product->getId()) {
            Mage::throwException(
                Mage::helper('ecomdev_productpageshipping')->__('Please specify a valid product')
            );
        }

        if (!isset($addressInfo['country_id'])) {
            Mage::throwException(
                Mage::helper('ecomdev_productpageshipping')->__('Please specify a country')
            );
        }

        if (empty($addressInfo['cart'])) {
            $this->resetQuote();
        }

        $shippingAddress = $this->getQuote()->getShippingAddress();

        $shippingAddress->setCountryId($addressInfo['country_id']);

        if (isset($addressInfo['region_id'])) {
            $shippingAddress->setRegionId($addressInfo['region_id']);
        }

        if (isset($addressInfo['postcode'])) {
            $shippingAddress->setPostcode($addressInfo['postcode']);
        }

        if (isset($addressInfo['region'])) {
            $shippingAddress->setRegion($addressInfo['region']);
        }

        if (isset($addressInfo['city'])) {
            $shippingAddress->setCity($addressInfo['city']);
        }

        $shippingAddress->setCollectShippingRates(true);

        if (isset($addressInfo['coupon_code'])) {
            $this->getQuote()->setCouponCode($addressInfo['coupon_code']);
        }

        $request = new Varien_Object($addToCartInfo);

        if ($product->getStockItem()) {
            $minimumQty = $product->getStockItem()->getMinSaleQty();
            if($minimumQty > 0 && $request->getQty() < $minimumQty){
                $request->setQty($minimumQty);
            }
        }

        $result = $this->getQuote()->addProduct($product, $request);

        if (is_string($result)) {
            Mage::throwException($result);
        }

        Mage::dispatchEvent('checkout_cart_product_add_after',
                            array('quote_item' => $result, 'product' => $product));

        $this->getQuote()->collectTotals();
        $this->_result = $shippingAddress->getGroupedAllShippingRates();
        return $this;
    }

    /**
     * Retrieve sales quote object
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        if ($this->_quote === null) {
            $addressInfo = $this->getAddressInfo();
            if (!empty($addressInfo['cart'])) {
                $quote = Mage::getSingleton('checkout/session')->getQuote();
            } else {
                $quote = Mage::getModel('sales/quote');
            }

            $this->_quote = $quote;
        }

        return $this->_quote;
    }

    /**
     * Reset quote object
     *
     * @return EcomDev_ProductPageShipping_Model_Estimate
     */
    public function resetQuote()
    {
        $this->getQuote()->removeAllAddresses();

        if ($this->getCustomer()) {
            $this->getQuote()->setCustomer($this->getCustomer());
        }

        return $this;
    }

    /**
     * Retrieve currently logged in customer,
     * if customer isn't logged it returns false
     *
     * @return Mage_Customer_Model_Customer|boolean
     */
    public function getCustomer()
    {
        if ($this->_customer === null) {
            $customerSession = Mage::getSingleton('customer/session');
            if ($customerSession->isLoggedIn()) {
                $this->_customer = $customerSession->getCustomer();
            } else {
                $this->_customer = false;
            }
        }

        return $this->_customer;
    }
}

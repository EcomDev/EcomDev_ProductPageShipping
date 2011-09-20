<?php
/**
 * Shipping Estimate extension
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
 * @copyright  Copyright (c) 2011 EcomDev BV (http://www.ecomdev.org)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Ivan Chepurnyi <ivan.chepurnyi@ecomdev.org>
 */

/**
 * Abstract block for estimate module
 *
 */
abstract class EcomDev_ProductPageShipping_Block_Estimate_Abstract extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * Estimate model
     *
     * @var EcomDev_ProductPageShipping_Model_Estimate
     */
    protected $_estimate = null;


    /**
     * Config model
     *
     * @var EcomDev_ProductPageShipping_Model_Config
     */
    protected $_config = null;


    /**
     * Module session model
     *
     * @var $_session EcomDev_ProductPageShipping_Model_Session
     */
    protected $_session = null;

    /**
     * List of carriers
     *
     * @var array
     */
    protected $_carriers = null;

    /**
     * Retrieve estimate data model
     *
     * @return EcomDev_ProductPageShipping_Model_Estimate
     */
    public function getEstimate()
    {
        if ($this->_estimate === null) {
            $this->_estimate = Mage::getSingleton('ecomdev_productpageshipping/estimate');
        }

        return $this->_estimate;
    }

    /**
     * Retrieve configuration model for module
     *
     * @return EcomDev_ProductPageShipping_Model_Config
     */
    public function getConfig()
    {
        if ($this->_config === null) {
            $this->_config = Mage::getSingleton('ecomdev_productpageshipping/config');
        }

        return $this->_config;
    }

    /**
     * Retrieve session model object
     *
     * @return EcomDev_ProductPageShipping_Model_Session
     */
    public function getSession()
    {
        if ($this->_session === null) {
            $this->_session = Mage::getSingleton('ecomdev_productpageshipping/session');
        }

        return $this->_session;
    }

    /**
     * Check is enabled functionality
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->getConfig()->isEnabled() && !$this->getProduct()->isVirtual();
    }
}

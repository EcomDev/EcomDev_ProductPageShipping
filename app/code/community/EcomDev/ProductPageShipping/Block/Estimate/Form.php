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
 * Estimte form block
 *
 * Displays fields to enter for estimation of the shipping rate
 *
 */
class EcomDev_ProductPageShipping_Block_Estimate_Form extends EcomDev_ProductPageShipping_Block_Estimate_Abstract
{
    /**
     * Check is field visible in the form
     *
     * If config model have method like useFieldName,
     * method uses it to check field visibility, otherwise returns true
     *
     * @param string $fieldName
     * @return boolean
     */
    public function isFieldVisible($fieldName)
    {
        if (method_exists($this->getConfig(), 'use' . uc_words($fieldName, ''))) {
            return $this->getConfig()->{'use' . uc_words($fieldName, '')}();
        }

        return true;
    }

    /**
     * Retrieve field value
     *
     * @param string $fieldName
     * @return mixed
     */
    public function getFieldValue($fieldName)
    {
        $values = $this->getSession()->getFormValues();
        if (isset($values[$fieldName])) {
            return $values[$fieldName];
        }

        return null;
    }

    /**
     * Retireve url for estimation form submitting
     *
     * @return string
     */
    public function getEstimateUrl()
    {
        return $this->getUrl('ecomdev_productpageshipping/estimate/estimate', array('_current' => true));
    }

    /**
     * Retrieve available carriers
     *
     * @return array
     */
    public function getCarriers()
    {
        if ($this->_carriers === null) {
            $this->_carriers = Mage::getModel('shipping/config')->getActiveCarriers();
        }

        return $this->_carriers;
    }

    /**
     * Check is field required or not
     *
     * @param string $fieldName
     * @return boolean
     */
    public function isFieldRequired($fieldName)
    {
        $methodMap = array(
            'region' => 'isStateProvinceRequired', // Checks is region required
            'city'   => 'isCityRequired', // Checks is city required
            'postcode' => 'isZipCodeRequired' // Checks is postal code required
        );

        if (!isset($methodMap[$fieldName])) {
            return false;
        }

        $method = $methodMap[$fieldName];
        foreach ($this->getCarriers() as $carrier) {
            if (method_exists($carrier, $method) && $carrier->$method()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check is required usage of shopping cart items
     * in shipping estimate
     *
     * @return boolean
     */
    public function useShoppingCart()
    {
        if ($this->getSession()->getFormValues() === null ||
            !$this->isFieldVisible('cart')) {
            return $this->getConfig()->useCartDefault();
        }

        return $this->getFieldValue('cart');
    }
}

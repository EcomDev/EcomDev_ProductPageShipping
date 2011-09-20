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
 * Estiamtion results block
 *
 *
 */
class EcomDev_ProductPageShipping_Block_Estimate_Result extends EcomDev_ProductPageShipping_Block_Estimate_Abstract
{
    /**
     * Retrieves result from estimate model
     *
     * @return array|null
     */
    public function getResult()
    {
        return $this->getEstimate()->getResult();
    }

    /**
     * Check result existance
     *
     * @return boolean
     */
    public function hasResult()
    {
        return $this->getResult() !== null;
    }

    /**
     * Retrieve carrier name for shipping rate group
     *
     * @param string $code
     * @return string|null
     */
    public function getCarrierName($code)
    {
        $carrier = Mage::getSingleton('shipping/config')->getCarrierInstance($code);
        if ($carrier) {
            return $carrier->getConfigData('title');
        }

        return null;
    }

    /**
     * Retrieve shipping price for current address and rate
     *
     * @param decimal $price
     * @param boolean $flag show include tax price flag
     * @return string
     */
    public function getShippingPrice($price, $flag)
    {
        return $this->formatPrice(
            $this->helper('tax')->getShippingPrice(
                $price,
                $flag,
                $this->getEstimate()
                    ->getQuote()
                    ->getShippingAddress()
           )
        );
    }

    /**
     * Format price value depends on store settings
     *
     * @param decimal $price
     * @return string
     */
    public function formatPrice($price)
    {
        return $this->getEstimate()
            ->getQuote()
            ->getStore()
            ->convertPrice($price, true);
    }
}

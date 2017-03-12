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

require_once 'app/code/core/Mage/Catalog/controllers/ProductController.php';

/**
 * Estimate shiping controller, passes the request to estimate model
 * Extended from product controller for supporting of full product initialization
 *
 */
class EcomDev_ProductPageShipping_EstimateController extends Mage_Catalog_ProductController
{
    /**
     * Estimate action
     *
     * Initializes the product and passes data to estimate model in block
     */
    public function estimateAction()
    {
        $product = $this->_initProduct();
        $this->loadLayout(false);
        $block = $this->getLayout()->getBlock('shipping.estimate.result');
        if ($block) {
            $estimate = $block->getEstimate();
            $product->setAddToCartInfo((array) $this->getRequest()->getPost());
            $estimate->setProduct($product);
            $addressInfo = $this->getRequest()->getPost('estimate');
            $productSimple = $this->getRequest()->getPost('product_simple_id');
            $estimate->setAddressInfo((array) $addressInfo);
            $estimate->setProductSimple($productSimple);
            $block->getSession()->setFormValues($addressInfo);
            try {
                $estimate->estimate();
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('catalog/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('catalog/session')->addError(
                    Mage::helper('ecomdev_productpageshipping')->__('There was an error during processing your shipping request')
                );
            }
        }
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }
}

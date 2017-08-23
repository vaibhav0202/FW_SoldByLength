<?php
/**
 * @category    FW
 * @package     FW_SoldByLength
 * @copyright   Copyright (c) 2015 F+W (http://www.fwcommunity.com)
 * @author      J.P. Daniel <jp.daniel@fwcommunity.com>
 */
class FW_SoldByLength_Model_Observer
{
    /**
     *
	 *
     * @param Varien_Event_Observer $observer
     */
	public function onCheckoutCartAdvancedAdd(Varien_Event_Observer $observer)
	{
		$request = $observer->getControllerAction()->getRequest();
		$params = $request->getParams();
		foreach ($params['items'] as &$item) {
			/** @var Mage_Catalog_Model_Product $product */
			$product = Mage::getModel('catalog/product');     // Get the product model
			$productID = $product->getIdBySku($item['sku']);  // Get the product ID
			if (empty($productID)) continue;  // Make sure the sku returned a product ID
			$product->load($productID);
			$minSaleQty = $product->getStockItem()->getMinSaleQty();
			if ($item['qty'] < $minSaleQty) $item['qty'] = $minSaleQty;
		}
		$request->setParams($params);
	}
}

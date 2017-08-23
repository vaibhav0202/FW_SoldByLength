<?php
/**
 * @category    FW
 * @package     FW_SoldByLength
 * @copyright   Copyright (c) 2012 F+W Media, Inc. (http://www.fwmedia.com)
 * @author		J.P. Daniel <jp.daniel@fwmedia.com>
 */
class FW_SoldByLength_Model_Stock_Item extends Mage_CatalogInventory_Model_Stock_Item
{
	/**
	 * Checking quote item quantity
	 *
	 * Second parameter of this method specifies quantity of this product in whole shopping cart
	 * which should be checked for stock availability
	 *
	 * @param mixed $qty quantity of this item (item qty x parent item qty)
	 * @param mixed $summaryQty quantity of this product
	 * @param mixed $origQty original qty of item (not multiplied on parent item qty)
	 * @return Varien_Object
	 */
	public function checkQuoteItemQty($qty, $summaryQty, $origQty = 0)
	{
		$result = parent::checkQuoteItemQty($qty, $summaryQty, $origQty);		// Get default core result
		
		// Get a product model that should have custom attribs in it
		$this->setProduct(Mage::getModel('catalog/product')->load($this->getProduct()->getId()));

		if ($this->getProduct()->getSoldByLength()) {

			/** @var $_helper Mage_CatalogInventory_Helper_Data */
			$_helper = Mage::helper('cataloginventory');
			
			if ($result->getHasError()) {	// Result has error when minimum/maximum message is returned 
				if (strpos($result->getMessage(), 'minimum') !== FALSE) {	// minimum message was returned
					$result->setMessage(		// Rewrite message with sold by length messaging
						$_helper->__('The minimum quantity allowed for purchase is %s.', 
							Mage::helper('fw_soldbylength')->getYards($this->getMinSaleQty())
						)
					);
					return $result;		// Return the message
				}	
				if (strpos($result->getMessage(), 'maximum') !== FALSE) {	// maximum message was returned
					$result->setMessage(		// Rewrite message with sold be length messaging
						$_helper->__('The maximum quantity allowed for purchase is %s.', 
							Mage::helper('fw_soldbylength')->getYards($this->getMaxSaleQty())
						)
					);
	        		return $result;		// Return the message
				}				
			}	        
			if ($backorderQty = $result->getItemBackorders()) {		// Check if there is backorders
				if (!$this->getIsChildItem()) {
					$result->setMessage(		// Rewrite message with sold by length messaging
						$_helper->__('This product is not available in the requested quantity. %s of the items will be backordered.', 
							Mage::helper('fw_soldbylength')->getYards($backorderQty)
						)
					);
				} else {
					$result->setMessage(		// Rewrite message with sold by length messaging
						$_helper->__('"%s" is not available in the requested quantity. %s of the items will be backordered.',
							$this->getProductName(),
							Mage::helper('fw_soldbylength')->getYards($backorderQty)
						)
					);
				}
			}
		}
		return $result;		// Return the message
	}
}

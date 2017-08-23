<?php

/**
 * SKU failed information Block
 *
 * @category    FW
 * @package     FW_SoldByLength
 * @copyright   Copyright (c) 2015 F+W (http://www.fwcommunity.com)
 * @author      J.P. Daniel <jp.daniel@fwcommunity.com>
 */
class FW_SoldByLength_Block_Sku_Products_Info extends Enterprise_Checkout_Block_Sku_Products_Info
{
    /**
     * Retrieve item's message
     *
     * @return string
     */
    public function getMessage()
    {
        /** @var Mage_Sales_Model_Quote_Item $item */
        $item = $this->getItem();

        if (!$item->getSoldByLength()) return parent::getMessage(); // Use core functionality if not sold by length

        /** @var FW_SoldByLength_Helper_Data $soldByLengthHelper */
        $soldByLengthHelper = Mage::helper('fw_soldbylength');

        switch ($item->getCode()) {
            case Enterprise_Checkout_Helper_Data::ADD_ITEM_STATUS_FAILED_QTY_ALLOWED:
                $message = $this->_getHelper()->getMessage(
                    Enterprise_Checkout_Helper_Data::ADD_ITEM_STATUS_FAILED_QTY_ALLOWED
                );
                $message .= '<br/>' . $this->__("Only %s%g%s left in stock", '<span class="sku-failed-qty" id="sku-stock-failed-' . $this->getItem()->getId() . '">', $soldByLengthHelper->getYards($item->getQtyMaxAllowed() * 1), '</span>');
                return $message;
            case Enterprise_Checkout_Helper_Data::ADD_ITEM_STATUS_FAILED_QTY_ALLOWED_IN_CART:
                $message = $this->_getHelper()->getMessage(
                    Enterprise_Checkout_Helper_Data::ADD_ITEM_STATUS_FAILED_QTY_ALLOWED_IN_CART
                );
                $message .= '<br/>';
                if ($item->getQtyMaxAllowed()) {
                    $message .= $this->__('The maximum quantity allowed for purchase is %s.', '<span class="sku-failed-qty" id="sku-stock-failed-' . $item->getId() . '">' . $soldByLengthHelper->getYards($item->getQtyMaxAllowed() * 1) . '</span>');
                } else if ($item->getQtyMinAllowed()) {
                    $message .= $this->__('The minimum quantity allowed for purchase is %s.', '<span class="sku-failed-qty" id="sku-stock-failed-' . $item->getId() . '">' . $soldByLengthHelper->getYards($item->getQtyMinAllowed() * 1) . '</span>');
                }
                return $message;
            default:
                return parent::getMessage();    // Default to core functionality
        }
    }
}

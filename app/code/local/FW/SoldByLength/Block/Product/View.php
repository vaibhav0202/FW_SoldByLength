<?php
class FW_SoldByLength_Block_Product_View extends Mage_Catalog_Block_Product_View
{
    /**
     * Get JSON encripted configuration array which can be used for JS dynamic
     * price calculation depending on product options
     *
     * @return string
     */
    public function getJsonConfig()
    {
        $config = array();
        if (!$this->hasOptions()) {
            return Mage::helper('core')->jsonEncode($config);
        }

        $_request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false);
        $_request->setProductClassId($this->getProduct()->getTaxClassId());
        $defaultTax = Mage::getSingleton('tax/calculation')->getRate($_request);

        $_request = Mage::getSingleton('tax/calculation')->getRateRequest();
        $_request->setProductClassId($this->getProduct()->getTaxClassId());
        $currentTax = Mage::getSingleton('tax/calculation')->getRate($_request);
        
        //SOLD BY LENGTH CONVERSION on FINAL PRICE
        if($this->getProduct()->getSoldByLength() == 1){
        	$_regularPrice = $this->getProduct()->getPrice()*8;
        	$_finalPrice = $this->getProduct()->getFinalPrice()*8;
        	$_priceInclTax = Mage::helper('tax')->getPrice($this->getProduct(), $_finalPrice, true);
        	$_priceExclTax = Mage::helper('tax')->getPrice($this->getProduct(), $_finalPrice);
        	$priceFormat = Mage::app()->getLocale()->getJsPriceFormat().' per Yard';
        }else{
        	$_regularPrice = $this->getProduct()->getPrice();
        	$_finalPrice = $this->getProduct()->getFinalPrice();
        	$_priceInclTax = Mage::helper('tax')->getPrice($this->getProduct(), $_finalPrice, true);
        	$_priceExclTax = Mage::helper('tax')->getPrice($this->getProduct(), $_finalPrice);
        	$priceFormat = Mage::app()->getLocale()->getJsPriceFormat();
        }
        

        $config = array(
            'productId'           => $this->getProduct()->getId(),
            'priceFormat'         => $priceFormat,
            'includeTax'          => Mage::helper('tax')->priceIncludesTax() ? 'true' : 'false',
            'showIncludeTax'      => Mage::helper('tax')->displayPriceIncludingTax(),
            'showBothPrices'      => Mage::helper('tax')->displayBothPrices(),
            'productPrice'        => Mage::helper('core')->currency($_finalPrice, false, false),
            'productOldPrice'     => Mage::helper('core')->currency($_regularPrice, false, false),
            'priceInclTax'        => Mage::helper('core')->currency($_priceInclTax, false, false),
            'priceExclTax'        => Mage::helper('core')->currency($_priceExclTax, false, false),
            /**
             * @var skipCalculate
             * @deprecated after 1.5.1.0
             */
            'skipCalculate'       => ($_priceExclTax != $_priceInclTax ? 0 : 1),
            'defaultTax'          => $defaultTax,
            'currentTax'          => $currentTax,
            'idSuffix'            => '_clone',
            'oldPlusDisposition'  => 0,
            'plusDisposition'     => 0,
            'oldMinusDisposition' => 0,
            'minusDisposition'    => 0,
        );

        $responseObject = new Varien_Object();
        Mage::dispatchEvent('catalog_product_view_config', array('response_object'=>$responseObject));
        if (is_array($responseObject->getAdditionalOptions())) {
            foreach ($responseObject->getAdditionalOptions() as $option=>$value) {
                $config[$option] = $value;
            }
        }

        return Mage::helper('core')->jsonEncode($config);
    }
}

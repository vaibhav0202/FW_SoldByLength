<?php

/**
 * @category    FW
 * @package     FW_SoldByLength
 * @copyright   Copyright (c) 2015 F+W (http://www.fwcommunity.com)
 * @author      J.P. Daniel <jp.daniel@fwcommunity.com>
 */
class FW_SoldByLength_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Converts a quantity to a Sold By Length string
     *
     * @param $qty           int
     * @param $forceFraction boolean
     * @param $yards         boolean
     *
     * @return string
     */
    public function convertQty($qty, $forceFraction = false, $yards = false)
    {
        $return = '';
        if ($qty <= 0) return $forceFraction ? '0/8' : '0';        // Make sure there is an actual quantity
        if ($qty >= 8) $return = intval($qty / 8);
        $fraction = $qty % 8;
        if ($fraction != 0) {
            $divisor = 1;
            while ($divisor <= 4 && $qty % ($divisor * 2) == 0) $divisor *= 2;
            $return .= ($return ? ' and ' : '') . $fraction / $divisor . '/' . 8 / $divisor;
        }
        if ($yards) $return .= ' yard' . ($qty != 8 ? 's' : '');
        return $return;
    }

    /**
     * Convert qty to Sold By Length fraction
     *
     * @param $qty int
     *
     * @return string
     */
    public function getFraction($qty)
    {
        return $this->convertQty($qty, true);
    }

    /**
     * Convert qty to Sold By Length yards
     *
     * @param $qty int
     *
     * @return string
     */
    public function getYards($qty)
    {
        return $this->convertQty($qty, false, true);
    }
}
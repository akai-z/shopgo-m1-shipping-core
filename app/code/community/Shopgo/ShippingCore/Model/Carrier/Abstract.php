<?php
/**
 * ShopGo
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @copyright   Copyright (c) 2014 Shopgo. (http://www.shopgo.me)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Abstract carrier model
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
abstract class Shopgo_ShippingCore_Model_Carrier_Abstract extends Mage_Core_Model_Abstract
{
    /**
     * Process shipment tracking info
     *
     * @param array $trackingInfo
     * @return array
     */
    public function processTrackingInfo($trackingInfo)
    {
        if (count($trackingInfo) == 1) {
            $trackingInfo = $trackingInfo[0];
        } else {
            foreach ($trackingInfo as $i => $ti) {
                $gti = array();

                if (isset($trackingInfo[$i + 1])) {
                    $gti = array_merge($ti, $trackingInfo[$i + 1]);
                }

                if (!empty($gti)) {
                    $trackingInfo = array_merge($trackingInfo, $gti);
                }
            }
        }

        return $trackingInfo;
    }

    /**
     * Process shipment tracking info by track ID
     *
     * @param array $trackingInfo
     * @return array
     */
    public function processTrackingInfoByTrackId($trackingInfo)
    {
        return array($trackingInfo);
    }
}

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
 * Adminhtml shipping core controller
 *
 * @category    Shopgo
 * @package     Shopgo_ShippingCore
 * @author      Ammar <ammar@shopgo.me>
 */
class Shopgo_ShippingCore_Adminhtml_Shopgo_ShippingcoreController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Set dimensional weight attributes
     *
     * @return string
     */
    public function setDwAttributesAction()
    {
        $params = $this->getRequest()->getPost();
        $response = Mage::app()->getResponse()
            ->setHeader('content-type', 'application/json; charset=utf-8');

        $result = array(
            'status' => 0,
            'description' => $this->__('Unspecified error')
        );

        $_result = Mage::getModel('shippingcore/dwa')->setDwAttributes(
            $params['attribute_set'],
            $params['attribute_set_group']
        );

        if ($_result) {
            $result = array(
                'status' => 1,
                'description' => ''
            );
        } else {
            $result['description'] = $this->__('Could not set dimensional weight attributes! Please try to set them manually.');
        }

        $response->setBody(json_encode($result));
    }

    /**
     * Get dimensional weight attribute set groups
     *
     * @return string
     */
    public function getDwaSetGroupsAction()
    {
        $params = $this->getRequest()->getPost();
        $response = Mage::app()->getResponse()
            ->setHeader('content-type', 'application/json; charset=utf-8');

        $result = array(
            'status' => 0,
            'description' => $this->__('Unspecified error')
        );

        if (!isset($params['attribute_set'])) {
            $result['description'] = '';
            $response->setBody(json_encode($result));

            return;
        }

        $groups = Mage::getModel('shippingcore/system_config_source_attributesetgroup')
            ->toOptionArray($params['attribute_set']);

        if (!empty($groups)) {
            $result = array(
                'status' => 1,
                'description' => '',
                'data' => $groups
            );
        } else {
            $result['description'] = $this->__('Could not get the groups for the specified attribute set!');
        }

        $response->setBody(json_encode($result));
    }
}

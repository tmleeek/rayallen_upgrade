<?php
/**
 * @category   Amconnector
 * @package    Kensium_Amconnector
 * @copyright  Copyright (c) 2016 Kensium Solution Pvt.Ltd. (http://www.kensiumsolutions.com/)
 */

namespace Kensium\Amconnector\Model\System\Config\Source;

class Direction implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label' => 'Acumatica to Magento'),
            array('value' => '2', 'label' => 'Magento to Acumatica'),
            array('value' => '3', 'label' => 'Bi-Directional')
        );
    }
}

<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomerBalance\Test\Block\Adminhtml\Customer\Edit\Tab;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Client\Element\SimpleElement;
use Magento\Backend\Test\Block\Widget\Tab as AbstractTab;

/**
 * Store credit tab.
 */
class Tab extends AbstractTab
{
    /**
     * Selector for active tab.
     *
     * @var string
     */
    protected $activeTab = './/div[@data-bind="visible: active" and not(contains(@style,"display: none"))]';

    /**
     * Store credit balance XPath.
     *
     * @var string
     */
    protected $storeCreditBalance = '//*[@id="balanceGrid"]//td[@data-column="amount"]';

    /**
     * Field set.
     *
     * @var string
     */
    protected $fieldSetStoreCredit = '#_customerbalancestorecreidt_fieldset';

    /**
     * Fill data to fields on tab.
     *
     * @param array $fields
     * @param SimpleElement|null $element
     * @return $this
     */
    public function fillFormTab(array $fields, SimpleElement $element = null)
    {
        $this->waitForElementVisible($this->fieldSetStoreCredit);
        $data = $this->dataMapping($fields);
        $this->_fill($data, $element);

        return $this;
    }

    /**
     * Check store credit balance history.
     *
     * @param string $value
     * @return bool
     */
    public function isStoreCreditBalance($value)
    {
        $storeCreditBalance = $this->activeTab . $this->storeCreditBalance;
        $this->waitForElementVisible($storeCreditBalance, Locator::SELECTOR_XPATH);
        return $this->_rootElement->find(
            $storeCreditBalance . '[contains(.,"' . $value . '")]',
            Locator::SELECTOR_XPATH
        )->isVisible();
    }
}
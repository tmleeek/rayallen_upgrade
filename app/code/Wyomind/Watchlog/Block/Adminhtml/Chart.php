<?php

/**
 * Copyright © 2016 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\Watchlog\Block\Adminhtml;

class Chart extends \Magento\Backend\Block\Template
{

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_datetime = null;
    
    /**
     * @var \Wyomind\Watchlog\Model\ResourceModel\Attempts\CollectionFactory 
     */
    protected $_attemptsCollectionFactory = null;
    
    /**
     * @var \Wyomind\Watchlog\Helper\Data
     */
    public $watchlogHelper = null;

    /**
     * Block contructor
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $datetime
     * @param \Wyomind\Watchlog\Model\ResourceModel\Attempts\CollectionFactory $attemptsCollectionFactory
     * @param \Wyomind\Watchlog\Helper\Data $watchlogHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        \Wyomind\Watchlog\Model\ResourceModel\Attempts\CollectionFactory $attemptsCollectionFactory,
        \Wyomind\Watchlog\Helper\Data $watchlogHelper,
        array $data = []
    ) {
        $this->_datetime = $datetime;
        $this->_attemptsCollectionFactory = $attemptsCollectionFactory;
        $this->watchlogHelper = $watchlogHelper;
        parent::__construct($context, $data);
        $this->setTemplate('chart.phtml');
    }

    /**
     * Get attempts data for the last X days (X is a value from the configuration of the extension)
     * @return array A list of javascript dates, the number of success and failure
     */
    public function getChartDataSummaryMonth()
    {
        $data = [];
        $headers = [__('Date'), __('Success'), __('Failed')];

        $data[] = $headers;

        $tmpData = [];

        $nbDays = $this->watchlogHelper->getDefaultConfig("watchlog/settings/history");
        $currentTimestamp = $this->_datetime->gmtTimestamp()+$this->_datetime->getGmtOffset();
        $yestermonthTimestamp = $currentTimestamp - ($nbDays-1) * 24 * 60 * 60;
        while ($yestermonthTimestamp <= $currentTimestamp) {
            $key = $this->_datetime->date('Y-m-d', $yestermonthTimestamp);
            $tmpData[$key] = [\Wyomind\Watchlog\Helper\Data::FAILURE => 0, \Wyomind\Watchlog\Helper\Data::SUCCESS => 0];
            $yestermonthTimestamp += 24 * 60 * 60;
        }

        $collection = $this->_attemptsCollectionFactory->create()->getSummaryMonth();
        foreach ($collection as $entry) {
            $key = $this->_datetime->date('Y-m-d', strtotime($entry->getDate())+$this->_datetime->getGmtOffset());
            if (!isset($tmpData[$key])) {
                $tmpData[$key] = [\Wyomind\Watchlog\Helper\Data::FAILURE => 0, \Wyomind\Watchlog\Helper\Data::SUCCESS => 0];
            }
            $tmpData[$key][$entry->getStatus()] = $entry->getNb();
        }
        ksort($tmpData);
        foreach ($tmpData as $date => $entry) {
            $data[] = ["#new Date('" . $date . "')#", (int) $entry[\Wyomind\Watchlog\Helper\Data::SUCCESS], (int) $entry[\Wyomind\Watchlog\Helper\Data::FAILURE]];
        }

        return $data;
    }

    /**
     * Get attempts data for the last 24 hours
     * @return array A list of javascript dates, the number of success and failure
     */
    public function getChartDataSummaryDay()
    {
        $data = [];
        $headers = [__('Date'), __('Success'), __('Failed')];

        $data[] = $headers;

        $tmpData = [];

        $currentTimestamp = $this->_datetime->gmtTimestamp()+$this->_datetime->getGmtOffset();
        $yesterdayTimestamp = $currentTimestamp - 23 * 60 * 60;
        while ($yesterdayTimestamp <= $currentTimestamp) {
            $key = $this->_datetime->date('M d, Y H:00:00', $yesterdayTimestamp);
            $tmpData[$key] = [\Wyomind\Watchlog\Helper\Data::FAILURE => 0, \Wyomind\Watchlog\Helper\Data::SUCCESS => 0];
            $yesterdayTimestamp += 60 * 60;
        }

        $collection = $this->_attemptsCollectionFactory->create()->getSummaryDay();
        foreach ($collection as $entry) {
            $key = $this->_datetime->date('M d, Y H:00:00', strtotime($entry->getDate())+$this->_datetime->getGmtOffset());
            if (!isset($tmpData[$key])) {
                $tmpData[$key] = [\Wyomind\Watchlog\Helper\Data::FAILURE => 0, \Wyomind\Watchlog\Helper\Data::SUCCESS => 0];
            }
            $tmpData[$key][$entry->getStatus()] = $entry->getNb();
        }

        foreach ($tmpData as $date => $entry) {
            $data[] = ["#new Date('" . $date . "')#", (int) $entry[\Wyomind\Watchlog\Helper\Data::SUCCESS], (int) $entry[\Wyomind\Watchlog\Helper\Data::FAILURE]];
        }
        return $data;
    }
}

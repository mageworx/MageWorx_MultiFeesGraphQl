<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver\Cart;

use Magento\Framework\GraphQl\Query\EnumLookup;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use MageWorx\MultiFees\Api\Data\FeeInterface;

abstract class AbstractAppliedFees implements ResolverInterface
{
    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var EnumLookup
     */
    protected $enumLookup;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param EnumLookup $enumLookup
     */
    public function __construct(PriceCurrencyInterface $priceCurrency, EnumLookup $enumLookup)
    {
        $this->priceCurrency = $priceCurrency;
        $this->enumLookup    = $enumLookup;
    }

    /**
     * @param array $feeData
     * @param string $currencyCode
     * @return array
     * @throws \Magento\Framework\Exception\RuntimeException
     */
    protected function getPreparedFeeData(array $feeData, string $currencyCode): array
    {
        return [
            'id'               => (int)$feeData[FeeInterface::FEE_ID],
            'title'            => $feeData[FeeInterface::TITLE],
            'type'             => $this->enumLookup->getEnumValueFromField(
                'MwFeeTypeEnum',
                $feeData[FeeInterface::TYPE]
            ),
            'customer_message' => empty($feeData['message']) ? '' : $feeData['message'],
            'date'             => empty($feeData['date']) ? '' : $feeData['date'],
            'price'            => [
                'value'    => $this->priceCurrency->roundPrice((float)$feeData['price']),
                'currency' => $currencyCode
            ],
            'tax'              => [
                'value'    => $this->priceCurrency->roundPrice((float)$feeData['tax']),
                'currency' => $currencyCode
            ],
            'options'          => $this->getPreparedOptions($feeData['options'], $currencyCode)
        ];
    }

    /**
     * @param array $options
     * @param string $currencyCode
     * @return array
     */
    protected function getPreparedOptions(array $options, string $currencyCode): array
    {
        $data = [];

        foreach ($options as $id => $optionData) {
            $data[] = [
                'id'    => (int)$id,
                'title' => $optionData['title'],
                'price' => [
                    'value'    => $this->priceCurrency->roundPrice((float)$optionData['price']),
                    'currency' => $currencyCode
                ],
                'tax'   => [
                    'value'    => $this->priceCurrency->roundPrice((float)$optionData['tax']),
                    'currency' => $currencyCode
                ]
            ];
        }

        return $data;
    }
}

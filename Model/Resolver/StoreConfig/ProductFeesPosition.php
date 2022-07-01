<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver\StoreConfig;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use MageWorx\MultiFees\Helper\Data;
use Magento\Framework\GraphQl\Query\EnumLookup;

class ProductFeesPosition implements ResolverInterface
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var EnumLookup
     */
    protected $enumLookup;

    /**
     * @param Data $helperData
     * @param EnumLookup $enumLookup
     */
    public function __construct(Data $helperData, EnumLookup $enumLookup)
    {
        $this->helperData = $helperData;
        $this->enumLookup = $enumLookup;
    }

    /**
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return string
     * @throws \Magento\Framework\Exception\RuntimeException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $store = $context->getExtensionAttributes()->getStore();
        $storeId = (int)$store->getId();

        return $this->helperData->getProductFeePosition($storeId)
            ? $this->enumLookup->getEnumValueFromField(
                'MwHiddenFeePositionEnum',
                $this->helperData->getProductFeePosition($storeId)
            ) : null;
    }
}

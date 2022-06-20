<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MageWorx\MultiFeesGraphQl\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use MageWorx\MultiFees\Api\Data\FeeInterface;

abstract class AbstractFee implements ResolverInterface
{
    /**
     * @param int $quoteId
     * @return array
     */
    abstract protected function getFees(int $quoteId): array;

    /**
     * @param array $fees
     * @return array
     */
    abstract protected function getData(array $fees): array;

    /**
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return null
     * @throws LocalizedException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        $quote = $value['model'];
        $fees  = $this->getFees((int)$quote->getId());

        if (empty($fees)) {
            return null;
        }

        $data = $this->getData($fees);

        return $data ?: null;
    }

    /**
     * @param array $feeData
     * @return array
     */
    protected function getPreparedFeeData(array $feeData): array
    {
        $options = $this->getPreparedOptions($feeData);

        return [
            'id'                          => (int)$feeData[FeeInterface::FEE_ID],
            'title'                       => $feeData[FeeInterface::TITLE],
            'description'                 => $feeData[FeeInterface::DESCRIPTION],
            'input_type'                  => $feeData[FeeInterface::INPUT_TYPE],
            'is_required'                 => (bool)$feeData[FeeInterface::REQUIRED],
            'is_enabled_customer_message' => (bool)$feeData[FeeInterface::ENABLE_CUSTOMER_MESSAGE],
            'customer_message_title'      => $feeData['customer_message_title'],
            'is_enabled_date_field'       => (bool)$feeData[FeeInterface::ENABLE_DATA_FIELD],
            'date_field_title'            => $feeData['date_field_title'],
            'sort_order'                  => (int)$feeData[FeeInterface::SORT_ORDER],
            'options'                     => $options ?: null
        ];
    }

    /**
     * @param array $feeData
     * @return array
     */
    protected function getPreparedOptions(array $feeData): array
    {
        $options = [];

        if (!empty($feeData['options'])) {
            foreach ($feeData['options'] as $option) {
                $options[] = [
                    'id'          => (int)$option['fee_option_id'],
                    'field_label' => $option['field_label'],
                    'is_default'  => (bool)$option['is_default'],
                    'position'    => (int)$option['position'],
                    'title'       => $option['title'],
                    'price_type'  => $option['price_type'],
                    'price'       => (float)$option['price']
                ];
            }
        }

        return $options;
    }
}

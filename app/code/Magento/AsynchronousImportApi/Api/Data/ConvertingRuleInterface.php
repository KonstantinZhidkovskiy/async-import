<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImportApi\Api\Data;

/**
 * Represents rule of converting data fields
 *
 * @api
 */
interface ConvertingRuleInterface
{
    public const NAME = 'name';
    public const PARAMETERS = 'parameters';
    public const SORT = 'sort';

    /**
     * Get rule name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get rule parameters
     *
     * @return string[]
     */
    public function getParameters(): array;

    /**
     * Get sort
     *
     * @return int
     */
    public function getSort(): int;
}
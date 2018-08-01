<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace FakeNamespace;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Exception\NotFoundException;

class FakeAction extends Action
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        throw new NotFoundException(__('I do not do anything'));
    }
}

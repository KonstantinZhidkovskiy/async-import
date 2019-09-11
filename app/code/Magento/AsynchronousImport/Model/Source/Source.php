<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousImport\Model\Source;

use Magento\AsynchronousImport\Model\Source\ResourceModel\Source as SourceResourceModel;
use Magento\AsynchronousImportApi\Api\Data\SourceInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * @inheritdoc
 */
class Source extends AbstractModel implements SourceInterface
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'asynchronous_import_source';

    /**
     * $uuid, $file, $metaData are marked as null for Backward Compatibility with \Magento\Framework\Data\Collection
     *
     * @see \Magento\Framework\Data\Collection::getNewEmptyItem
     *
     * @param Context $context
     * @param Registry $registry
     * @param string $uuid
     * @param string $file
     * @param string $metaData
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        string $uuid = null,
        string $file = null,
        string $metaData = null,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->setData(self::UUID, $uuid);
        $this->setData(self::FILE, $file);
        $this->setData(self::META_DATA, $metaData);
    }

    /**
     * Object initialization
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(SourceResourceModel::class);
    }

    /**
     * @inheritdoc
     */
    public function getUuid(): string
    {
        return $this->getData(self::UUID);
    }

    /**
     * @inheritdoc
     */
    public function getFile(): string
    {
        return $this->getData(self::FILE);
    }

    /**
     * @inheritdoc
     */
    public function getMetaData(): string
    {
        return $this->getData(self::META_DATA);
    }
}
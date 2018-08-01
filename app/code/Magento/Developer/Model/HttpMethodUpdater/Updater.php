<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\Developer\Model\HttpMethodUpdater;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\HttpMethodMap;

/**
 * Updates actions according to gathered logs.
 */
class Updater
{
    /**
     * @var HttpMethodMap
     */
    private $map;

    /**
     * @param HttpMethodMap $map
     */
    public function __construct(HttpMethodMap $map)
    {
        $this->map = $map;
    }

    /**
     * @param string $class
     * @param string $interface
     * @return void
     * @throws \RuntimeException
     */
    private function addInterface(string $class, string $interface): void
    {
        $reflection = new \ReflectionClass($class);
        $file = $reflection->getFileName();
        $className = $reflection->getShortName();
        $fileContent = file_get_contents($file);
        if ($fileContent === false) {
            throw new \RuntimeException("Failed to read $file");
        }

        if (preg_match('/class\s+' .$className .'\s+extends\s+[a-z0-9_]+\s+?\n?\{/i', $fileContent, $found)) {
            $beginning = preg_replace('/\s+?\n?\{$/', '', $found[0]);
            $rewrite = str_replace(
                $found[0],
                $beginning ." implements \\$interface\n{",
                $fileContent
            );
        } elseif (preg_match('/class\s+' .$className .'\s+extends\s+[a-z0-9_]+\s+implements\s+[0-9a-z_\\\,\s]+\s*?\n?\{/i', $fileContent, $found)) {
            $beginning = preg_replace('/\s+?\n?\{$/', '', $found[0]);
            $rewrite = str_replace(
                $found[0],
                $beginning .", \\$interface\n{",
                $fileContent
            );
        } else {
            throw new \RuntimeException("Cannot update $class");
        }

        $result = file_put_contents($file, $rewrite);
        if (!$result) {
            throw new \RuntimeException("Failed to rewrite $file");
        }
    }

    /**
     * @param Logged $logged
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return void
     */
    public function update(Logged $logged): void
    {
        $class = $logged->getActionClass();
        $implements = class_implements($class, true);
        if (!$implements || !in_array(ActionInterface::class, $implements)) {
            throw new \InvalidArgumentException(
                "Class $class is not an action"
            );
        }
        $map = $this->map->getMap();

        foreach ($logged->getMethods() as $method) {
            if (array_key_exists($method, $map)
                && !in_array($map[$method], $implements)) {
                $this->addInterface($class, $map[$method]);
            }
        }
    }
}

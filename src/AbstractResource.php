<?php
/*
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Worldpay;

use InvalidArgumentException;
use UnexpectedValueException;

use ReflectionProperty;

/**
 * Order Class
 *
 * Create an order resource.
 */
abstract class AbstractResource
{
    /**
     * URI for resource
     *
     * @var string
     */
    protected static $uri;

    /**
     * Required parameters
     *
     * @var array
     */
    protected static $required = [];

    /**
     * Resource Parameters
     *
     * @var array
     */
    protected $params = [];

    /**
     * Format Resource Parameters
     *
     * @return array
     */
    public function formatParameters(&$params)
    {
        // nothing.
    }

    /**
     * Get Resource Parameter
     *
     * @return array
     */
    public function getParameters()
    {
        $params = [];

        $this->formatParameters($params);

        if (empty($params))
        {
            $params = $this->params;
        }

        $filtered = [];

        foreach ($params as $key => $value) {
            if (is_null($value)) {
                continue;
            }

            $filtered[$key] = $value;
        }

        return $filtered;
    }

    /**
     * Get order JSON
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->getParameters());
    }

    /**
     * Set Parsed Parameters
     *
     * @param array $params Resource parameters
     *
     * @return void
     */
    public function setParameters(array $params)
    {
        self::parseRequired($params);

        foreach ($params as $key => $value) {
            $property = new ReflectionProperty($this, $key);
            if ($property->isProtected()) {
                $this->$key = $value;
                $this->params[$key] = $value;
            }
        }

        #$this->params = $params;
    }

    /**
     * Parse Required Fields
     *
     * @param array $params Resource parameters
     *
     * @return void
     */
    public static function parseRequired(array $params)
    {
        if (empty($params)) {
            throw new InvalidArgumentException('Invalid parameters');
        }

        $missing = [];
        foreach (self::$required as $key) {
            if (isset($params[$key])) {
                continue;
            }

            $missing[] = $key;
        }

        if (count($missing)) {
            throw new InvalidArgumentException(sprintf('Missing required parameters: %s', implode(', ', $errors)));
        }
    }
}
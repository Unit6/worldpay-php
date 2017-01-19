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

/**
 * Shopper Class
 *
 * Create a shopper instance.
 */
class Shopper extends AbstractResource
{
    /**
     * Shopper Email
     *
     * The email address supplied by the shopper.
     *
     * @var string
     */
    protected $email;

    /**
     * Shopper Client IP
     *
     * The shopper's IP address. This must NOT be your server IP.
     * Required for 3D Secure Orders
     *
     * @var string
     */
    protected $ip;

    /**
     * Set to the shopper's unique session identifier.
     * Required for 3D Secure Orders
     *
     * @var string
     */
    protected $sessionID;

    /**
     * Shopper Browser User Agent
     *
     * Set to the shopper's browser user agent.
     * Required for 3D Secure Orders
     *
     * @var string
     */
    protected $userAgent;

    /**
     * Shopper Accept Header from Browser
     *
     * Accept header of the shopper's browser.
     * Required for 3D Secure Orders
     *
     * @var string
     */
    protected $acceptHeader;

    /**
     * Creating a Shopper
     *
     * @param string|null $email     Shopper Email.
     * @param string|null $sessionID Shopper Session ID.
     *
     * @return Shopper
     */
    public function __construct($email = null, $sessionID = null)
    {
        $this->email = $email;
        $this->sessionID = $sessionID;

        // Use the $_SERVER superglobal.
        $this->parseEnvironment();
    }

    /**
     * Parse $_SERVER for Shopper Settings.
     *
     * @return string
     */
    private function parseEnvironment()
    {
        $ip = NULL;

        // IP address parameters ordered by preference or priority.
        $params = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($params as $i) {
            if (isset($_SERVER[$i]) && ! empty($_SERVER[$i])) {
                $ip = $_SERVER[$i];
                break;
            }
        }

        $this->ip = $ip;
        $this->userAgent = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null);
        $this->acceptHeader = (isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null);
    }

    /**
     * Shopper with Email
     *
     * @param Address $address
     *
     * @return self
     */
    public function withEmail($email)
    {
        $clone = clone $this;
        $clone->email = $email;

        return $clone;
    }

    /**
     * Shopper with IP
     *
     * @param string $ip Shoppers client IP address.
     *
     * @return self
     */
    public function withIP($ip)
    {
        $clone = clone $this;
        $clone->ip = $ip;

        return $clone;
    }

    /**
     * Shopper with Session ID
     *
     * @param string $sessionID Shoppers current session ID.
     *
     * @return self
     */
    public function withSessionID($sessionID)
    {
        $clone = clone $this;
        $clone->sessionID = $sessionID;

        return $clone;
    }

    /**
     * Shopper with User Agent
     *
     * @param string $userAgent Shoppers browser user agent string.
     *
     * @return self
     */
    public function withUserAgent($userAgent)
    {
        $clone = clone $this;
        $clone->userAgent = $userAgent;

        return $clone;
    }

    /**
     * Shopper with Accept Header
     *
     * @param string $acceptHeader Shoppers browser accept header.
     *
     * @return self
     */
    public function withAcceptHeader($acceptHeader)
    {
        $clone = clone $this;
        $clone->acceptHeader = $acceptHeader;

        return $clone;
    }

    /**
     * Get Shopper Email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get Shopper IP
     *
     * @return string
     */
    public function getIP()
    {
        return $this->ip;
    }

    /**
     * Get Session ID
     *
     * @return string
     */
    public function getSessionID()
    {
        return $this->sessionID;
    }

    /**
     * Get User Agent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Get Accept Header
     *
     * @return string
     */
    public function getAcceptHeader()
    {
        return $this->acceptHeader;
    }

    /**
     * Get Shopper Parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return [
            'shopperEmailAddress' => $this->getEmail(),
            'shopperIpAddress'    => $this->getIP(),
            'shopperSessionId'    => $this->getSessionID(),
            'shopperUserAgent'    => $this->getUserAgent(),
            'shopperAcceptHeader' => $this->getAcceptHeader(),
        ];
    }
}
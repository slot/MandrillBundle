<?php
namespace Hip\MandrillBundle;

/**
 * Mandrill Dispatcher Service
 *
 * Copyright (c) 2013 Hipaway Travel GmbH, Berlin
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author: Sven Loth <sven.loth@hipaway.com>
 * @copyright: 2013 Hipaway Travel GmbH, Berlin
 */
class Dispatcher
{
    /**
     * Mandrill service
     *
     * @var \Mandrill $service
     */
    protected $service;

    /**
     * Default Sender Email
     *
     * @var string
     */
    protected $defaultSender;

    /**
     * Default subaccount
     *
     * @var string
     */
    protected $subaccount;

    /**
     * Default Sender Name
     *
     * @var string
     */
    protected $defaultSenderName;

    /**
     * Proxy options
     *
     * @var array
     */
    protected $proxy;

    /**
     * @var bool
     */
    protected $disableDelivery;

    public function __construct($service, $defaultSender, $defaultSenderName, $subaccount, $disableDelivery, $proxy, $debug) {
        $this->service = $service;
        $this->defaultSender = $defaultSender;
        $this->defaultSenderName = $defaultSenderName;
        $this->subaccount = $subaccount;
        $this->disableDelivery = $disableDelivery;
        $this->proxy = $proxy;

        $this->service->debug = $debug;

        if ($this->useProxy()) {
            $this->addCurlProxyOptions();
        }
    }

    /**
     * Send a message
     *
     * @param Message $message
     * @param string $templateName
     * @param array $templateContent
     * @param bool $async
     * @param string $ipPool
     * @param string $sendAt
     *
     * @return array|bool
     */
    public function send(Message $message, $templateName = '', $templateContent = array(), $async = false, $ipPool=null, $sendAt=null)
    {
        if ($this->disableDelivery) {
            return false;
        }

        if (strlen($message->getFromEmail()) == 0) {
            $message->setFromEmail($this->defaultSender);
        }

        if (strlen($message->getFromName()) == 0 && null !== $this->defaultSenderName) {
            $message->setFromName($this->defaultSenderName);
        }

        if (strlen($message->getSubaccount()) == 0 && null !== $this->subaccount) {
            $message->setSubaccount($this->subaccount);
        }

        if (!empty($templateName)) {
            return $this->service->messages->sendTemplate($templateName, $templateContent, $message->toArray(), $async, $ipPool, $sendAt);
        }

        return $this->service->messages->send($message->toArray(), $async, $ipPool, $sendAt);
    }

    /**
     * Get Mandrill service
     *
     * @return \Mandrill
     */
    public function getService()
    {
        return $this->service;
    }

    private function useProxy()
    {
        return $this->proxy['use'];
    }

    private function addCurlProxyOptions()
    {
        if ($this->proxy['host'] !== null) {
            curl_setopt($this->service->ch, CURLOPT_PROXY, $this->proxy['host']);
        }

        if ($this->proxy['port'] !== null) {
            curl_setopt($this->service->ch, CURLOPT_PROXYPORT, $this->proxy['port']);
        }

        if ($this->proxy['user'] !== null && $this->proxy['password'] !== null) {
            curl_setopt($this->service->ch, CURLOPT_PROXYUSERPWD, sprintf(
                '%s:%s',
                $this->proxy['user'],
                $this->proxy['password']
            ));
        }
    }
}

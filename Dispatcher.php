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
     * Default Sender Name
     *
     * @var string
     */
    protected $defaultSenderName;

    public function __construct($service, $defaultSender, $defaultSenderName) {
        $this->service = $service;
        $this->defaultSender = $defaultSender;
        $this->defaultSenderName = $defaultSenderName;
    }

    /**
     * Send a message
     *
     * @param Message $message
     * @param string $templateName
     * @param array $templateContent
     * @param bool $async
     *
     * @return array
     */
    public function send(Message $message, $templateName = '', $templateContent = array(), $async = false)
    {

        if (strlen($message->getFromEmail()) == 0) {
            $message->setFromEmail($this->defaultSender);
            $message->setFromName($this->defaultSenderName);
        }

        if (!empty($templateName)) {
            return $this->service->messages->sendTemplate($templateName, $templateContent, $message->toArray(), $async);

        }

        return $this->service->messages->send($message->toArray(), $async);

    }





}

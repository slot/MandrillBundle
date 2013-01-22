<?php
namespace Hip\MandrillBundle;
/**
 * Description
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

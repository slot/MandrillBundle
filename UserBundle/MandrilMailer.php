<?php
/*
 * This file is adapted from the Wrep\FOSUserBundleMandrillMailer
 *
 * (c) Rick Pastoor <rick@wrep.nl>
 * Edited by: James Moughon <jmoughon@gmail.com>
 *
 */

namespace Hip\MandrillBundle\UserBundle;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;

/**
 * Mailer implementation for the FOSUserBundle
 *
 * @author Mathijs Kadijk <mathijs@wrep.nl>
 */
class MandrillMailer implements MailerInterface
{
    protected $router;
    protected $templating;
    protected $dispatcher;
    protected $message;
    protected $parameters;

    /**
     * Constructor
     *
     * @param RouterInterface   $router
     * @param EngineInterface   $templating
     * @param Dispatcher        $dispatcher
     * @param Message           $message
     * @param array             $parameters
     */
    public function __construct($mailer, RouterInterface $router, EngineInterface $templating, array $parameters)
    {
        $this->router = $router;
        $this->templating = $templating;
        $this->dispatcher = $dispatcher;
        $this->message = $message;
        $this->parameters = $parameters;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['confirmation.template'];
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' =>  $url
        ));
        $this->sendEmailMessage($rendered, $user->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['resetting.template'];
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url
        ));
        $this->sendEmailMessage($rendered, $user->getEmail());
    }

    /**
     * This will configure the message and send it.
     *
     * @param string $renderedTemplate
     * @param string $toEmail
     */
    protected function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail)
    {
        // Split subject and body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        // Check e-mail content
        if (strlen($body) == 0 || strlen($subject) == 0) {
            throw new \RuntimeException(
                    "No message was found, cannot send e-mail to " . $toEmail . ". This " .
                    "error can occur when you don't have set a confirmation template or using the default " .
                    "without having translations enabled."
            );
        }
        // Send message via Mandrill
        $this->message->addTo($toEmail);
        $this->message->setSubject($subject);
        $this->message->setText($body);

        $this->dispatcher->send($this->message);
    }
}

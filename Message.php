<?php
namespace Hip\MandrillBundle;

/**
 * A mandrill message
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
class Message
{
    /**
     * the full HTML content to be sent
     *
     * @var string
     */
    protected $html = '';

    /**
     * optional full text content to be sent
     *
     * @var string
     */
    protected $text;

    /**
     * the message subject
     *
     * @var string
     */
    protected $subject;

    /**
     * an array of recipient information
     *
     * @var array
     */
    protected $to = array();

    /**
     * the sender email address (required)
     *
     * @var string
     */
    protected $fromEmail;

    /**
     * optional from name to be used
     *
     * @var string
     */
    protected $fromName;

    /**
     * optional extra headers to add to the message (currently only Reply-To and X-* headers are allowed)
     *
     * @var array
     */
    protected $headers = array();

    /**
     * whether or not to turn on open tracking for the message
     *
     * @var bool
     */
    protected $trackOpens = true;

    /**
     * whether or not to turn on click tracking for the message
     *
     * @var bool
     */
    protected $trackClicks = true;

    /**
     * whether or not to automatically generate a text part for messages that are not given text
     *
     * @var bool
     */
    protected $autoText = true;

    /**
     * whether or not to strip the query string from URLs when aggregating tracked URL data
     *
     * @var bool
     */
    protected $urlStripQs = true;

    /**
     * whether or not to expose all recipients in to "To" header for each email
     *
     * @var bool
     */
    protected $preserveRecipients = true;

    /**
     * an optional address to receive an exact copy of each recipient's email
     *
     * @var string
     */
    protected $bccAddress;

    /**
     * whether to evaluate merge tags in the message.
     * Will automatically be set to true if either merge_vars or global_merge_vars are provided.
     *
     * @var bool
     */
    protected $merge = false;

    /**
     * global merge variables to use for all recipients. You can override these per recipient.
     *
     * @var array
     */
    protected $globalMergeVars = array();

    /**
     * per-recipient merge variables, which override global merge variables with the same name.
     *
     * @var array
     */
    protected $mergeVars = array();

    /**
     * an array of string to tag the message with.
     *
     * Stats are accumulated using tags, though we only store
     * the first 100 we see, so this should not be unique or
     * change frequently. Tags should be 50 characters or less.
     * Any tags starting with an underscore are reserved for
     * internal use and will cause errors.
     *
     * @var array
     */
    protected $tags = array();

    /**
     * an array of strings indicating for which any matching URLs
     * will automatically have Google Analytics parameters appended
     * to their query string automatically.
     *
     * @var array
     */
    protected $googleAnalyticsDomains = array();

    /**
     * optional string indicating the value to set for the utm_campaign
     * tracking parameter. If this isn't provided the email's from
     * address will be used instead.
     *
     * @var string
     */
    protected $googleAnalyticsCampaign = '';

    /**
     * metadata an associative array of user metadata. Mandrill
     * will store this metadata and make it available for retrieval.
     * In addition, you can select up to 10 metadata fields to index
     * and make searchable using the Mandrill search api.
     *
     * @var array
     */
    protected $metadata = array();

    /**
     * Per-recipient metadata that will override the global values specified
     * in the metadata parameter.
     *
     * @var array
     */
    protected $recipientMetadata = array();

    /**
     * an array of supported attachments to add to the message
     *
     * @var array
     */
    protected $attachments = array();


    /**
     * Add a recipient
     *
     * @param string $email
     * @param string $name
     *
     * @return Message
     */
    public function addTo($email, $name = '')
    {
        $this->to[] = array('email' => $email, 'name' => $name);

        return $this;
    }

    /**
     * Add extra header
     *
     * currently only Reply-To and X-* headers are allowed
     *
     * @param $key
     * @param $value
     *
     * @throws \Exception
     * @return Message
     */
    public function addHeader($key, $value)
    {
        if (!preg_match('/^X-.*|Reply-To/i', $key)) {
            throw new \Exception('currently only Reply-To and X-* headers are allowed');
        }

        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Set global merge variable to use for all recipients.
     * You can override these per recipient.
     *
     * @param string $name
     * @param string $content
     *
     * @return Message
     */
    public function addGlobalMergeVar($name, $content)
    {
        $this->globalMergeVars[] = array(
            'name' => $name,
            'content' => $content,
        );
 
        return $this;
    }

    /**
     * Add per-recipient merge variable,
     * which override global merge variables with the same name.
     *
     * @param string $recipient
     * @param string $name
     * @param string $content
     *
     * @return Message
     */
    public function addMergeVar($recipient, $name, $content)
    {
        $this->mergeVars[] = array(
            'rcpt' => $recipient,
            'vars' => array(
                array(
                    'name' => $name,
                    'content' => $content
                )
            )
        );

        return $this;
    }

    /**
     * Add a string to tag the message with.
     *
     * Stats are accumulated using tags, though we only store the first 100 we see,
     * so this should not be unique or change frequently. Tags should be 50
     * characters or less. Any tags starting with an underscore are reserved
     * for internal use and will cause errors.
     *
     * @param string $tag
     *
     * @throws \Exception
     * @return Message
     */
    public function addTag($tag)
    {
        if (substr($tag, 0, 1) == '_') {
            throw new \Exception('tag cannot start with underscore');
        }

        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Add google Analytics domain
     *
     * @param string $domain
     *
     * @return Message
     */
    public function addGoogleAnalyticsDomain($domain)
    {
        // todo: add domain name validator

        $this->googleAnalyticsDomains[] = $domain;

        return $this;
    }

    /**
     * Add global metadata.
     *
     * Mandrill will store this metadata and make it available for retrieval.
     * In addition, you can select up to 10 metadata fields to index and
     * make searchable using the Mandrill search api.
     *
     * @param string $data
     *
     * @return Message
     */
    public function addMetadata($data)
    {
        $this->metadata[] = $data;

        return $this;
    }

    /**
     * Add Per-recipient metadata that will override the global
     * values specified in the metadata parameter.
     *
     * @param string $recipient
     * @param string $data
     *
     * @return Message
     */
    public function addRecipientMetadata($recipient, $data)
    {
        foreach ($this->recipientMetadata as $idx => $rcptMetadata) {
            if (isset($rcptMetadata['rcpt']) && $rcptMetadata['rcpt'] == $recipient) {
                $this->recipientMetadata[$idx]['values'][] = $data;
                return $this;
            }
        }

        $this->recipientMetadata[] = array('rcpt' => $recipient, 'values' => array($data));

        return $this;
    }

    /**
     * Add supported attachments to add to the message
     *
     * @param string $type - the MIME type of the attachment - allowed types are text/*, image/*, and application/pdf
     * @param string $name - the file name of the attachment
     * @param string $data - base64 encoded attachment data
     *
     * @return Message
     */
    public function addAttachment($type, $name, $data)
    {
        $this->attachments[] = array(
            'type' => $type,
            'name' => $name,
            'content' => $data
        );

        return $this;


    }

    public function addAttachmentFromPath($path, $type = '', $name = '') {

        if (!is_readable($path)) {
            throw new \Exception('cannot read file ' . $path);
        }

        $data = file_get_contents($path);

        $base64data = base64_encode($data);

        if (empty($name)) {
            $name = basename($path);
        }

        if (empty($type)) {

            // open fileinfo database
            $finfo = finfo_open(FILEINFO_MIME, '/usr/share/misc/magic');

            if (!$finfo) {
                throw new \Exception('Opening fileinfo database failed, please specify type');
            }

            $type = finfo_file($finfo, $path);
            finfo_close($finfo);

        }

        $this->addAttachment($type, $name, $base64data);

        return $this;
    }

    /**
     * an optional address to receive an exact copy of each recipient's email
     *
     * @param string $bccAddress
     * @return \Hip\MandrillBundle\Message
     */
    public function setBccAddress($bccAddress)
    {
        $this->bccAddress = $bccAddress;

        return $this;
    }

    /**
     * the sender email address
     *
     * @param string $fromEmail
     * @return \Hip\MandrillBundle\Message
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    /**
     * optional from name to be used
     *
     * @param string $fromName
     * @return \Hip\MandrillBundle\Message
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;

        return $this;
    }

    /**
     * the full HTML content to be sent
     *
     * @param string $html
     * @return \Hip\MandrillBundle\Message
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * optional full text content to be sent
     *
     * @param string $text
     * @return \Hip\MandrillBundle\Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * the message subject
     *
     * @param string $subject
     * @return \Hip\MandrillBundle\Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * whether or not to turn on click tracking for the message
     *
     * @param boolean $trackClicks
     * @return \Hip\MandrillBundle\Message
     */
    public function setTrackClicks($trackClicks)
    {
        $this->trackClicks = $trackClicks;
    }

    /**
     * whether or not to turn on open tracking for the message
     *
     * @param boolean $trackOpens
     * @return \Hip\MandrillBundle\Message
     */
    public function setTrackOpens($trackOpens)
    {
        $this->trackOpens = $trackOpens;

        return $this;
    }

    /**
     * whether or not to strip the query string from URLs when aggregating tracked URL data
     *
     * @param boolean $urlStripQs
     * @return \Hip\MandrillBundle\Message
     */
    public function setUrlStripQs($urlStripQs)
    {
        $this->urlStripQs = $urlStripQs;

        return $this;
    }

    /**
     * whether or not to expose all recipients in to "To" header for each email
     *
     * @param boolean $preserveRecipients
     * @return \Hip\MandrillBundle\Message
     */
    public function setPreserveRecipients($preserveRecipients)
    {
        $this->preserveRecipients = $preserveRecipients;

        return $this;
    }

    /**
     * whether to evaluate merge tags in the message.
     *
     * Will automatically be set to true if either merge_vars or global_merge_vars are provided.
     *
     * @param boolean $merge
     * @return \Hip\MandrillBundle\Message
     */
    public function setMerge($merge)
    {
        $this->merge = $merge;

        return $this;
    }

    /**
     * optional string indicating the value to set for the utm_campaign tracking parameter.
     *
     * If this isn't provided the email's from address will be used instead.
     *
     * @param string $googleAnalyticsCampaign
     * @return \Hip\MandrillBundle\Message
     */
    public function setGoogleAnalyticsCampaign($googleAnalyticsCampaign)
    {
        $this->googleAnalyticsCampaign = $googleAnalyticsCampaign;

        return $this;
    }

    /**
     * whether or not to automatically generate a text part for messages that are not given text
     *
     * @param boolean $autoText
     * @return \Hip\MandrillBundle\Message
     */
    public function setAutoText($autoText)
    {
        $this->autoText = $autoText;

        return $this;
    }

    public function toArray()
    {
        $data = array();

        $reflectedThis = new \ReflectionObject( $this );

        foreach($reflectedThis->getProperties() as $reflectionProperty) {

           $key = $reflectionProperty->name;
           $key_underscore = preg_replace('/(?<=\\w)(?=[A-Z])/','_$1', $key);
           $key_underscore = strtolower($key_underscore);

           $data[$key_underscore] = $this->{$key};
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return boolean
     */
    public function getAutoText()
    {
        return $this->autoText;
    }

    /**
     * @return string
     */
    public function getBccAddress()
    {
        return $this->bccAddress;
    }

    /**
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @return array
     */
    public function getGlobalMergeVars()
    {
        return $this->globalMergeVars;
    }

    /**
     * @return string
     */
    public function getGoogleAnalyticsCampaign()
    {
        return $this->googleAnalyticsCampaign;
    }

    /**
     * @return array
     */
    public function getGoogleAnalyticsDomains()
    {
        return $this->googleAnalyticsDomains;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @return boolean
     */
    public function getMerge()
    {
        return $this->merge;
    }

    /**
     * @return array
     */
    public function getMergeVars()
    {
        return $this->mergeVars;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return boolean
     */
    public function getPreserveRecipients()
    {
        return $this->preserveRecipients;
    }

    /**
     * @return array
     */
    public function getRecipientMetadata()
    {
        return $this->recipientMetadata;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return boolean
     */
    public function getTrackClicks()
    {
        return $this->trackClicks;
    }

    /**
     * @return boolean
     */
    public function getTrackOpens()
    {
        return $this->trackOpens;
    }

    /**
     * @return boolean
     */
    public function getUrlStripQs()
    {
        return $this->urlStripQs;
    }
}

<?php

/**
 * Zend_Mail_Transport wrapper for Mandrill
 *
 * @copyright Copyright &copy; Sam Stenvall 2016-
 * @license   https://opensource.org/licenses/MIT The MIT License (MIT)
 */
class Zend_Mail_Transport_Mandrill extends Zend_Mail_Transport_Abstract
{

    /**
     * @var Mandrill
     */
    private $mandrill;

    /**
     * @var array
     */
    private $messageOptions;


    /**
     * Zend_Mail_Transport_Mandrill constructor.
     *
     * @param string $apiKey
     * @param array  $messageOptions
     */
    public function __construct($apiKey, $messageOptions = [])
    {
        $this->mandrill = new Mandrill($apiKey);
        $this->setMessageOptions($messageOptions);
    }


    /**
     * @param array $messageOptions
     */
    public function setMessageOptions($messageOptions)
    {
        $this->messageOptions = $messageOptions;
    }


    /**
     * @inheritdoc
     */
    protected function _sendMail()
    {
        // Re-throw as Zend_Mail_Transport_Exception
        try {
            $this->mandrill->messages->send($this->buildMessage());
        } catch (Mandrill_Error $e) {
            throw new Zend_Mail_Transport_Exception('Failed to send e-mail: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * @return array
     */
    protected function buildMessage()
    {
        $options = [
            'subject'    => $this->_mail->getSubject(),
            'from_email' => $this->_mail->getFrom(),
            'to'         => $this->buildRecipients(),
        ];

        $html = $this->_mail->getBodyHtml();
        $text = $this->_mail->getBodyText();

        if ($html) {
            $options['html'] = $html->getRawContent();
        }

        if ($text) {
            $options['text'] = $text->getRawContent();
        }

        return array_merge($this->messageOptions, $options);
    }


    /**
     * @return array
     */
    protected function buildRecipients()
    {
        $recipients = [];

        foreach ($this->_mail->getRecipients() as $recipient) {
            $recipients[] = [
                'email' => $recipient,
                'type'  => 'to',
            ];
        }

        return $recipients;
    }

}

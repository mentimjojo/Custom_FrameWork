<?php

class Mailer
{

    /**
     * Sender of the email and sender name
     *
     * @var string
     */
    private $sender;

    /**
     * Receiver of the email
     *
     * @var string
     */
    private $receiver;

    /**
     * The cc of the email
     *
     * @var string
     */
    private $cc;

    /**
     * The bcc of the email
     *
     * @var string
     */
    private $bcc;

    /**
     * Subject of the email
     *
     * @var string
     */
    private $subject;

    /**
     * Message of the mail
     *
     * @var string
     */
    private $message;

    /**
     * Set headers of the email
     *
     * @var array
     */
    private $headers;

    /**
     * Mail template
     *
     * @var string
     */
    private $template = 'Mail_Template.php';


    /**
     * The live mail template
     *
     * @var
     */
    private $mail_template;

    /**
     * Mailer constructor.
     */
    public function __construct()
    {
        // Get mail template
        $this->mail_template = file_get_contents(Constants::path_resources . '/Templates/' . $this->template);
    }

    /**
     * Set template of the mail, default is Mail_Template.php
     *
     * @param  string $template
     * @return $this
     */
    public function setTemplate(string $template = 'Mail_Template.php')
    {
        // Set template
        $this->template = $template;
        // Get mail template
        $this->mail_template = file_get_contents(Constants::path_resources . '/Templates/' . $this->template);
        // Return this
        return $this;
    }

    /**
     * Set sender of the mail
     *
     * @param  string $sender_name
     * @param  string $sender_email
     * @return $this
     */
    public function setSender(string $sender_name, string $sender_email)
    {
        // Set sender
        $this->sender = (object)array(
            'name' => $sender_name,
            'email' => $sender_email
        );
        // Return this
        return $this;
    }

    /**
     * Set cc receiver
     * @param  string $cc_name
     * @param  string $cc_email
     * @return $this
     */
    public function setCC(string $cc_name, string $cc_email)
    {
        // Set cc
        $this->cc = (object)array(
            'name' => $cc_name,
            'email' => $cc_email
        );
        // Return this
        return $this;
    }

    /**
     * Set bcc
     *
     * @param string $bcc_name
     * @param string $bcc_email
     * @return $this
     */
    public function setBCC(string $bcc_name, string $bcc_email)
    {
        // Set bcc
        $this->bcc = (object)array(
            'name' => $bcc_name,
            'email' => $bcc_email
        );
        // Return this
        return $this;
    }

    /**
     * Set receiver of the email
     *
     * @param string $receiver_name
     * @param string $receiver_email
     * @return $this
     */
    public function setReceiver(string $receiver_name, string $receiver_email)
    {
        // Set receiver
        $this->receiver = (object)array(
            'name' => $receiver_name,
            'email' => $receiver_email
        );
        // Return this
        return $this;
    }

    /**
     * Set subject of the mail
     *
     * @param string $subject
     * @return $this
     */
    public function setSubject(string $subject)
    {
        // Set subject
        $this->subject = $subject;
        // Return this
        return $this;
    }

    /**
     * Set message of the email
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message)
    {
        // Set message
        $this->message = $message;
        // Return this
        return $this;
    }

    /**
     * Set headers of the email
     */
    private function setHeaders()
    {
        // Mime version
        $this->headers[] = 'MIME-Version: 1.0';
        // Use plain html
        $this->headers[] = "Content-type:text/html;charset=UTF-8";
        // Reply
        $this->headers[] = "Reply-To: " . $this->sender->name . " <" . $this->sender->email . ">";
        // Return path
        $this->headers[] = "Return-Path: " . $this->sender->name . " <" . $this->sender->email . ">";
        // From
        $this->headers[] = "From: " . $this->sender->name . " <" . $this->sender->email . ">";
        // To
        $this->headers[] = "To: " . $this->receiver->name . " <" . $this->receiver->email . ">";
        // CC
        if (!empty($this->cc->email)) {
            $this->headers[] = "Cc: " . $this->cc->name . " <" . $this->cc->email . ">";
        }
        // BCC
        if (!empty($this->bcc->email)) {
            $this->headers[] = "Bcc: " . $this->bcc->name . " <" . $this->bcc->email . ">";
        }
        // PHP version
        $this->headers[] = "X-Mailer: PHP/" . phpversion();
    }

    /**
     * Replace variable in mail template
     *
     * @param string $search
     * @param string $replace
     */
    public function replaceVariable(string $search, string $replace){
        // Replace variable
        $this->mail_template = str_replace($search, $replace, $this->mail_template);
    }

    /**
     * Send the mail
     * @return stdClass
     */
    public function send(): stdClass
    {
        // Check if all set
        if (isset($this->receiver)) {
            if (isset($this->sender)) {
                if (isset($this->subject)) {
                    if (isset($this->message)) {
                        if (file_exists(Constants::path_resources . '/Templates/' . $this->template)) {
                            // Set headers ready
                            $this->setHeaders();
                            // Replace message
                            $mail_temp = str_replace('{message}', $this->message, $this->mail_template);
                            // Try sending
                            try {
                                // Send mail
                                mail($this->receiver->email, $this->subject, $mail_temp, implode("\r\n", $this->headers));
                                // Return
                                $return = array('status' => true, 'message' => 'success');
                            } catch (Exception $ex) {
                                // Throw warning
                                ErrorHandler::warning(108, 'error_unknown');
                                // Return
                                $return = array('status' => false, 'message' => 'error_unknown');
                            }
                        } else {
                            // Set return
                            $return = array('status' => false, 'message' => "error_mail_template");
                        }
                    } else {
                        // Set return
                        $return = array('status' => false, 'message' => 'error_no_message');
                    }
                } else {
                    // Set return
                    $return = array('status' => false, 'message' => 'error_no_subject');
                }
            } else {
                // Set return
                $return = array('status' => false, 'message' => 'error_no_sender');
            }
        } else {
            // Set return
            $return = array('status' => false, 'message' => 'error_no_receiver');
        }
        // Return
        return (object)$return;
    }

}

?>
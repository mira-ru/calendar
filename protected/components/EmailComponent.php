<?php

class EmailComponent extends CComponent
{

        /**
         * @var string the content-type of the email
         */
        public $contentType = 'UTF-8';

        /**
         * @var string language to encode the message in (eg "Japanese", "ja", "English", "en" and "uni" (UTF-8))
         */
        public $language = 'uni';

        /**
         * @var integer line length of email as per RFC2822 Section 2.1.1
         */
        public $lineLength = 70;

        private $to;
        private $subject;
        private $from_email;
        private $from_author;
        private $message;
        private $headers;

        public function init()
        {
                $this->subject = null;
                $this->from_email = null;
                $this->from_author = null;
                $this->message = null;
                $this->headers = '';
        }

       /**
        * Setter for recipient's
        * @param string|array $emails Emails of recipients
        * @return EmailComponent
        */
        public function to($emails)
        {
                if(!is_array($emails))
                        $emails = array($emails);

                $this->to = $emails;

                return $this;
        }

        /**
         * Setter for from_author and from_email
         * @param mixed $from author name or array("author"=>"", "email"=>"")
         * @return EmailComponent
         */
        public function from($from)
        {
                if(!is_array($from)) {
                        $this->from_author = $from;
                        return $this;
                }

                if(isset($from['author']))
                        $this->from_author = $from['author'];

                if(isset($from['email']))
                        $this->from_email = $from['email'];

                return $this;
        }

        /**
         * Setter for subject
         * @param string $subject
         * @return EmailComponent
         */
        public function subject($subject)
        {
                $this->subject = $subject;
                return $this;
        }

        /**
         * Setter for message
         * @param string $message
         * @return EmailComponent
         */
        public function message($message)
        {
                $this->message = $message;
                return $this;
        }

        /**
         * Prepare mail and insert into queue for sending
         * @throws CException
         * @return mixed
         */
        public function send()
        {
                if(empty($this->to))
                        throw new CException('Empty recipient list');

                $this->generateMail();

		mb_language($this->language);

		return mail($this->to, $this->subject, $this->message, $this->headers, '-f'.$this->from_email);
        }

        /**
         * Preparing message for send.
         * Not required for sending mail.
         * Usage for getting generated mail attributes.
         * @return $this
         */
        public function prepare()
        {
                $this->generateMail();
                return $this;
        }

        /**
         * Replace message, subject and author params, init default values for empty attributes
         * Call function generateHeaders
         * @throws CException
         * @return bool
         */
        private function generateMail()
        {
                if(is_array($this->to))
                        $this->to = implode(', ', $this->to);
                if(is_null($this->subject))
                        $this->subject = '';
                if(is_null($this->from_email))
                        $this->from_email = '';
                if(is_null($this->from_author))
                        $this->from_author = '';
                if(is_null($this->message))
                        $this->message = '';

		$this->message = wordwrap($this->message, $this->lineLength);
                $this->generateHeaders();

                return true;
        }

        /**
         * Generate mail headers
         * @return bool
         */
        private function generateHeaders()
        {
                $headers = "";
                $headers .= "From: =?{$this->contentType}?B?" . base64_encode($this->from_author) . "?=<{$this->from_email}>\n";
                $headers .= "MIME-Version: 1.0\n";
                $headers .= "Content-Type: text/html; charset={$this->contentType}\n";
                $headers .= "Content-Transfer-Encoding: 8bit\n";
                $this->headers = $headers;
                return true;
        }

        /**
         * Getter for subject
         * @return string
         */
        public function getSubject()
        {
                return $this->subject;
        }

        /**
         * Getter for from
         * @return array
         */
        public function getFrom()
        {
                return array(
                        'from_author'=>$this->from_author,
                        'from_email'=>$this->from_email,
                );
        }

        /**
         * Getter for message
         * @return string
         */
        public function getMessage()
        {
                return $this->message;
        }
}
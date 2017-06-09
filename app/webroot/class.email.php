<?php
$server_root = str_replace('/sendsms.php','',$_SERVER['SCRIPT_FILENAME']);
# Arvin Castro, arvin@sudocode.net
# http://sudocode.net/sources/includes/class-email-php/
# 9 August 2011

# requires Mail_mimeDecode class;

require_once $server_root.'/Mail/mimeDecode.php';  # uncomment

/*  Changelog
	25-05-11
	Used preg_match for getSenderEmail and getRecipientEmail
	Added static functions: get_email_address, get_email_name, get_email_user, get_email_domain

	09-08-11
	Added function getPartRecursive to recursively search through multipart messages for matching parts
*/

class email {

    var $raw;
    var $resource;

    public function parse($raw) {
        $email = new email();
        $email->raw = $raw;

        # Loading mimeDecode library
        $params['include_bodies'] = true;
        $params['decode_bodies']  = true;
        $params['decode_headers'] = true;
        $params['input'] = $raw;

        # Decoding stream for mime message
        $email->resource = Mail_mimeDecode::decode($params);

        return $email;
    }

    public function parseSTDIN() {
        return self::parse(file_get_contents('php://stdin'));
    }
    public function parseFile($path) {
        return self::parse(file_get_contents($path));
    }
    public function parseURL($uri) {
        return self::parse(file_get_contents($uri));
    }
    public function parsePOST() {
	    return self::parse(file_get_contents('php://input'));
    }

    #---------------------------------------------#

    public function getHeader($key) {
        return isset($this->resource->headers[$key]) ? $this->resource->headers[$key]: null;
    }

    public function getSubject() {
        return $this->getHeader('subject');
    }

    public function getRecipient() {
        return $this->getHeader('to');
    }
    public function getRecipientEmail() {
		return self::get_email_address($this->getRecipient());
    }

    public function getSender() {
        return $this->getHeader('from');
    }
    public function getSenderEmail() {
		return self::get_email_address($this->getSender());
    }

    public function getReplyTo() {
        return $this->getHeader('reply-to');
    }
    public function getReplyToEmail() {
        return self::get_email_address($this->getReplyTo());
    }

    public static function get_email_address($email) {
		if(preg_match('/<([^<>]+)>/', $email, $matches)) {
			$email = $matches[1];
        }
        return $email;
    }
    public static function get_email_domain($email) {
		list($user, $domain) = explode('@', self::get_email_address($email), 2);
		return $domain;
    }
    public static function get_email_user($email) {
		list($user, $domain) = explode('@', self::get_email_address($email), 2);
		return $user;
    }
    public static function get_email_name($email) {
		if(preg_match('/(.+)<[^<>]+>/', $email, $matches)) {
			$name = trim($matches[1], " '\"\n\t");
		} else {
			$name = self::get_email_user($email);
		}
		return $name;
    }

    #---------------------------------------------#

    public function getParts($contentType) { return $this->getPart($contentType); }

    public function getPart($contentType) {
        $parts = array();
        list($content, $type) = explode('/', $contentType);
        return $this->getPartRecursive($content, $type, $this->resource);
    }    
    
    public function getPartRecursive($content, $type, $resource) {
    	$parts = array();
    	if($resource->ctype_primary == 'multipart') {
    		foreach($resource->parts as $part) {
    			$matches = $this->getPartRecursive($content, $type, $part);
    			if($matches) $parts = array_merge($parts, $matches);
    		}
    	} elseif(($content == '*' and $type == '*') or ($content == $resource->ctype_primary and ($type == '*' or $type == $resource->ctype_secondary))) {
    		$parts[] = $resource;
    	}
    	return $parts;
    }

    public function getContentType($resource = null, $option = null) {
        if($resource == null) $resource = $this->resource;
        if($resource == 'primary' || $resource == 'secondary') {
            $option   = $resource;
            $resource = $this->resource;
        }

        if($option == 'primary')
            return $resource->ctype_primary;
        if($option == 'secondary')
            return $resource->ctype_secondary;

        return $resource->ctype_primary.'/'.$resource->ctype_secondary;
    }

    #---------------------------------------------#

    public function getTextContent() {

        # Look for part with Content-Type: text/plain
        $text = $this->getPart('text/plain');
        if(count($text) > 0) {
            # TODO handle multiple parts of the same type
            # $text[0]->body
            return $text[0]->body;
        }

        # No matching parts found
        # Look for part with Content-Type: text/html
        $text = $this->getPart('text/html');
        if(count($text) > 0) {
            # TODO handle multiple parts of the same type

            $body = $text[0]->body;

            # Tags are stripped, to return plain text
            $body = strip_tags($body);

            # Converted to ASCII
            $body = html_entity_decode($body);

            return $body;
        }

        # No matching parts found
        return '';
    }

    #--------------------------------------------#

    public function removeSignature($text) {
        # Remove Signature delimited by --
        $sig = (strrpos($text, "\r\n--\r\n") !== false) ? strrpos($text, "\r\n--\r\n"): (strrpos($text, "\n--\n")) ? strrpos($text, "\n--\n"): strrpos($text, "--");
        if($sig !== false) {
            $text = substr($text, 0, $sig);
        }

        # Remove original messages/past conversations/RE:
        $sig = strripos($text, 'original message');
        if($sig !== false) {
            $text = substr($text, 0, $sig);
        }

        # Remove Sent from my blah blah
        $sig = strripos($text, 'sent from my');
        if($sig !== false) {
            $text = substr($text, 0, $sig);
        }

        return trim($text);
    }

}

?>
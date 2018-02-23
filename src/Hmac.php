<?php
/**
 * Created by PhpStorm.
 * User: swimtobird
 * Date: 2018/2/23
 * Time: 14:52
 */

namespace Swimtobird;


use Carbon\Carbon;

class Hmac
{
    protected $username;

    protected $secret;

    protected $host;

    protected $url;

    protected $method;

    protected $algorithm = 'sha1';

    /**
     * Hmac constructor.
     * @param $username
     * @param $secret
     * @param $host
     * @param string $method
     * @param string $url
     */
    public function __construct($username, $secret, $host, $method = 'GET', $url = '/')
    {
        $this->setUsername($username);

        $this->setSecret($secret);

        $this->setHost($host);

        $this->setMethod($method);

        $this->setUrl($url);
    }

    /**
     * @param $account
     */
    protected function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param $secret
     */
    protected function setSecret($secret)
    {
        $this->secret = $secret;
    }

    protected function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public static function getDateHeader()
    {
        return (Carbon::now('GMT'))->format('D, d M Y H:i:s T');
    }

    /**
     * @param $host
     * @return $this
     */
    protected function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getHeader()
    {
        return [
            "Host" => $this->host,
            "Date" => self::getDateHeader(),
            "Authorization" => $this->getAuthorization(),
        ];
    }

    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    protected function getSignatureHeaders()
    {
        return [
            'date' => self::getDateHeader(),
            'request-line' => $this->getAuthorizationHeader()
        ];
    }

    protected function getSignature()
    {
        $signing = self::getSignatureString($this->getSignatureHeaders());

        return base64_encode(hash_hmac($this->algorithm, $signing, '70e8f08ce07490d05d1252a17b1aa335', $raw_output = true));
    }

    protected function getAuthorizationHeader()
    {
        $requestLine = $this->method . " " . $this->url . " HTTP/1.1";

        return $requestLine;
    }

    /**
     * @return string
     */
    protected function getAuthorizationTemplate()
    {
        return 'hmac username="%s",algorithm="%s",headers="%s",signature="%s"';
    }

    /**
     * @return string
     */
    protected function getAuthorization()
    {
        return sprintf($this->getAuthorizationTemplate(),
            $this->username,
            'hmac-' . $this->algorithm,
            self::getHeadersString($this->getSignatureHeaders()),
            $this->getSignature()
        );
    }

    private static function getSignatureString($signatureHeaders)
    {
        $sigString = "";
        foreach ($signatureHeaders as $key => $val) {
            if ($sigString !== "") {
                $sigString .= "\n";
            }
            if (mb_strtolower($key) === "request-line") {
                $sigString .= $val;
            } else {
                $sigString .= mb_strtolower($key) . ": " . $val;
            }
        }
        return $sigString;
    }

    private static function getHeadersString($signatureHeaders)
    {
        $headers = "";
        foreach ($signatureHeaders as $key => $val) {
            if ($headers !== "") {
                $headers .= " ";
            }
            $headers .= $key;
        }
        return $headers;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: swimtobird
 * Date: 2018/2/23
 * Time: 14:52
 */

use Swimtobird\Hmac;

class HmacTest extends \PHPUnit\Framework\TestCase
{
    public function testGetHeader()
    {
        $hamc = new Hmac('user','secret','example.com','GET');

        $hamc->setUrl('/default');

        $header = $hamc->getHeader();


        $client = new GuzzleHttp\Client(['base_uri' => 'http://example.com']);

        $request = $client->request('GET', '/default', [
            'headers' => $header
        ]);

        $this->assertEquals('200',$request->getStatusCode());
    }

    public function testPostHeader()
    {
        $hamc = new Hmac('user','secret','example.com','POST');

        $hamc->setUrl('/default');

        $header = $hamc->getHeader();

        $client = new GuzzleHttp\Client(['base_uri' => 'http://example.com']);

        $request = $client->request('POST', '/default', [
            'headers' => $header,
            'form_params'=>[
                'params' => 'test'
            ]
        ]);

        $this->assertEquals('200',$request->getStatusCode());
    }
}
# HMAC Authentication with Kong by PHP

## Installation
``composer require swimtobird/kong-hmac``

## HOW TO USE
- GET

````php

        $hamc = new Hmac('user','secret','example.com','GET');

        $hamc->setUrl('/default');

        $header = $hamc->getHeader();


        $client = new GuzzleHttp\Client(['base_uri' => 'http://example.com']);

        $request = $client->request('GET', '/default', [
            'headers' => $header
        ]);

        print json($response->getBody(),true);
````

- POST

``````php

        $hamc = new Hmac('user','secret','example.com','POST');

        $hamc->setUrl('/default');

        $header = $hamc->getHeader();


        $client = new GuzzleHttp\Client(['base_uri' => 'http://example.com']);

        $request = $client->request('POST', '/default', [
            'headers' => $header
            'form_params'=>[
                'params' => 'test'
            ]            
        ]);

        print json($response->getBody(),true);
`````
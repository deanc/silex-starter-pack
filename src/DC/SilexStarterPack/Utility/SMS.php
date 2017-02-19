<?php

namespace DC\SilexStarterPack\Utility;
use Twilio\Rest\Client;

class SMS {

    private $app;

    public function __construct(\Silex\Application $app) {
        $this->app = $app;
    }

    public function send($from, $to, $body) {

        $client = new Client(TWILIO_ACCOUNT_SID, TWILIO_ACCOUNT_TOKEN);

        $message = $client->messages->create(
            $to,
            array(
                'from' => $from,
                'body' => $body
            )
        );

        if(property_exists($message, 'id') AND !empty($message->id)) {
            $now = $this->app['db']->fetchColumn("SELECT NOW()");
            $this->app['db']->insert('sms', array(
                'from' => $from, 'to' => $to, 'body' => $body, 'now' => $now
            ));
        }

        return $message;
    }
}
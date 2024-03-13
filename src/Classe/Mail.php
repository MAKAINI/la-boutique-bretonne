<?php

namespace App\Classe;
use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = 'fb45dc4d19655bc5b14c2702a95b56cc';
    private $api_key_secret = '07d356a5889b531b6c2ec02ef30c965f';

    public function send($to_email, $to_name, $subject, $content)
    {
      $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1'] );
     // $mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'),true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "makainimallh2005@yahoo.com",
                        'Name' => "Makaini"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 5747529,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content, 
                    ]
                ]
            ]
        ];
$response = $mj->post(Resources::$Email, ['body' => $body]);
$response->success(); 
      
    }

}
<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    //creation de deux variable avec nos clés d'identification
        //on les recupère sur le site de mailjet dans mon compte->preference compte->Gestion des clés API de la clé principale et des sous clés
    private $api_key = '3fdb0a8d09893e94f12bdcdcc5b23c36';
    private $api_key_secret ='8c601a72cb91b015bbc9cf75b36137ae';

    //fonction envoi de mail
    public function send($to_email, $to_name, $subject, $content)
    {
        //objet mailjet avec instanciation
        $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);
        //creation du corps du mail
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "anadyeven@gmail.com",
                        'Name' => "Shoes&Shop"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 2769776,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        //methode post qui permet de poster les emails
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        //on regarde la reponse
        $response->success();
        //$response->success() && dd($response->getData());
    }
}
<?php

namespace App\Mailer;

class LaravelMailer
{
    protected $to, $subject, $content;
    public function __construct($to, $subject, $content)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->content = $content;
    }
    public function mailBody()
    {
      /*   [
            [
                'Email' => "mytestemailvoucher@gmail.com",
                'Name' => "mytestemail"
            ]
        ] */
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "mytestemailvoucher@gmail.com",
                        'Name' => "mytestemail"
                    ],
                    'To' => $this->to,
                    'Subject' => $this->subject,
                    'HTMLPart' => $this->content,
                ]
            ]
        ];
        return $body;
    }
    public function sendMail()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.mailjet.com/v3.1/send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->mailBody()));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json'
            )
        );
        curl_setopt($ch, CURLOPT_USERPWD, env('MailJet_APISecretKey'));
        $server_output = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($server_output);
        if ($response->Messages[0]->Status == 'success') {
            echo "Email sent successfully.";
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 11/3/15
 * Time: 5:04 PM
 */

namespace App\Services;

use App\Services\Interfaces\SendMailInterface;
use PHPMailer;

class SESSendMail implements SendMailInterface
{
    protected $host = 'email-smtp.us-east-1.amazonaws.com';

    public function mail($options)
    {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $this->host;
        $mail->SMTPAuth = true;
        $mail->Username = env('SES_USERNAME');
        $mail->Password = env('SES_PASSWORD');
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->From = $options['from'];
        $mail->FromName = (isset($options['fromName'])) ? $options['fromName'] : 'Labs';
        $mail->addAddress($options['to'], $options['toName']);
        $mail->isHTML(true);
        $mail->Subject = $options['subject'];
        $mail->Body = $options['mailBody'];

        // check if there is any attachment
        if (isset($options['attachment'])) {
            $mail->addAttachment($options['attachment']);
        }

        if (!$mail->send()) {
            \App::abort(500, $mail->ErrorInfo);
            return false;
        } else {
            return true;
        }
    }
}

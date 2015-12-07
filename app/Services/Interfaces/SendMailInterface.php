<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 11/3/15
 * Time: 5:04 PM
 */

namespace App\Services\Interfaces;


interface SendMailInterface
{
    public function mail($options);
}

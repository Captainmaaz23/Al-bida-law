<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\MainController as MainController;
use Illuminate\Http\Request;
use Mail;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends MainController {

    public function basic_email() {
        $data = array('name' => "Samiullah");

        Mail::send(['text' => 'mail'], $data, function ($message) {

            $message->to('xamicomsian@gmail.com', 'Sami')->subject
                    ('Laravel Basic Testing Mail');

            $message->from('info@logic-valley.com', 'Samiullah');
        });

        echo "Basic Email Sent. Check your inbox.";
    }

    /*

      public function html_email() {

      $data = array('name'=>"Virat Gandhi");

      Mail::send('mail', $data, function($message) {

      $message->to('abc@gmail.com', 'Tutorials Point')->subject

      ('Laravel HTML Testing Mail');

      $message->from('xyz@gmail.com','Virat Gandhi');

      });

      echo "HTML Email Sent. Check your inbox.";

      }

      public function attachment_email() {

      $data = array('name'=>"Virat Gandhi");

      Mail::send('mail', $data, function($message) {

      $message->to('abc@gmail.com', 'Tutorials Point')->subject

      ('Laravel Testing Mail with Attachment');

      $message->attach('C:\laravel-master\laravel\public\uploads\image.png');

      $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');

      $message->from('xyz@gmail.com','Virat Gandhi');

      });

      echo "Email Sent with attachment. Check your inbox.";

      } */
}

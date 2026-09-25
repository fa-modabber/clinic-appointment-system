<?php

class SmsChannel
{
    public function send($notifiable, $notification): void
    {
        $message = $notification->toSms($notifiable);

        // Call sms provider API
        // $receiver= $notifiable->mobile
        // send a sms including $message to the $receiver 
    }
}
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNoticeSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $content = array();

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data = array())
    {
        $this->content = $data;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::debug('SendNoticeSMSJob sms');
        $url = 'https://smsb2c.mitake.com.tw/b2c/mtk/SmSend?';
        $url .= 'username=pleaseEnter';
        $url .= '&password=pleaseEnter';
        $url .= '&CharsetURL=UTF8';
        $url .= '&dstaddr='.$this->content['phone'];
        $url .= '&smbody='.$this->content['message'];
        $url .= '&response='.route('my-account.send_phone');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
    }
}

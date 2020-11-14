<?php

namespace App\Jobs;

use App\Mail\SendBackgroundNoticeEmail ;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNoticeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data = '')
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
        \Log::debug('SendNoticeEmailJob email');
        $email = new SendBackgroundNoticeEmail($this->content);
        \Mail::to($this->content['receiver_email'])->queue($email);
    }
}

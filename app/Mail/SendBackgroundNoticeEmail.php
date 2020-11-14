<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBackgroundNoticeEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->content = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.notice_template')
            ->from($this->content['sender'])
            ->subject($this->content['subject'])
            ->with([
                'receiver_email'     => $this->content['receiver_email'],
                'receiver_name'     => $this->content['receiver_name'],
                'content'     => $this->content['content'],
            ]);
    }
}

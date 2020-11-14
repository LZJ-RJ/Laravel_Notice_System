<?php

use Illuminate\Database\Seeder;

class NoticeManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $noticeManagerModel = app(\App\NoticeManagers::class);
        $default_array = array(
            array(
                'event' => 'register',
                'target' => 'user',
                'email_subject' => '',
                'email_content' => '',
                'email_activated' => 'N',
                'sms_content' => '',
                'sms_activated' => 'N',
            ),
            //TODO 新增事件處
        );

        foreach ($default_array as $insert_array){
            $noticeManagerModel->create($insert_array);
        }

    }
}

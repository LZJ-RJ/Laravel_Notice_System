<?php

namespace App\Http\Controllers;

use App\Jobs\SendNoticeEmailJob;
use App\Jobs\SendNoticeSMSJob;
use App\NoticeManagers;
use App\NoticeBoxes;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeManagerController extends Controller
{

    private  $event_name_to_chinese = array(
        'register' => '註冊成功',
        'all' => '全站通知',
    );

    //編輯事件的頁面
    public function edit($event_name, Request $request)
    {
        //TODO 新增事件處
        $event_name_to_chinese = array(
            'register' => '註冊成功',
            'all' => '全站通知',
        );

        $type_to_chinese = array(
            'email' => '信箱',
            'sms' => '簡訊',
        );

        $edit_notice_event = NoticeManagers::where('event', $event_name)->get()->toArray();
        if(!empty($edit_notice_event)){
            $edit_notice_event = $edit_notice_event[0];
        }

        if($request->get('type') == 'email'){
            return view('admin.setting.notice.notice-email-edit', [
                'event_name' => $event_name,
                'edit_notice_event' => $edit_notice_event,
                'type' => $request->get('type'),
                'event_name_to_chinese' => $event_name_to_chinese[$event_name] ,
                'type_to_chinese' => $type_to_chinese[$request->get('type')],
                'target'=> $request->get('target'),
            ]);

        }else{
            return view('admin.setting.notice.notice-sms-edit',[
                'event_name' => $event_name,
                'edit_notice_event' => $edit_notice_event,
                'type' => $request->get('type'),
                'event_name_to_chinese' => $event_name_to_chinese[$event_name] ,
                'type_to_chinese' => $type_to_chinese[$request->get('type')],
                'target'=> $request->get('target'),
            ]);
        }

    }

    public function update(Request $request)
    {

        if($request->get('event_name')!=''){
            if($request->get('event_name') == 'all_user' && $request->get('target') == 'all' && $request->get('content')){
                //全站站內通知系統（所有帳號）
                $notice_event = NoticeManagers::where('event', $request->get('event_name'));
                $data = [
                    'email_content' => $request->get('content'),
                    'sms_content' => $request->get('content')
                ];
                if(!empty($notice_event->get()->toArray())){
                    $notice_event->update($data);
                }else{
                    $data += [
                        'target' => $request->get('target'),
                        'event' => $request->get('event_name'),
                        'email_activated' => 'Y',
                        'sms_activated' => 'Y'
                    ];
                    $notice_event = NoticeManagers::create($data);
                }
                $passed_data['content'] = $request->get('content');
                $this->trigger_event_center('all_user', 'all', $passed_data);
            }else{

                $sms_activated = '';
                $email_activated = '';
                if($request->get('type') == 'email'){
                    if($request->get('email_activated') != 'Y'){
                        $email_activated = 'N';
                    }else{
                        $email_activated = 'Y';
                    }
                }else if($request->get('type') == 'sms'){
                    if($request->get('sms_activated') != 'Y'){
                        $sms_activated = 'N';
                    }else{
                        $sms_activated = 'Y';
                    }
                }


                $data = [
                    'event' => $request->get('event_name'),
                    'target' => $request->get('target'),
                    'email_subject' => $request->get('email_subject'),
                    'email_content' => $request->get('email_content'),
                    'email_activated' => $email_activated,
                    'sms_content' => $request->get('sms_content'),
                    'sms_activated' => $sms_activated,
                ];

                foreach($data as $key => $value){
                    if($value==''){
                        unset($data[$key]);
                    }
                }
                $notice_event = NoticeManagers::where('event', $data['event']);
                if(!empty($notice_event->get()->toArray())){
                    $notice_event->update($data);
                }else{
                    $notice_event = NoticeManagers::create($data);
                }

            }

        }

        return redirect()->back();
    }

    public function trigger_event_center($event = '', $target = array(), $data = array()){
        //TODO 新增事件處
        $event_to_notice_box_type = array(
            'register' => 'member_info',
        );

        $single_target = array();

//        target為針對角色
        if(!empty($target['admin'])){
            $single_target['admin'] = $target['admin'];
        }

        //全站通知
        if($target == 'all') {
            if ($target == 'all') {
                $single_target['user'] = 'all';
            }
        }

        if (!empty($single_target)) {
            //全站通知
            if (isset($single_target['user']) && $single_target['user'] == 'all') {
                $event_name = 'trigger_' . $event;
                $notice_box['owner'] = $single_target['user'];
                $notice_box['box_source_event'] = $event_name;
                $notice_box['box_type'] = $event_to_notice_box_type[$event];
                $notice_box['read_at'] = 'when';
                $this->$event_name($single_target, $notice_box, $data);
            } else {
                //一般通知
                foreach ($single_target as $role => $single_owner) {
                    $notice_box = array();
                    $notice_box['owner'] = $single_owner;
                    $notice_box['box_source_event'] = $event;
                    $notice_box['box_type'] = $event_to_notice_box_type[$event];
                    $notice_box['read_at'] = 'when';

                    //target對象格式統一，不論使用者；若無此對象，對象陣列為空陣列。
                    //$target = ['admin' => 該管理者陣列];
                    $event_name = 'trigger_' . $event;
                    $single_owner['role'] = $role;
                    $this->$event_name($single_owner, $notice_box, $data);
                }
            }
        }
    }

    //特別事件-全站通知
    public function trigger_all_user($single_target = array(), $notice_box = array(),  $data = array()){
        $notice_manager = NoticeManagers::where('event', 'all_user')->get()->toArray();

        if(
            !empty($notice_manager) &&
            !empty($single_target)
        ) {
            $default_content = '您好，此為全站訊息：'.$data['content'];
            $this->common_send_format($single_target, $notice_box, $notice_manager, $default_content);
        }
    }

    //TODO 新增事件處，可模仿一般事件的function去新增


    //一般事件
    public function trigger_register($single_target = array(), $notice_box = array(), $data = array()){
        $notice_manager = NoticeManagers::where('event', 'register')->get()->toArray();
        if(
            !empty($notice_manager) &&
            !empty($single_target)
        ) {

            $default_content = '歡迎您！';
            $this->common_send_format($single_target, $notice_box, $notice_manager, $default_content);
        }

    }

    public function common_send_format($single_target=array(), $notice_box=array(), $notice_manager=array(), $default_content = '', $rec_email = '', $msg_content=''){
        //建立站內訊息
        $notice_box['box_content'] = $default_content;
        //全站通知
        if(isset($single_target['user']) && $single_target['user'] == 'all'){
            $notice_box['owner'] = 'all_user';
        }else{
            $notice_box['owner'] = $notice_box['owner']['id'];
        }

        //全站站內通知
        if(isset($single_target['user']) && $single_target['user'] == 'all'){
            //無站外信件觸發
            //無站外簡訊觸發
            $all_users = User::all();
            foreach ($all_users as $user){
                $notice_box['owner'] = $user->id;
                NoticeBoxes::create($notice_box);
            }

        //一般事件
        }else{
            NoticeBoxes::create($notice_box);

            //信件觸發
            if($notice_manager[0]['email_activated'] == 'Y' ){
                $receiver_email = $single_target['email'];
                $receiver_name = $single_target['name'];
                if($notice_manager[0]['email_subject'] != ''){
                    $email_subject = $notice_manager[0]['email_subject'];
                }else{
                    $email_subject = '預設信箱標題(自動發送)';
                }

                //TODO 設定SMTP資訊
//                此寄信方法採用.env裡面的設定值
//                MAIL_DRIVER=smtp
//                MAIL_HOST=smtp.gmail.com
//                MAIL_PORT=587
//                MAIL_ENCRYPTION=tls
//                MAIL_USERNAME=XXX@gmail.com
//                MAIL_PASSWORD=XXX

                $from = [
                    'email' => env('MAIL_USERNAME'),
                    'name' => '寄信者名稱',
                    'subject' => $email_subject
                ];
                //填寫收信人信箱
                $to = [
                    'email' => $receiver_email,
                    'name' => $receiver_name
                ];

                $data = [
                    'register_name' => $receiver_name,
                    'register_email' => $receiver_email,
                    'content' => $default_content.'<br><br>'.$notice_manager[0]['email_content'],
                ];

                //寄出信件
                $data += ['send_to' => 'user'];
                $this->send_email('template.notice_email_template', $data, '', $from, $to);
            }



            //簡訊觸發
            //簡訊部分有更動$default_content
            if($notice_manager[0]['sms_activated'] == 'Y' && $single_target['phone']){
                $this->send_sms($single_target['phone'], $default_content.' '.$notice_manager[0]['sms_content']);
            }

        }
    }

    public function send_email($template = '', $data = '', $message = '', $from = '', $to = ''){
        try {
            $be_sent_data['subject'] = $from['subject'];
            $be_sent_data['content'] = $data['content'];
            $be_sent_data['sender'] = $from['email'];
            $be_sent_data['receiver_email'] = $to['email'];
            $be_sent_data['receiver_name'] = $to['name'];
            dispatch(new SendNoticeEmailJob($be_sent_data));
            return "Email Sent!";
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function send_sms($phone = '', $message = ''){

        $string_index_https = strpos($message,'https://');
        $string_index_a_href = strpos($message,'">');
        $new_href = substr($message, $string_index_https,  $string_index_a_href-$string_index_https);
        $message = strip_tags( htmlspecialchars_decode($message) );
        $message = str_replace(" ","",$message);
        $message .= '('.$new_href.')';
        try {
            dispatch(new SendNoticeSMSJob(['phone' => $phone, 'message' => $message]));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function index(){}

    public function create(){}

    public function store(Request $request){}

    public function show(Request $request, $user_id=null){}

    public function destroy($id){}
}

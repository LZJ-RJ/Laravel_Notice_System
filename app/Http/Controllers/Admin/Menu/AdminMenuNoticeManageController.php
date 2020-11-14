<?php
// TODO : 需要建立後台側邊欄

namespace App\Http\Controllers\Admin\Menu;

use Illuminate\Http\Request;

class AdminMenuNoticeManageController
{
    public function __construct()
    {
        $this->name = trans('admin.notice.notice');

        $this->slug = 'notice';

        $this->iconClasses = 'nav-icon icon-wrench';
    }

    public function handle(Request $request)
    {
        //可調整顯示的順序用
        //TODO 新增事件處
        $event_array = array(
            array('register', array('user')),
        );

        //TODO 新增事件處
        $event_name_to_chinese = array(
            'register' => '註冊成功',
        );

        $event_target_to_chinese = array(
            'admin' => '管理員',
            'user' => '用戶',
        );
        return view('admin.setting.notice.notice')->with
        (
            compact('event_array', 'event_name_to_chinese', 'event_target_to_chinese')
        );
    }
}

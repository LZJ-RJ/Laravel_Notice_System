# Notice_System

## 設定
寄送信箱需在「.env」輸入信箱的SMTP資訊

寄送簡訊需在「app/Jobs/SendNoticeSMSJob.php」裡面輸入三竹簡訊API的帳密

## 流程

### 新增事件/角色
需要先在這些檔案的「TODO 新增事件處」中新增：
```php
//AdminMenuNoticeManageController.php

//NoticeManagerController.php

//NoticeManagerSeeder.php

//notification.blade.php
```

### 啟動/觸發通知系統
在該事件的觸發處增添這段程式碼來啟動通知系統：
```php
$noticeManager = new NoticeManagerController();
$admin_array = array();
$noticeManager->trigger_event_center('register', array('admin' => $admin_array));
```
* trigger_event_center()的第一欄位「register」為事件名稱。

* trigger_event_center()第二欄位「admin_array」為角色陣列，子項目要放入 User的Model，且轉成陣列型態(toArray())過後的
，之後此事件若要擴充角色可以從此處。


### 更新資料畫面
從「view」來看的話，會有一個「事件列表」，
還會有每個事件的「信箱」以及「簡訊」的內容編輯頁。

然後「事件列表」最下面會有個全站通知輸入框，這個單純為站內通知，屬於比較特殊的事件，
並不會寄送站外的通知(信箱、簡訊)。


### 其他
寄送外部通知時，皆為背景寄信，所以要使用Queue去做，可用command啟動Queue。

Queue指令參考：
* sudo php artisan queue:restart 
> 重啟Queue

* sudo php artisan queue:listen --tries=3 &
> 聆聽Queue(若失敗，最多嘗試三次。)

* sudo php artisan queue:work --tries=3 & 
> 啟動Queue(若失敗，最多嘗試三次。)

* ps -ef |grep artisan 
> 列出目前php artisan指令的程序有哪些

* sudo kill -9 4362 
> 刪除程序編號為 4362 的程序
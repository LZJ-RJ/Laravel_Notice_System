<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * 通知系統
 */
Route::get('notice', 'Admin/Menu/AdminMenuNoticeManagerController@handle')->name('notice.list'); //進入事件列表頁 (此應為後台側邊欄的初始進入畫面)
Route::get('notice/edit/{event}', 'NoticeManagerController@edit')->name('notice.edit'); //進入事件編輯頁
Route::post('notice/update', 'NoticeManagerController@update')->name('notice.update'); //更新通知系統的事件內容
Route::post('notice_box/update_read', 'NoticeBoxController@updateRead')->name('notice_box.update_read'); //已讀該使用者的通知訊息
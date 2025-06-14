<?php

use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Grid\Actions\Delete;

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Encore\Admin\Form::forget(['map', 'editor']);

// サイドバーメニュー表示調整
// // 以下のメニューは日本語に更新
$updates = [
	'Admin' => '管理項目',
	'Users' => '管理者一覧',
	'Roles' => '権限管理',
	'Menu' => 'メニュー管理',
];

foreach ($updates as $en => $ja) {
	Menu::where('title', $en)->update(['title' => $ja]);
}

// // 以下のメニューは非表示
Menu::whereIn('uri', [
	'Dashboard',
	'auth/permissions',
	'auth/permissions',
	'auth/logs',
])->delete();

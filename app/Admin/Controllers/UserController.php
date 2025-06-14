<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'User';

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		$grid = new Grid(new User());

		$grid->column('id', 'Id');
		$grid->column('name', '名前');
		$grid->column('email', 'メールアドレス');
		$grid->column('postal_code', '郵便番号');
		$grid->column('address', '住所');
		$grid->column('phone', '電話番号');
		$grid->column('stripe_id', '有料会員ID');
		$grid->column('created_at', '作成日')->sortable();
		$grid->column('updated_at', '最終更新日')->sortable();
		$grid->column('deleted_at', '削除日')->sortable();
		$grid->filter(function ($filter) {
			$filter->like('name', '名前');
			$filter->like('email', 'メールアドレス');
			$filter->like('postal_code', '郵便番号');
			$filter->like('address', '住所');
			$filter->like('phone', '電話番号');
			$filter->between('created_at', '作成日')->datetime();
			$filter->scope('trashed', '削除済みユーザー')->onlyTrashed();
		});

		$grid->disableCreateButton();
		$grid->disableExport();
		$grid->actions(function ($actions) use ($grid) {
			$scope = request('_scope_');
			if ($scope !== 'trashed') {
				$actions->disableEdit();
			}
			if ($scope === 'trashed') {
				$actions->disableView();
			}
			$actions->disableDelete();
		});

		return $grid;
	}

	/**
	 * Make a show builder.
	 *
	 * @param mixed $id
	 * @return Show
	 */
	protected function detail($id)
	{
		$show = new Show(User::findOrFail($id));

		$show->field('id', 'Id');
		$show->field('name', '名前');
		$show->field('email', 'メールアドレス');
		$show->field('postal_code', '郵便番号');
		$show->field('address', '住所');
		$show->field('phone', '電話番号');
		$show->field('stripe_id', '有料会員ID');
		$show->field('pm_type', 'カード種類');
		$show->field('pm_last_four', 'カード番号');
		$show->field('created_at', '作成日');
		$show->field('updated_at', '最終更新日');
		$show->field('deleted_at', '削除日');

		return $show;
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form()
	{
		$form = new Form(new User());

		$form->datetime('deleted_at', __('Deleted at'))->default(NULL);

		return $form;
	}
}

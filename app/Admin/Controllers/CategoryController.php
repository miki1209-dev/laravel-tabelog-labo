<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CategoryController extends AdminController
{
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Category';

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		$grid = new Grid(new Category());

		$grid->column('id', 'ID');
		$grid->column('name', 'カテゴリ名');
		$grid->column('description', 'カテゴリ説明');
		$grid->column('is_featured', '注目');
		$grid->column('created_at', '登録日')->sortable();
		$grid->column('updated_at', '最終更新日')->sortable();
		$grid->filter(function ($filter) {
			$filter->like('name', 'カテゴリ名');
			$filter->like('description', 'カテゴリ説明');
			$filter->equal('is_featured', '注目')->select([0 => '注目なし', 1 => '注目あり']);
		});

		$grid->disableExport();

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
		$show = new Show(Category::findOrFail($id));

		$show->field('id', 'ID');
		$show->field('name', 'カテゴリ名');
		$show->field('description', 'カテゴリ説明');
		$show->field('file_name', 'カテゴリ画像');
		$show->field('is_featured', '注目');
		$show->field('created_at', '登録日');
		$show->field('updated_at', '最終更新日');

		return $show;
	}

	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form()
	{
		$form = new Form(new Category());

		$form->text('name', 'カテゴリ名');
		$form->textarea('description', 'カテゴリ説明');
		$form->image('file_name', 'カテゴリ画像')->disk('admin')->move('categories')->uniqueName();
		$form->switch('is_featured', '注目');

		return $form;
	}
}

<?php

namespace App\Admin\Controllers;

use App\Models\Shop;
use App\Models\Category;
use Illuminate\Support\Facades\Request;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;


class ShopController extends AdminController
{
	protected $input;
	/**
	 * Title for current resource.
	 *
	 * @var string
	 */
	protected $title = 'Shop';

	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		$grid = new Grid(new Shop());

		$grid->column('id', 'ID');
		$grid->column('name', '店舗名');
		$grid->column('address', '住所');
		$grid->column('phone_number', '電話番号');
		$grid->column('description', '店舗説明');
		$grid->column('opening_time', '開店時間');
		$grid->column('closing_time', '閉店時間');
		$grid->column('categories', 'カテゴリ名')->display(function ($categories) {
			return implode(', ', array_column($categories, 'name'));
		});
		$grid->column('created_at', '作成日')->sortable();
		$grid->column('updated_at', '最終更新日')->sortable();

		$grid->filter(function ($filter) {
			$filter->like('name', '店舗名');
			$filter->like('address', '住所');
			$filter->like('phone_number', '電話番号');
			$filter->like('description', '店舗説明');
			$filter->where(function ($query) {
				$input = $this->input;
				if (!empty($input)) {
					$query->whereHas('categories', function ($query) use ($input) {
						$query->whereIn('categories.id', $input);
					});
				}
			}, 'カテゴリ名')->multipleSelect(Category::pluck('name', 'id')->toArray());
			$filter->where(function ($query) {
				$input = $this->input;
				if ($input) {
					if (strlen($input) === 5) {
						$input .= ':00';
					}
					$query->whereTime('opening_time', '>=', $input);
				}
			}, '開店時間')->datetime(['format' => 'HH:mm']);
			$filter->where(function ($query) {
				$input = $this->input;
				if ($input) {
					if (strlen($input) === 5) {
						$input .= ':00';
					}
					$query->whereTime('closing_time', '<=', $input);
				}
			}, '閉店時間')->datetime(['format' => 'HH:mm']);
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
		$show = new Show(Shop::findOrFail($id));

		$show->field('id', 'ID');
		$show->field('name', '店舗名');
		$show->field('address', '住所');
		$show->field('phone_number', '電話番号');
		$show->field('description', '店舗説明');
		$show->field('opening_time', '開店時間');
		$show->field('closing_time', '閉店時間');
		$show->field('categories', 'カテゴリ名')->as(function ($categories) {
			return $categories->pluck('name')->join(', ');
		});
		$show->field('file_name', '店舗画像');
		$show->field('created_at', '作成日');
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
		$form = new Form(new Shop());

		$form->text('name', '店舗名');
		$form->text('address', '住所');
		$form->text('phone_number', '電話番号');
		$form->textarea('description', '店舗説明');
		$form->time('opening_time', '開店時間')->format('HH:mm');
		$form->time('closing_time', '閉店時間')->format('HH:mm');
		$form->multipleSelect('categories', ' カテゴリ名')->options(Category::pluck('name', 'id')->toArray());
		$form->image('file_name', '店舗画像')->disk('admin')->move('shops')->uniqueName();

		return $form;
	}
}

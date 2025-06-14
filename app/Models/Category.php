<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'description',
		'file_name',
		'is_featured'
	];

	public function shops()
	{
		return $this->belongsToMany(Shop::class, 'category_shop');
	}
}

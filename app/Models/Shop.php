<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;
use Illuminate\Support\Carbon;

class Shop extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'address',
		'phone_number',
		'description',
		'opening_time',
		'closing_time',
		'file_name',
	];

	/**
	 * @property \Illuminate\Database\Eloquent\Collection $categories
	 */

	public function categories()
	{
		return $this->belongsToMany(Category::class, 'category_shop');
	}

	public function favoredByUsers()
	{
		return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
	}

	public function reviews()
	{
		return $this->hasMany(Review::class);
	}

	public function getAverageScoreAttribute()
	{
		$avg = $this->reviews->avg('score');
		return $avg ? round($avg, 1) : null;
	}

	public function getRoundedScoreAttribute()
	{
		$avg = $this->reviews->avg('score');
		return $avg ? round($avg * 2) / 2 : null;
	}

	public function getFormattedOpeningTimeAttribute()
	{
		return Carbon::parse($this->opening_time)->format('G:i');
	}

	public function getFormattedClosingTimeAttribute()
	{
		return Carbon::parse($this->closing_time)->format('G:i');
	}

	public function getFormattedPhoneNumberAttribute()
	{
		$digits = preg_replace('/\D/', '', $this->phone_number);

		if (strlen($digits) === 11) {
			return substr($digits, 0, 3) . '-' . substr($digits, 3, 4) . '-' . substr($digits, 7);
		}
		return $this->phone_number;
	}
}

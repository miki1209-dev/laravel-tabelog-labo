<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Carbon;

class Reservation extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id',
		'shop_id',
		'visit_date',
		'visit_time',
		'number_of_people',
		'status',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function shop()
	{
		return $this->belongsTo(Shop::class);
	}

	public function getFormattedVisitDateAttribute()
	{
		return Carbon::parse($this->visit_date)->format('Y年m月d日');
	}
	public function getFormattedVisitTimeAttribute()
	{
		return Carbon::parse($this->visit_time)->format('G:i');
	}
}

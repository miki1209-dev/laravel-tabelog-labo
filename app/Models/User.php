<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Review;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
	use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Billable;

	protected $dates = ['deleted_at'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'postal_code',
		'address',
		'phone',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function favorite_shops()
	{
		return $this->belongsToMany(Shop::class, 'favorites')->withTimestamps();
	}

	public function reviews()
	{
		return $this->hasMany(Review::class);
	}
	public function isPremium()
	{
		return $this->subscribed('premium') && $this->subscription('premium')->valid();
	}
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
	/**
	 * Display the registration view.
	 */
	public function create(): View
	{
		return view('auth.register');
	}

	/**
	 * Handle an incoming registration request.
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request): RedirectResponse
	{
		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
			'password' => ['required', 'confirmed', Rules\Password::defaults()],
			'postal_code' => ['nullable', 'regex:/^\d{7}$|^\d{3}-\d{4}$/'],
			'address' => ['nullable', 'string', 'max:255'],
			'phone' => ['nullable', 'regex:/^(0\d{9,10}|0\d{1,4}-\d{1,4}-\d{4})$/'],
		]);

		try {
			$user = User::create([
				'name' => $request->name,
				'email' => $request->email,
				'password' => Hash::make($request->password),
				'postal_code' => $request->postal_code,
				'address' => $request->address,
				'phone' => $request->phone,
			]);

			event(new Registered($user));

			Auth::login($user);

			return redirect('/verify-email');
		} catch (QueryException $e) {
			Log::error('Database Error' . $e->getMessage());
			return back()->withErrors(['db_error' => 'データベースへの登録が失敗しました。時間をおいて再度試してみてください'])->withInput();
		} catch (Exception $e) {
			Log::error('General Error' . $e->getMessage());
			return back()->withErrors(['general_error' => '予期せぬエラーが発生しました'])->withInput();
		}
	}
}

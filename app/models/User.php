<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	// todo: improve the regex
	public static $rules = array(
		'id' => array('required', 'regex:/^20[0-9]{2}(((B[1-5])|(A[1-8]))|(B[1-5]A[1-8])|(A[0-8]B[1-5]))PS[0-9]{3}G$/', 'unique:users'),
		'name' => 'required',
		'mobile' => 'required|digits:10',
		'email' => 'required|email|regex:/^f20[0-9]{5}@goa.bits-pilani.ac.in$/|unique:users',
		'hostel' => array('required', 'regex:/^(AH[0-8])|(CH[0-6])$/'),
		'room_no' => 'required|numeric'
		);
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

}
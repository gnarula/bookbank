<?php
use Illuminate\Auth\UserInterface;

class Book extends Eloquent implements UserInterface{
	protected $table = 'books';
	protected $timestamp = false;
	protected $hidden = array('available');
	public static $rules = array(
		'id' => array('required','unique:books'),
		'name' => 'required',
		'branch' => 'required',
		'edition' => 'required|digits:3',
		'author' => 'alpha_num',	//Assuming only one author (It should be a foreign key of Author model)
		'available' = > 'numeric'
		);
}
?>
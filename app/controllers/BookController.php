<?php

class BookController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    public function postCreate() {
        $validator = Validator::make(Input::all(), Book::$rules);

        $data = array();

        if($validator->passes()) {
            $book = new Book;
            $book->id = Input::get('id');
            $book->name = Input::get('name');
            $book->branch = Input::get('branch');
            $book->edition = Input::get('edition');
            $book->author = Input::get('author');
            $book->available = '1';

            $book->save();
            $data['success'] = true;
            $data['message'] = 'book saved';
        }
        else {
            $data['success'] = false;
            $data['message'] = 'something went wrong';
        }
        return Response::json($data);
    }
}
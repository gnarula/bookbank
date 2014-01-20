<?php

class StudentController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth.student');
    }

    public function getIndex() {
        return View::make('student.index');
    }

    public function getBooks() {
        $user = Auth::user()->id;

        $books = User::find($user)->books;

        return Response::json($books);
    }
}

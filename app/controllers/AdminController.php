<?php

class AdminController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth.admin');
    }

    public function getIndex() {
        return View::make('admin.index');
    }

    public function postIssue() {
        $id = Input::get('id');
        $book_ids = explode(',', Input::get('booknos'));

        $data = array();

        foreach($book_ids as $book_id) {
            try {
                $issue = new BookIssued;
                $issue->user_id = $id;

                $book = Book::find($book_id);

                $issue->book_id = $book_id;
                $issue->issue_date = date('Y-m-d');
                $issue->return_date = date('Y-m-d', strtotime('+1 month'));

                $issue->save();

                // change availability
                $book->available = 0;
                $book->save();
            }
            // todo: figure out exact exception name
            // Exception if book is unavailable since book_id is primary key
            catch (Exception $e) {
                $data['success'] = false;
                $data['message'] = 'Please check the book id(s) and student id';

                return Response::json($data);
            }
        }
        $data['success'] = true;
        $data['message'] = 'Books issued successfully';

        return Response::json($data);
    }

    public function postReturn() {
        $book_ids = explode(',', Input::get('booknos'));

        foreach($book_ids as $book_id) {
            try {
                // make the book available
                $book = Book::findOrFail($book_id);
                $book->available = 1;
                $book->save();
            }
            catch (Exception $e) {
                $data['success'] = false;
                $data['message'] = 'Book ID '.$book_id.' not found';

                return Response::json($data);
            }
        }
        BookIssued::destroy($book_ids);
        $data['success'] = true;
        $data['message'] = 'Books returned!';

        return Response::json($data);
    }
}

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
        $data = array();

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

    public function postAdd() {
        $book_ids = explode(',', Input::get('booknos'));
        $data = array();

        foreach($book_ids as $book_id) {
            try {
                $book = new Book;
                $book->name = Input::get('name');
                $book->branch = Input::get('branch');
                $book->edition = Input::get('edition');
                $book->author = Input::get('author');

                $book->save();
            }
            catch (Exception $e) {
                $data['success'] = false;
                $data['message'] = 'An error occured. Check if the book no is unique';

                return Response::json($data);
            }
        }
        $data['success'] = true;
        $data['message'] = 'Books added successfully';

        return Response::json($data);
    }

    public function getEdit($book_id) {
        // todo - add exception for book not found
        $book = Book::findOrFail($book_id);

        return View::make('admin.edit', compact("book"));
    }

    public function postUpdate() {

        $book = Book::findorFail(Input::get('id'));

        $book->name = Input::get('name');
        $book->branch = Input::get('branch');
        $book->edition = Input::get('edition');
        $book->author = Input::get('author');

        $book->save();

        $data = array();
        $data['success'] = true;
        $data['message'] = 'Book updated';

        return Response::json($data);
    }

    public function getSearch() {
        return View::make('admin.search');
    }

    /*
        todo: cleanup
        Params expected (one or more) - book_id, book_name, student_name, student_id
    */
    public function postSearch() {
        $query = DB::table('books');
        $joined = false;
        $select = array('books.id', 'books.name', 'books.author', 'books.edition', 'books.branch', 'books.available');

        if(!empty(Input::get('book_id'))) {
            $query->where('books.id', '=', Input::get('book_id'));

            if(!empty(Input::get('book_name'))) {
               $query->where('books.name', 'like', '%'.Input::get('book_name').'%');
            }
            if(!empty(Input::get('student_id'))) {
                $query->join('books_issued', 'books_issued.book_id', '=', 'books.id')->join('users', 'books_issued.user_id', '=', 'users.id')
                    ->where('users.id', 'like', '%'.Input::get('student_id').'%');
                array_push($select, 'users.name as user_name', 'users.id as user_id');
                $joined = true;
            }
            if(!empty(Input::get('student_name'))) {
                if($joined) {
                    $query->where('users.name', 'like', '%'.Input::get('student_name').'%');
                }
                else {
                    $query->join('books_issued', 'books_issued.book_id', '=', 'books.id')->join('users', 'books_issued.user_id', '=', 'users.id')
                        ->where('users.name', 'like', '%'.Input::get('student_name').'%');
                    array_push($select, 'users.name as user_name', 'users.id as user_id');
                }
            }
        }
        else if(!empty(Input::get('book_name'))) {
            $query->where('books.name', 'like', '%'.Input::get('book_name').'%');

            if(!empty(Input::get('student_id'))) {
                $query->join('books_issued', 'books_issued.book_id', '=', 'books.id')->join('users', 'books_issued.user_id', '=', 'users.id')
                    ->select('users.name', 'users.id');
                $joined = true;
            }
            if(!empty(Input::get('student_name'))) {
                if($joined) {
                    $query->where('users.name', 'like', '%'.Input::get('student_name').'%');
                }
                else {
                    $query->join('books_issued', 'books_issued.book_id', '=', 'books.id')->join('users', 'books_issued.user_id', '=', 'users.id')->where('users', 'users.name', 'like', '%'.Input::get('student_name').'%');
                    array_push($select, 'users.name as user_name', 'users.id as user_id');
                }
            }
        }
        else if(!empty(Input::get('student_id'))) {
            $query->join('books_issued', 'books_issued.book_id', '=', 'books.id')->join('users', 'books_issued.user_id', '=', 'users.id')
                ->where('users.id', 'like', '%'.Input::get('student_id').'%');
            array_push($select, 'users.name as user_name', 'users.id as user_id');

            if(!empty(Input::get('student_name'))) {
                $query->where('users.name', 'like', '%'.Input::get('student_name').'%');
            }
        }
        else if(!empty(Input::get('student_name'))) {
            $query->join('books_issued', 'books_issued.book_id', '=', 'books.id')->join('users', 'books_issued.user_id', '=', 'users.id')
                ->where('users.name', 'like', '%'.Input::get('student_name'.'%'));
            array_push($select, 'users.name as user_name', 'users.id as user_id');
        }

        return Response::json($query->get($select));
    }
}

<?php

class StudentController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth.student');
    }

    public function getIndex() {
        return View::make('student.index');
    }
}

<?php

class AdminController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth.admin');
    }

    public function getIndex() {
        return View::make('admin.index');
    }
}

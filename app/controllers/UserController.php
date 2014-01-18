<?php

class UserController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth', array('only' => array('getLogout')));
    }

    public function postCreate() {
        $validator = Validator::make(Input::all(), User::$rules);

        $data = array();

        if($validator->passes()) {
            $user = new User;
            $user->email = Input::get('email');
            $user->id = Input::get('id');
            $user->name = Input::get('name');
            $user->mobile = Input::get('mobile');
            $user->hostel = Input::get('hostel');
            $user->room_no = Input::get('room_no');
            $user->password = Hash::make(Input::get('password'));

            $user->save();
            $data['success'] = true;
            $data['message'] = 'User Registered';
        }
        else {
            $data['success'] = false;
            $data['message'] = 'Details invalid or user has already registered';
        }
        return Response::json($data);
    }

    public function postLogin() {
        if(Auth::attempt(array('id' => Input::get('id'), 'password' => Input::get('password')))) {
            if(strcmp(Input::get('id'), 'admin') == 0) {
                return Redirect::to('admin/');
            }
            return Redirect::to('student/');
        }
        $data['success'] = false;
        $data['message'] = 'Invalid credentials';

        return Response::json($data);
    }

    public function getLogout() {
        Auth::Logout();
        return Redirect::to('/');
    }
}

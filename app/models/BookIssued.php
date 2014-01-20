<?php

class BookIssued extends Eloquent {
    protected $table = 'books_issued';
    protected $primaryKey = 'book_id';
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('User');
    }
}
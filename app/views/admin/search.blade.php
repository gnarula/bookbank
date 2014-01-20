{{ Form::open(array('url' => 'admin/search')) }}

<div>
    {{ Form::label('book_id', 'Book No') }}
    {{ Form::text('book_id') }}
</div>

<div>
    {{ Form::label('book_name', 'Book Name') }}
    {{ Form::text('book_name') }}
</div>

<div>
    {{ Form::label('student_id', 'Student ID') }}
    {{ Form::text('student_id') }}
</div>

<div>
    {{ Form::label('student_name', 'Student Name') }}
    {{ Form::text('student_name') }}
</div>

<div>
    {{ Form::submit('Search') }}
</div>
{{ Form::close() }}
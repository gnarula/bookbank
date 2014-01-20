{{ Form::model($book, array('url' => 'admin/update')) }}

<div>
    {{ Form::hidden('id') }}
</div>

<div>
    {{ Form::label('name', 'Book Name') }}
    {{ Form::text('name') }}
</div>

<div>
    {{ Form::label('branch', 'Book Branch') }}
    {{ Form::text('branch') }}
</div>

<div>
    {{ Form::label('edition', 'Book Edition') }}
    {{ Form::text('edition') }}
</div>

<div>
    {{ Form::label('author', 'Book Author') }}
    {{ Form::text('author') }}
</div>

<div>
    {{ Form::submit('Update') }}
</div>

{{ Form::close() }}
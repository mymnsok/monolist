@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-xs-offset-3 col-xs-6">
    <div class="panel panel-default">
      <div class="panel-body">
        {!! Form::open(['route' => 'login.post']) !!}
        
          <div class="panel-group">
            {!! Form::label('email', 'メールアドレス') !!}
            {!! Form::email('email', old('email'), ['class' => 'form-control']) !!}
          </div>
          
          <div class="panel-group">
            {!! Form::label('password', 'パスワード') !!}
            {!! Form::password('password', ['class' => 'form-control']) !!}
          </div>
          
          <div class="text-right">
            {!! Form::submit('ログイン',['class' => 'form-control']) !!}
          </div>
          
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection
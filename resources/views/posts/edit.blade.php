@extends('layouts.app')

@section('content')
    <h1>Create</h1>
    {!! Form::open(['action'=>['PostsController@update', $post->id], 'method'=>'POST','enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('title','Title')}}
            {{Form::text('title', $post->title,['class'=>'form-control', 'placeholder'=>'Title'])}}
        </div>
        <div class="form-group">
                {{Form::label('body','Body')}}
                {{Form::textArea('body', $post->body,['id' => 'article-ckeditor','class'=>'form-control', 'placeholder'=>'Body Text'])}}
            </div>

            <div class="form-group">
                    {{Form::file('cover_image')}}
                </div>
            {{-- Laravel will not allow you to use PUT method to update so you w
                will have to spoof/trick the system --}}
            {{Form::hidden('_method','PUT')}}
            {{Form::submit('Submit',['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection
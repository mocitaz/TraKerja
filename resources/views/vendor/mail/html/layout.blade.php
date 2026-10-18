@extends('emails.layouts.base')

@section('email_header')
{!! $header ?? '' !!}
@endsection

@section('email_content')
{!! Illuminate\Mail\Markdown::parse($slot) !!}
{!! $subcopy ?? '' !!}
@endsection

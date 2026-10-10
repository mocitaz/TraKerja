@extends('errors.error_layout')

@section('code', '429')
@section('title')
Let’s take a short pause
@endsection
@section('description')
Too many requests have been made in a short time.
@endsection
@section('recovery')
Wait a little before trying again. Repeated requests may extend the wait.
@endsection

@extends('errors.error_layout')

@section('code', '403')
@section('title')
This page is restricted
@endsection
@section('description')
Your account does not have permission to open this page.
@endsection
@section('recovery')
Check that you are using the correct account, or contact support if you need access.
@endsection

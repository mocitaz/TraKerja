@extends('errors.error_layout')

@section('code', '419')
@section('title')
Your session has expired
@endsection
@section('description')
We couldn’t verify this request. Your session may have expired.
@endsection
@section('recovery')
Sign in again before retrying. Any unsaved changes may need to be entered again.
@endsection

@extends('errors.error_layout')

@section('code', '500')
@section('title')
Something went wrong
@endsection
@section('description')
We couldn’t complete your request because of a server error.
@endsection
@section('recovery')
Try again later. If you were submitting a payment or saving data, check its status before repeating the action.
@endsection

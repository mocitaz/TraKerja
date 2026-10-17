@extends('legal.layout')

@section('title', __('Privacy Policy'))
@section('description', __('How your information is collected, used, and managed.'))
@section('document', 'privacy-policy')

@section('contents')
        <a href="#section-1"><span>01</span>{{ __('Introduction') }}</a>
        <a href="#section-2"><span>02</span>{{ __('Information We Collect') }}</a>
        <a href="#section-3"><span>03</span>{{ __('How We Use Information') }}</a>
        <a href="#section-4"><span>04</span>{{ __('Data Security') }}</a>
        <a href="#section-5"><span>05</span>{{ __('Your Rights and Choices') }}</a>
@endsection

@section('article')
    @include('legal.privacy-policy-content')
@endsection

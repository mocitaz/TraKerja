@extends('legal.layout')

@section('title', __('Terms of Service'))
@section('page_title', __('page-titles.terms'))
@section('description', __('The terms for using your TraKerja workspace.'))
@section('document', 'terms-of-service')

@section('contents')
        <a href="#section-1"><span>01</span>{{ __('Acceptance of Terms') }}</a>
        <a href="#section-2"><span>02</span>{{ __('Description of Service') }}</a>
        <a href="#section-3"><span>03</span>{{ __('User Accounts') }}</a>
        <a href="#section-4"><span>04</span>{{ __('Acceptable Use') }}</a>
        <a href="#section-5"><span>05</span>{{ __('Security & Privacy Guarantees') }}</a>
        <a href="#section-6"><span>06</span>{{ __('Contact Us') }}</a>
@endsection

@section('article')
    @include('legal.terms-of-service-content')
@endsection

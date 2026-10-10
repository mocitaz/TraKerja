@extends('legal.layout')

@section('title', 'Terms of Service')
@section('description', 'The terms for using your TraKerja workspace.')
@section('document', 'terms-of-service')

@section('contents')
        <a href="#section-1"><span>01</span>Acceptance of Terms</a>
        <a href="#section-2"><span>02</span>Description of Service</a>
        <a href="#section-3"><span>03</span>User Accounts</a>
        <a href="#section-4"><span>04</span>Acceptable Use</a>
        <a href="#section-5"><span>05</span>Security &amp; Privacy Guarantees</a>
        <a href="#section-6"><span>06</span>Contact Us</a>
@endsection

@section('article')
    @include('legal.terms-of-service-content')
@endsection

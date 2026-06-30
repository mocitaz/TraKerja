@extends('errors.error_layout')

@section('code', '429')
@section('title', 'Terlalu Banyak Request')
@section('icon', 'ph-gauge')
@section('description', 'Tenang bro! Server mendeteksi terlalu banyak permintaan dari perangkat Anda dalam waktu singkat. Harap tunggu beberapa saat sebelum mencoba kembali.')

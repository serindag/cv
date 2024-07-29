@extends('frontend.layouts.master')

@section('content')
    @include('frontend.pages.home')
    @include('frontend.pages.about')
    @include('frontend.pages.service')
    @include('frontend.pages.skills')
    @include('frontend.pages.portfolio')
    @include('frontend.pages.feedback')
    @include('frontend.pages.blog')
    @include('frontend.pages.contact')
@endsection
@extends('layouts.app')

@section('content')
    <home-page :ltds="{{ json_encode($ltds) }}"></home-page>
@endsection

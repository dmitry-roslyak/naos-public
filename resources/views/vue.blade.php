@extends('layouts.app') @section('content')
<div id='app'>
    <app-header></app-header>
    <transition name="redirect">
        <router-view></router-view>
    </transition>
</div>
@endsection
@extends('layouts.app') @section('content')
<app-header style="max-width: 80em;margin-bottom:15px"></app-header>
<transition name="redirect">
    <router-view></router-view>
</transition>
@endsection
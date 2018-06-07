@extends('layouts.app') @section('content')
<transition name="redirect">
        <router-view></router-view>
</transition>
<footer class="footer">
        <a class="thumbnail"  href="https://laravel.com">
                <img src="/images/LaravelLogo.png" style="height:28px">
                Laravel
        </a>
        <a class="thumbnail"  href="https://Vuejs.org">
                <img src="/images/Vue.png" style="height:32px">
                Vue.js
        </a>
        <a class="thumbnail"  href="https://getbootstrap.com/docs/3.3/">
                <img src="/images/Boostrap_logo.svg.png"  style="height:28px">
                Bootstrap
        </a>
        <h5>Copyright&nbsp;&copy;&nbsp;2017&nbsp;Roslyak Dmitry</h5>
</footer>
@endsection
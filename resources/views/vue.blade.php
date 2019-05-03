@extends('layouts.app') @section('content')
<app-header style="max-width: 80em;margin-bottom:15px"></app-header>
<transition name="redirect">
    <router-view></router-view>
</transition>
<footer class="footer">
    <div>
        <table class="hidden-xs">
            <tbody>
                <tr>
                    <td>
                        <a href="https://laravel.com" target="_blank">
                            <img src="/images/LaravelLogo.png">
                        </a>
                    </td>
                    <td>
                        <a href="https://Vuejs.org" target="_blank">
                            <img src="/images/Vue.png">
                        </a>
                    </td>
                    <td>
                        <a href="https://webpack.js.org/" target="_blank">
                            <img src="https://webpack.js.org/d19378a95ebe6b15d5ddea281138dcf4.svg">
                        </a>
                    </td>
                    <td>
                        <a href="https://sass-lang.com/" target="_blank">
                            <img alt="Sass" src="https://sass-lang.com//assets/img/logos/logo-b6e1ef6e.svg">
                        </a>
                    </td>
                    <td>
                        <a href="https://firebase.google.com/products/auth/" target="_blank">
                            <img src="https://www.gstatic.com/mobilesdk/160503_mobilesdk/logo/2x/firebase_96dp.png" alt="Firebase">
                        </a>
                    </td>
                    <td>
                        <a href="https://getbootstrap.com/docs/3.3/" target="_blank">
                            <img src="/images/Boostrap_logo.svg.png">
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <h5>
            <a href="mailto:dmitri.roslyak@gmail.com" target="_blank"><i class="fa fa-envelope"></i></a>
            <a href="https://t.me/dmitry_roslyak" target="_blank"><i class="fa fa-telegram"></i></a>
            <a href="skype:f1-forever?chat" target="_blank"><i class="fa fa-skype"></i></a>
        </h5>
        <h5 class="copyright">&copy;&nbsp;2017-2019&nbsp;Roslyak Dmitry</h5>
    </div>
</footer>
@endsection
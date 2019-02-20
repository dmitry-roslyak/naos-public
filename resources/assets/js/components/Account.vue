<template>
    <div class="container-fluid" style="max-width: 80em">
        <div class="container-fluid">
            <div class="dropdown">
                <i class="fa fa-money"></i>
                <label>&nbsp;{{lng.currency_type}} :&nbsp;&nbsp;</label>
                <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true">
                    <span style="vertical-align:middle;">&nbsp;{{lng.currency}}&nbsp;</span><span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="fake-link" @click="get_currency('UAH')"><span>&nbsp;&nbsp;{{lng.UAH}}</span></a></li>
                    <li><a class="fake-link" @click="get_currency('RUB')"><span>&nbsp;&nbsp;{{lng.RUB}}</span></a></li>
                    <li><a class="fake-link" @click="get_currency('USD')"><span>&nbsp;&nbsp;{{lng.USD}}</span></a></li>
                </ul>
            </div>
        </div>
        <hr style="margin:8px 0">
        <user-info></user-info> 
        <!-- <hr>
        <div class="row">
            <span class="glyphicon glyphicon-lock"></span>
            <a @click="pass_reset?pass_reset=false:pass_reset=true">{{pass_reset?"Отмена":"Изменить пароль"}}</a>
            <div v-if="pass_reset">
                <span>Старый пароль</span>
                <input v-model="pswd" class="form-control myinput1">
                <span>Новый пароль</span>
                <input v-model="pswd" class="form-control myinput1">
                <span>Подтвердить новый пароль</span>
                <input v-model="pswd" class="form-control myinput1">
                <button class="btn btn-primary" style="margin-top:10px">Изменить</button>   
            </div>
        </div> -->
        <hr style="margin:8px 0">
        <div class="container-fluid">
            <i class="fa fa-envelope"></i>
            <!--<span class="glyphicon glyphicon-envelope"></span>-->
            <label>{{lng.email_me}} :</label>
            <div class="container-fluid">
                <div class="checkbox disabled">
                    <label>
                        <!--<input v-bind:checked="" type="checkbox">-->
                        <input type="checkbox" disabled>
                        {{lng.email_me_offers}}
                    </label>
                    </div>
                    <div class="checkbox">
                    <label>
                        <!--<input v-bind:checked="" type="checkbox">-->
                        <input type="checkbox" disabled>
                        {{lng.email_me_wishlist}}
                    </label>
                </div>
            </div>
        </div>
        <!--<span>Разрешить хранение: </span>
        <div class="form-inline" style="padding:8px 0 0 16px">
            <span>личной информации:&nbsp;</span>
            <div class="radio">
                <label><input type="radio" name="r1" checked>&nbsp;Да</label>
                <label><input type="radio" name="r1">&nbsp;Нет</label>
            </div>
        </div> 
        <div class="form-inline" style="padding:8px 0 0 16px">
            <span>номера платежной карты (PAN):&nbsp;</span>
            <div class="radio">
                <label><input type="radio" name="r2" checked>&nbsp;Да</label>
                <label><input type="radio" name="r2">&nbsp;Нет</label>
            </div>
        </div>  -->
    </div>
</template>

<script>
    var self, data = {
        lng: {},
        pass_reset: false
    };
    export default {
        data: function () {return data;},
        mounted() {
            self = this;
            this.lng = window.lng;
        },
        methods: {
            get_currency(val){
                axios.get('/set_currency?val='+val).then(function (response) {
                    self.lng.currency = self.lng[response.data.name];
                    self.$store.commit('set_currency', response.data.rate);
                }); 
            }
        }
    }
</script>

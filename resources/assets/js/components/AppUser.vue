<template>
  <div class="container">
    <div class="container-fluid">
      <div class="dropdown">
        <i class="fa fa-money" />
        <label>{{ lng.currency_type }}:</label>
        <a class="dropdown-toggle fake-link" data-toggle="dropdown" aria-haspopup="true">
          {{ lng.currency }}
          <span class="caret" />
        </a>
        <ul class="dropdown-menu">
          <li v-for="currencyName in currencies" :key="currencyName">
            <a href="#" @click.prevent="fetchCurrency(currencyName)">
              {{ lng[currencyName] }}
            </a>
          </li>
        </ul>
      </div>
    </div>
    <hr style="margin:8px 0" />
    <app-user-info></app-user-info>
    <!-- <hr>
        <div class="row">
            <span class="glyphicon glyphicon-lock" />
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
    </div>-->
    <hr style="margin:8px 0" />
    <div class="container-fluid">
      <i class="fa fa-envelope" />
      <label>{{ lng.email_me }} :</label>
      <div class="container-fluid">
        <div class="checkbox disabled">
          <label>
            <!--<input v-bind:checked="" type="checkbox">-->
            <input type="checkbox" disabled />
            {{ lng.email_me_offers }}
          </label>
        </div>
        <div class="checkbox">
          <label>
            <!--<input v-bind:checked="" type="checkbox">-->
            <input type="checkbox" disabled />
            {{ lng.email_me_wishlist }}
          </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {
    AppUserInfo: () => import(/* webpackChunkName: "js/AppUserInfo-vue" */ "./AppUserInfo"),
  },
  data() {
    return { currencies: ["UAH", "USD"] };
  },
  computed: {
    lng() {
      return this.$root.lng;
    },
  },
  methods: {
    fetchCurrency(val) {
      axios.get("/set_currency?val=" + val).then((response) => {
        this.lng.currency = this.lng[response.data.name];
        this.$store.commit("set_currency", response.data.rate);
      });
    },
  },
};
</script>

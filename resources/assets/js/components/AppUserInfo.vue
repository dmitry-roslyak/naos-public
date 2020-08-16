<template>
  <div class="container-fluid">
    <i class="fa fa-id-card" />
    <label>{{ lng.personal_info }}</label>
    <a v-if="!guest" class="fake-link" @click="upd_usr_info()">
      <i class="fa fa-edit" />
      {{ edit ? lng.confirm : lng.edit }}
    </a>
    <div class="container-fluid">
      <table class="table user-info-table">
        <tbody>
          <tr>
            <td>{{ lng.fname + ", " + lng.lname }}</td>
            <td v-if="edit">
              <input id="name" v-model="userInfo.name" v-validate class="form-control myinput1" />
            </td>
            <td v-else>{{ userInfo.name }}</td>
            <td>
              <i :class="validate.name ? 'fa fa-check-circle' : 'fa fa-times'" />
            </td>
          </tr>
          <tr>
            <td>{{ lng.tel_number }}</td>
            <td v-if="edit">
              <input id="tel" v-model="userInfo.tel" v-validate class="form-control myinput1" maxlength="11" />
            </td>
            <td v-else>{{ userInfo.tel }}</td>
            <td>
              <i :class="validate.tel ? 'fa fa-check-circle' : 'fa fa-times'" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import Validator from "../validate";

var data = {
  edit: true,
  userInfo: {},
  guest: false,
  validate: {},
};
var validator = new Validator(
  {
    name: /(^\S{1,120}\s{1,5}\S{1,120}$)/,
    tel: /^(\d{3,11})$/,
  },
  data.validate
);

export default {
  data: function() {
    return data;
  },
  computed: {
    lng() {
      return this.$root.lng;
    },
  },
  created() {
    if (window.Laravel.user) {
      this.usr_info();
    } else {
      this.guest = true;
      this.edit = true;
    }
  },
  methods: {
    usr_info() {
      axios.get("/user_info").then((response) => {
        this.userInfo = response.data;
        this.$nextTick((params) => {
          this.edit = !validator.isValid();
        });
      });
    },
    upd_usr_info() {
      if (!this.edit) this.edit = true;
      else if (validator.isValid()) {
        this.edit = false;
        axios.post("/update_user_info", { user: this.userInfo }).then(() => {
          this.$root.$data.user = this.userInfo.name;
        });
      }
    },
  },
};
</script>

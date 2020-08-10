<template>
    <div class="container-fluid">
        <i class="fa fa-id-card"></i> &nbsp;<label>{{lng.personal_info}}</label>&nbsp;
        <a class="fake-link" @click="upd_usr_info()" v-if="!guest">
            <i class="fa fa-edit"></i>&nbsp;{{edit?lng.confirm:lng.edit}}
        </a>
        <div class="container-fluid">
            <table class="table user-info-table">
                <tbody>
                    <tr>
                        <td>{{lng.fname+', '+lng.lname}}</td>
                        <td v-if="edit"><input v-model="userInfo.name" v-validate id="name" class="form-control myinput1"></td>
                        <td v-else>{{userInfo.name}}</td>
                        <td><i :class="validate.name ? 'fa fa-check-circle' : 'fa fa-times'"></i></td>
                    </tr>
                    <tr>
                        <td>{{lng.tel_number}}</td>
                        <td v-if="edit"><input v-model="userInfo.tel" v-validate id="tel" class="form-control myinput1" maxlength="11"></td>
                        <td v-else>{{userInfo.tel}}</td>
                        <td><i :class="validate.tel ? 'fa fa-check-circle' : 'fa fa-times'"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import Validator from "../validate"
    var self,
        data = {
            edit: true,
            userInfo: {},
            guest: false,
            validate: {}
        },
        validator = new Validator({
            name: /(^\S{1,120}\s{1,5}\S{1,120}$)/,
            tel: /^(\d{3,11})$/
        }, data.validate);

    export default {
        data: function () { return data; },
        computed: {
            lng(){ return this.$root.lng },
        },
        created() {
            self = this; 
            if (window.Laravel.user) {
                this.usr_info();
            }
            else {
                this.guest = true;
                this.edit = true;
            }
        },
        methods: {
            usr_info() {
                axios.get('/user_info').then(function (response) {
                    self.userInfo = response.data;
                    self.$nextTick(function (params) {
                        self.edit =  !validator.isValid();
                    })
                });
            },
            upd_usr_info() {
                if (!self.edit) self.edit = true;
                else if(validator.isValid()){
                    self.edit = false;
                    axios.post('/update_user_info', { user: self.userInfo, }).then(function () {
                        self.$root.$data.user = self.userInfo.name;
                    });
                }
            }
        }
    }

</script>
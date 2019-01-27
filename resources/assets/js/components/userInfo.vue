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
                        <td>{{lng.fname}}</td>
                        <td v-if="edit"><input v-model="userInfo.fname" class="form-control myinput1"></td>
                        <td v-else>{{userInfo.fname}}</td>
                    </tr>
                    <tr>
                        <td>{{lng.lname}}</td>
                        <td v-if="edit"><input v-model="userInfo.lname" class="form-control myinput1"></td>
                        <td v-else>{{userInfo.lname}}</td>
                    </tr>
                    <tr>
                        <td>{{lng.tel_number}}</td>
                        <td v-if="edit"><input v-model="userInfo.tel" class="form-control myinput1"></td>
                        <td v-else>{{userInfo.tel}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    var self, data = {
        lng: {},
        edit: true,
        userInfo: {},
        guest: false
    };
    export default {
        data: function () { return data; },
        created() {
            self = this; 
            this.lng = window.lng;
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
                    if (self.userInfo.fname) self.edit = false;
                }).catch(function (error) {
                });
            },
            upd_usr_info() {
                if (!self.edit) self.edit = true;
                else {
                    self.edit = false;
                    axios.post('/update_user_info', { user: self.userInfo, }).catch(function (error) {
                    });
                }
            }
        }
    }

</script>
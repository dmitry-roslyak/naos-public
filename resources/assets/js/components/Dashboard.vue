<template>
    <div class="container-fluid">
        <ul class="nav nav-tabs" style="margin: 0 30px">
            <li role="presentation" v-if="add==false" class="active"><a style="background-color: white">Показать</a></li>
            <li role="presentation" v-else><a @click="add=false">Показать</a></li>
            <li role="presentation" v-if="add" class="active"><a style="background-color: white">Добавить</a></li>
            <li role="presentation" v-else><a @click="add=true">Добавить</a></li>
        </ul>
        <div class="row" style="margin: 0 30px;border:1px solid #ddd;border-width:0 1px 1px">
        <div v-if="!add">
            <table v-if="items_list" class="table" >
                <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Name</th> 
                        <th>Price</th>
                        <th>Description</th>
                        <th>Discount ID</th>
                        <th>Select</th>
                        <th>Edit</th>
                    </tr>
                    <!--{{prod[0].discount.discount}}-->
                    <tr v-for="item in items_list" >
                        <td>{{item.id}}</td>
                        <td>{{item.ctg.name}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.price}}</td>
                        <td :title="item.description" style="width: 18%;"><div class="short-txt">{{item.description}}</div></td>
                        <td>{{item.discount_id}}</td>
                        <td><div class="checkbox" style="margin-top:2px;margin-bottom:2px">
                            <input style="height:1em" type="checkbox" @click="">
                        </div></td>
                        <td><a>Edit</a></td>
                    </tr>
                    <!--<div v-if="specs.category=='dimension'"></div>-->
                </tbody>
            </table>
            </div>
            <div v-if="add" class="col-md-12" style="margin:15px">
                <!--<form class="form-horizontal" action="upload" role="form" method="post" enctype="multipart/form-data">-->
                    <input type="hidden" name="_token" :value="csrf">
                    <div class="form-group">
                        <label class="col-md-2">Изображение</label>
                        <input accept="image/*" name="image" type="file" required>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Название</label>
                        <input name="name" required>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Цена Грн</label>
                        <input name="price" type="number" required>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Количество</label>
                        <input name="count" type="number" value="10" required>
                    </div>
                    <div class="form-group">
                        <div class="dropdown">
                            <label>Категория</label>
                            <input name="category_id" v-show="false" v-model="category_id">
                            <a href="#" class="dropdown-toggle" style="text-decoration: none;"
                                data-toggle="dropdown" aria-haspopup="true">
                                <span style="vertical-align:middle;">&nbsp;{{category_id?category_id:"Нет"}}&nbsp;</span><span class="caret"></span>
                            </a>
                            <!-- <ul class="dropdown-menu" >
                                <li v-for="(val,i1) in ctg_list"><a @click="category_id=val.id;get_ctgSpec()">
                                    &nbsp;&nbsp;{{val}}</a>
                                </li>
                            </ul> -->
                            <a v-if="category_id" @click="category_id=null" style="text-decoration:none;color:black;cursor:pointer"><sup>&nbsp;Х</sup></a>
                        </div>
                    </div>
                       <form class="form-horizontal" action="tu" role="form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" :value="csrf">
                    <table class="table" >
                        <tbody>
                            <tr v-for="item in ctgSpecCustom">
                                <td><input name="specs[name][]"></td>
                                <td><input name="specs[value][]"></td>
                                <td><input name="specs[type][]"></td>
                                <td @click="t1add">Добавить</td>
                            </tr>
                            <tr v-for="item in ctgSpec">
                                <td><input name="specs[name][]" :value="item.name"></td>
                                <td><input name="specs[value][]"></td>
                                <td><input name="specs[type][]" :value="item.val_type"></td>
                                <td>Удалить</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            Добавить
                        </button>
                    </div>
                </form>
                    <div class="form-group">
                        <div class="dropdown">
                            <label>Скидка</label>
                            <input name="discount_id" v-show="false" v-model="discount_id">
                            <a href="#" class="dropdown-toggle" style="text-decoration: none;"
                                data-toggle="dropdown" aria-haspopup="true">
                                <span style="vertical-align:middle;">&nbsp;{{discount_id?discount_id:"Нет"}}&nbsp;</span><span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" v-if="ready">
                                <li v-for="(val,i1) in discount_list"><a @click="discount_id=val.id">
                                    &nbsp;&nbsp;{{val}}</a>
                                </li>
                            </ul>
                            <a v-if="discount_id" @click="discount_id=null" style="text-decoration:none;color:black;cursor:pointer"><sup>&nbsp;Х</sup></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Описание</label>
                        <textarea style="width:100%" name="desc" :value="dummyText" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            Добавить
                        </button>
                    </div>
                <!--</form>-->
            </div>
        </div>
    </div>
</template>

<script>
var data={
    img:null,
    csrf:null,
    items_list:null,
    discount_list:null,
    discount_id:null,
    category_id:null,
    ctgSpec:null,
    ctgSpecCustom:[0],
    ready:false,
    add:true,
    dummyText:"Questions explained agreeable preferred strangers too him her son. Set put shyness offices his females him distant. Improve has message besides shy himself cheered however how son. Quick judge other leave ask first chief her. Indeed or remark always silent seemed narrow be. Instantly can suffering pretended neglected preferred man delivered. Perhaps fertile brandon do imagine to cordial cottage. "+
"Arrived compass prepare an on as. Reasonable particular on my it in sympathize. Size now easy eat hand how. Unwilling he departure elsewhere dejection at. Heart large seems may purse means few blind. Exquisite newspaper attending on certainty oh suspicion of. He less do quit evil is. Add matter family active mutual put wishes happen. "+
"Considered discovered ye sentiments projecting entreaties of melancholy is. In expression an solicitude principles in do. Hard do me sigh with west same lady. Their saved linen downs tears son add music. Expression alteration entreaties mrs can terminated estimating. Her too add narrow having wished. To things so denied admire. Am wound worth water he linen at vexed."+
"Feet evil to hold long he open knew an no. Apartments occasional boisterous as solicitude to introduced. Or fifteen covered we enjoyed demesne is in prepare. In stimulated my everything it literature. Greatly explain attempt perhaps in feeling he. House men taste bed not drawn joy. Through enquire however do equally herself at. Greatly way old may you present improve. Wishing the feeling village him musical. "+
"And produce say the ten moments parties. Simple innate summer fat appear basket his desire joy. Outward clothes promise at gravity do excited. Sufficient particular impossible by reasonable oh expression is. Yet preference connection unpleasant yet melancholy but end appearance. And excellence partiality estimating terminated day everything. "
};
var data_self,self;
    export default { 
        data: function () {return data},
        mounted() {
            data_self =this.$data;
            self=this;
            data_self.csrf = window.Laravel.csrfToken;
            // this.get_items_list();
            this.get_discount_list();
        },
        methods:{
            t1add(){
                
                data_self.ctgSpecCustom.push(0);
                console.log(data_self.ctgSpecCustom);
            },
            get_discount_list(){
                axios.get('/discount_list').then(function (response) {
                    data_self.discount_list = response.data;
                    data_self.ready =true;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            get_ctgSpec(){
                axios.get('/ctg_spec?id='+data_self.category_id).then(function (response) {
                    data_self.ctgSpec = response.data.specs;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            get_items_list(){
                axios.get('/t1').then(function (response) {
                    data_self.items_list = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            }
        }
    }
</script>
<style>
.short-txt{
    white-space: nowrap; width: 16em; overflow: hidden;text-overflow: ellipsis;
}
</style>


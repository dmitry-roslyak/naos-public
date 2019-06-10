<template>
    <div class="container-fluid">
        <h4 style="padding-left:8px">{{lng.comments+" "+paginator.total}}</h4>
        <button @click="stt($event.currentTarget)" class="btn btn-primary btn-add-comment">
            <i class="fa fa-angle-up pull-right" style="display:none" aria-hidden="true"></i>
            <i class="fa fa-angle-down pull-right" aria-hidden="true"></i>
            <i class="fa fa-comment-o" aria-hidden="true"></i>&nbsp;{{lng.to_comment}}
        </button>
        <div id="leaveMsg" class="thumbnail">
            <div class="caption">
                <div class="form-group">
                    <label>{{lng.comment}}</label>
                    <textarea class="form-control" v-model="message" rows="3"></textarea>
                </div>
                <button class="btn btn-default" style="width:100%" @click="leave_comment()">{{lng.to_comment}}</button>
            </div>
        </div>
        <div v-for="(comment,i) in comments" :key="comment.id" class="panel panel-default">
            <div class="panel-body" style="position:relative">
                <span class="text-primary">{{comment.user.name}}</span>
                <span style="top: 15px;right: 15px;position: absolute;">{{comment.created_at}}</span>
                <div style="margin: 10px 0">{{comment.message}}</div>
                <div class="like-tab fake-link" @click="comment_like(i,$event.target)">
                    <i :class="comment.vote && comment.vote.action == 'like' ? 'fa fa-thumbs-up like' : 'fa fa-thumbs-o-up like'">
                        {{comment.like}}
                    </i>
                    <i :class="comment.vote && comment.vote.action == 'dislike' ? 'fa fa-thumbs-up dislike' : 'fa fa-thumbs-o-up dislike'">
                        {{comment.dislike}}
                    </i>
                </div>
            </div>
        </div>
        <v-pagination v-model="paginator"></v-pagination>      
    </div>
</template>
<script>
    var self, data = {
        comments: [],
        message: '',
        paginator: {
            total: 0,
            take: 30,
            skip: 0,
            page: 1
        },
    };
    var formatter = new Intl.DateTimeFormat([] , {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "numeric"
    });
    export default {
        props: ['productId'],
        data: function() {return data},
        watch: {
            'paginator.skip': 'show_comments',
            'productId': 'show_comments',
        },
        computed: {
            lng(){ return this.$root.lng },
        },
        created() {
            self = this;
            window.webSocketPromise.then(webSocket => {
                webSocket.send(JSON.stringify({
                    "event": "pusher:subscribe",
                    "data": {
                        "channel": 'chat-product' + this.productId
                }}));
                webSocket.onmessage = function (event) {
                    var res = JSON.parse(event.data);
                    if(res.event=='new-message'){
                        self.paginator.total++;
                        var comment = JSON.parse(res.data);
                        comment.created_at = formatter.format(new Date(comment.created_at+'Z'));
                        self.comments.unshift(comment);
                    }
                }
            }).catch((event)=> {
                console.error(event);
            });
            this.show_comments();
        },
        methods: {
            stt(el){
                $("#leaveMsg").slideToggle();
                $(el.getElementsByClassName('fa-angle-up')[0]).toggle();
                $(el.getElementsByClassName('fa-angle-down')[0]).toggle();
            },
            comment_like(i,el) {
                axios.get('/comment_like', { 
                    params: {
                        id: this.comments[i].id,
                        action: el.classList.contains('like') ? 'like' : 'dislike'
                    }
                }).then(function(response) {
                    self.comments[i] = response.data;
                    self.comments[i].created_at = formatter.format(new Date(self.comments[i].created_at+'Z'));
                    self.$forceUpdate();
                }).catch(function() {}); 
            },
            show_comments() {
                axios.get('/all_comments?id='+this.productId+'&skip=' + self.paginator.skip).then(function(response) {
                    self.paginator.total = response.data[0];
                    self.comments = response.data[1];
                    for (var i = 0; i < self.comments.length; i++){
                        self.comments[i].created_at = formatter.format(new Date(self.comments[i].created_at+'Z'));
                    }
                });
            },
            leave_comment() {
                axios.post('/leave_comment', {
                    message: self.message,
                    pid: this.productId
                });
            }
        }
    }
</script>
<style>
    #leaveMsg{
        background-color: white;
        display: none;
        margin-top: -24px
    }
    .btn-add-comment{
        width: 100%;
        margin-bottom: 15px;
    }
    .like-tab {
        float: right;
    }
    .like {
        color: green;
    }
    .like:hover {
        text-shadow: 0.1em 0.1em 0.5em lightgreen;
    }
    .dislike {
        color: red;
    }
    .dislike:hover {
        text-shadow: 0.1em 0.1em 0.5em lightcoral;
    }
     .like-tab > .fa[class*="fa-thumbs"]{
        animation: bounce1 0.35s;
     }
     .like-tab > .fa[class*="fa-thumbs-o"]{
        animation: bounce0 0.35s;
     }
</style>
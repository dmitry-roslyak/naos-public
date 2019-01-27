<template>
    <div class="container-fluid">
        <h4 style="padding-left:8px">{{lng.comments+" "+paginator.total}}</h4>
        <button @click="stt($event.currentTarget)" class="btn btn-primary btn-add-comment">
            <i class="fa fa-angle-up font1 pull-right" style="display:none" aria-hidden="true"></i>
            <i class="fa fa-angle-down font1 pull-right" aria-hidden="true"></i>
            <i class="fa fa-comment-o font1" aria-hidden="true"></i>&nbsp;{{lng.to_comment}}
        </button>
        <div id="leaveMsg" class="thumbnail">
            <div class="caption">
                <div class="form-group">
                    <label>{{lng.to_rate}}</label>
                    <star-rating :star-size="16" @rating-selected="rating = $event" :rating="rating" :show-rating="false"></star-rating>
                </div>
                <div class="form-group" style="margin-bottom:0px">
                    <label>{{lng.comment}}</label>
                    <textarea class="form-control" v-model="message" rows="3"></textarea>
                    <button class="btn btn-default" style="margin-top:6px;width:100%" @click="leave_comment()">{{lng.to_comment}}</button>
                </div>
            </div>
        </div>
        <div v-for="(comment,i) in comments" :key="comment.id" class="panel panel-default">
            <div class="panel-body" style="position:relative">
                <span class="text-primary col-xs-12 col-sm-4">{{comment.user.name}}</span>
                <star-rating class="col-xs-12 col-sm-3" v-if="comment.rating" :rating="+comment.rating" :star-size="16" :show-rating="false" :read-only="true"></star-rating>
                <span class="pull-right" style="margin-right:15px">{{comment.created_at}}</span>
                <div class="col-xs-12" style="margin:10px 0 30px">{{comment.message}}</div>
                <!-- <a @click="reply=comment.id" v-if="comment.reply_id==0" class="fake-link">
                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                    &nbsp;{{lng.to_reply}}
                </a> -->
                <div class="like-tab" @click="comment_like(i,$event.target)">
                    <i v-if="comment.vote?comment.vote.is_liked>0:false" data-check="1" class="fa fa-thumbs-up like"></i>
                    <i v-else class="fa fa-thumbs-o-up like" data-check="0"></i>
                    <i>{{comment.like}}</i>&nbsp;
                    <i v-if="comment.vote?comment.vote.is_liked<0:false" data-check="1" class="fa fa-thumbs-down dislike"></i>
                    <i v-else class="fa fa-thumbs-o-down dislike" data-check="0"></i>
                    <i>{{comment.dislike}}</i>
                </div>
            </div>
        </div>
        <pagination v-model="paginator" class="col-xs-12"></pagination>      
    </div>
</template>
<script>
    var self, pid, data = {
        reply: null,
        comments: [],
        rating: 0,
        message: '',
        lng: {},
        paginator: {
            total: 0,
            take: 30,
            skip: 0,
            func: null
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
        mounted() {
            self = this;
            pid = this.$parent.$props.id;
            this.lng = window.lng;
            this.paginator.func = this.show_comments
            this.show_comments();
            window.socket.send(JSON.stringify({
                "event": "pusher:subscribe",
                "data": {
                    "channel": 'chat-product' + pid
            }}));
            window.socket.onmessage = function (event) {
                var res = JSON.parse(event.data);
                if(res.event=='new-message'){
                    self.paginator.total++;
                    var comment = JSON.parse(res.data);
                    comment.created_at = formatter.format(new Date(comment.created_at+'Z'));
                    self.comments.unshift(comment);
                }
            }
        },
        data: function() {return data},
        methods: {
            stt(el){
                $("#leaveMsg").slideToggle();
                $(el.getElementsByClassName('fa-angle-up')[0]).toggle();
                $(el.getElementsByClassName('fa-angle-down')[0]).toggle();
            },
            comment_like(i,el) {
                var like = el.classList.contains('like');
                if(like||el.classList.contains('dislike')){
                    el.getAttribute('data-check')>0 ? el.setAttribute('data-check','0') : el.setAttribute('data-check','1');
                    axios.get('/comment_like?id='+self.comments[i].id+'&x='+ (like ? 1 : -1)).then(function(response) {
                        self.comments[i] = response.data;
                        self.comments[i].created_at = formatter.format(new Date(self.comments[i].created_at+'Z'));
                        self.$forceUpdate();
                    }).catch(function(error) {
                    }); 
                } 
            },
            show_comments() {
                axios.get('/all_comments?id='+pid+'&skip=' + self.paginator.skip).then(function(response) {
                    self.paginator.total = response.data[0];
                    self.comments = response.data[1];
                    for (var i = 0; i < self.comments.length; i++){
                        self.comments[i].created_at = formatter.format(new Date(self.comments[i].created_at+'Z'));
                    }
                }).catch(function(error) {
                });
            },
            leave_comment() {
                axios.post('leave_comment', {
                    rating: self.rating,
                    message: self.message,
                    pid: pid,
                    cid: 0
                }).catch(function(error) {
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
        position: absolute;
        bottom: 15px;
        right: 60px;
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
     .like,.dislike[data-check="1"]{
        animation: bounce1 0.35s;
     }
     .dislike,.like[data-check="0"]{
        animation: bounce0 0.35s;
     }
</style>
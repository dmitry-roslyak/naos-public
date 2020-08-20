<template>
  <div class="container-fluid">
    <h4 style="padding-left:8px">{{ lng.comments + " " + paginator.total }}</h4>
    <button class="btn btn-primary btn-add-comment" @click="collapseToggle($event.currentTarget)">
      <i class="fa fa-angle-down pull-right" aria-hidden="true" />
      <i class="fa fa-comment-o" aria-hidden="true" />
      &nbsp;{{ lng.to_comment }}
    </button>
    <div id="leaveMsg" class="collapse">
      <div class="thumbnail">
        <div class="caption">
          <div class="form-group">
            <label>{{ lng.comment }}</label>
            <textarea v-model="message" class="form-control" rows="3" />
          </div>
          <button class="btn btn-default" style="width:100%" @click="leave_comment()">{{ lng.to_comment }}</button>
        </div>
      </div>
    </div>
    <div v-for="(comment, i) in comments" :key="comment.id" class="panel panel-default">
      <div class="panel-body" style="position:relative">
        <span class="text-primary">{{ comment.user.name }}</span>
        <span style="top: 15px;right: 15px;position: absolute;">{{ comment.created_at }}</span>
        <div style="margin: 10px 0">{{ comment.message }}</div>
        <div class="like-tab fake-link" @click="comment_like(i, $event.target)">
          <i :class="comment.vote && comment.vote.action == 'like' ? 'fa fa-thumbs-up like' : 'fa fa-thumbs-o-up like'">
            {{ comment.like }}
          </i>
          <i
            :class="
              comment.vote && comment.vote.action == 'dislike' ? 'fa fa-thumbs-up dislike' : 'fa fa-thumbs-o-up dislike'
            "
          >
            {{ comment.dislike }}
          </i>
        </div>
      </div>
    </div>
    <v-pagination v-model="paginator" />
  </div>
</template>
<script>
var data = {
  comments: [],
  message: "",
  paginator: {
    total: 0,
    take: 30,
    skip: 0,
    page: 1,
  },
};
var formatter = new Intl.DateTimeFormat([], {
  year: "numeric",
  month: "long",
  day: "numeric",
  hour: "numeric",
  minute: "numeric",
});
export default {
  components: {
    VPagination: () => import(/* webpackChunkName: "js/VPagination-vue" */ "./VPagination.vue"),
  },
  props: {
    productId: {
      type: [String, Number],
      required: true,
    },
  },
  data: function() {
    return data;
  },
  computed: {
    lng() {
      return this.$root.lng;
    },
  },
  watch: {
    "paginator.skip": "show_comments",
    productId: "show_comments",
  },
  created() {
    if (__NODE_ENV === "production") {
      const webSocket = new WebSocket(
        "wss://ws-eu.pusher.com:443/app/69e878ea5991b6099fb6?protocol=7&client=js&version=4.1.0&flash=false"
      );
      webSocket.onerror = (event) => console.warn(event);
      webSocket.onopen = () => {
        webSocket.send(
          JSON.stringify({
            event: "pusher:subscribe",
            data: {
              channel: "chat-product" + this.productId,
            },
          })
        );
      };
      webSocket.onmessage = (event) => {
        var res = JSON.parse(event.data);
        if (res.event === "new-message") {
          this.paginator.total++;
          var comment = JSON.parse(res.data);
          comment.created_at = formatter.format(new Date(comment.created_at + "Z"));
          this.comments.unshift(comment);
        }
      };
    } else console.warn("WebSocket disabled in development mode");

    this.show_comments();
  },
  methods: {
    collapseToggle(el) {
      $("#leaveMsg").collapse("toggle");
      el.getElementsByClassName("fa-angle-down")[0].classList.toggle("transform-rotate");
    },
    comment_like(i, el) {
      axios
        .get("/comment_like", {
          params: {
            id: this.comments[i].id,
            action: el.classList.contains("like") ? "like" : "dislike",
          },
        })
        .then((response) => {
          this.comments[i] = response.data;
          this.comments[i].created_at = formatter.format(new Date(this.comments[i].created_at + "Z"));
          this.$forceUpdate();
        })
        .catch(function() {});
    },
    show_comments() {
      axios.get("/comments?product_id=" + this.productId + "&skip=" + this.paginator.skip).then((response) => {
        this.paginator.total = response.data[0];
        this.comments = response.data[1];
        for (var i = 0; i < this.comments.length; i++) {
          this.comments[i].created_at = formatter.format(new Date(this.comments[i].created_at + "Z"));
        }
      });
    },
    leave_comment() {
      axios.post("/leave_comment", {
        message: this.message,
        pid: this.productId,
      });
    },
  },
};
</script>
<style>
#leaveMsg {
  background-color: white;
}
.btn-add-comment {
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
.like-tab > .fa[class*="fa-thumbs"] {
  animation: bounce1 0.35s;
}
.like-tab > .fa[class*="fa-thumbs-o"] {
  animation: bounce0 0.35s;
}
</style>

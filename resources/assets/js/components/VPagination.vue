<template>
  <div>
    <ul
      class="pagination fake-link"
      style="position:relative;left:50%;z-index:1;transform: translateX(-50%);"
    >
      <li :class="{'disabled': value.page == 1}">
        <a @click="value.page > 1 && goTo(value.page-1)">&laquo;</a>
      </li>
      <li v-show="value.page!=1">
        <a @click="goTo(1)">1</a>
      </li>
      <li>
        <a
          v-show="value.page>2&&value.page-2!=1&&value.page<4"
          @click="goTo(value.page-2)"
        >{{value.page-2}}</a>
        <a v-show="value.page>3">...</a>
      </li>
      <li v-show="value.page>1&&value.page-1!=1">
        <a @click="goTo(value.page-1)">{{value.page-1}}</a>
      </li>
      <li class="active">
        <a>{{value.page}}</a>
      </li>
      <li v-show="value.page+1<pageCount">
        <a @click="goTo(value.page+1)">{{value.page+1}}</a>
      </li>
      <li>
        <a v-show="value.page+2<pageCount" @click="goTo(value.page+2)">{{value.page+2}}</a>
        <a v-show="value.page<pageCount-3">...</a>
      </li>
      <li v-show="value.page!=pageCount">
        <a @click="goTo(pageCount)">{{pageCount}}</a>
      </li>
      <li :class="{'disabled': value.page == pageCount}">
        <a @click="value.page < pageCount && goTo(value.page+1)">&raquo;</a>
      </li>
    </ul>
  </div>
</template>
<script>
export default {
  props: {
    value: {
      type: Object,
      default: function () {
        return {
          paginator: {
            total: 0,
            take: 30,
            skip: 0,
            page: 1,
          },
        };
      },
    },
  },
  computed: {
    pageCount() {
      return Math.ceil(this.value.total / this.value.take) || 1;
    },
  },
  methods: {
    goTo(page) {
      this.value.page = page;
      this.value.skip = (this.value.page - 1) * this.value.take;
      window.scrollTo(0, 0);
      // window.scrollY = 0;
    },
  },
};
</script>

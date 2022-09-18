<template>
  <div class="card w-50 mx-auto mb-3">
    <div class="row p-2">
      <div class="d-flex align-items-center gap-3">
        <img :src="
          image
            ? `/storage/${image}`
            : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg'
        " class="rounded-circle profile-img" alt="" />

        <a :href="`/profile/${user.id}`" class="text-decoration-none"><b>{{ user.username }}</b></a>
        <button class="btn btn-primary" v-if="current != post.user_id && !follow" @click="handleFollow(post.user_id)">
          Follow
        </button>
      </div>
    </div>
    <img :src="'/storage/' + post.image" alt="" class="w-100" />
    <div class="card-body">
      <div class="d-flex align-items-center gap-3 mb-3">
        <font-awesome-icon :icon="isLike ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"
          style="font-size: 1.2rem; cursor: pointer;" @click="handleLike(post.id)" :class="isLike && 'red'" />
        <font-awesome-icon icon="fa-regular fa-comment" style="font-size: 1.2rem; cursor: pointer;" />
      </div>
      <div class="card-title">
        <b class="d-block">{{likeCount}} likes</b>
        <b>{{ user.username }}</b>
        <p class="ml-4 d-inline">{{ post.caption }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["post", "user", "image", "current", "follows", "like", "likecount"],
  data() {
    return {
      follow: this.follows,
      isLike: this.like,
      likeCount: this.likecount
    };
  },
  methods: {
    async handleFollow(id) {
      try {
        await axios.post(`/follow/${id}`);
        this.follow = !this.follow;
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
      }
    },
    async handleLike(id) {
      try {
        await axios.post(`/likes/${id}`);
        if (this.isLike) {
          this.likeCount = parseInt(this.likeCount) - 1;
        } else {
          this.likeCount = parseInt(this.likeCount) + 1;
        }
        this.isLike = !this.isLike;
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
      }
    },
  },

};
</script>

<style scoped>
.profile-img {
  width: 45px;
  height: 45px;
  object-fit: cover;
}

.line {
  transform: translateX(50%);
}

.red {
  color: red;
  transition: all 0.3s ease-in-out;
  transform: scale(1.2);
}
</style>
<template>
  <div>
    <div class="container w-50">
      <div class="row">
        <div class="col-8">
          <img :src="'/storage/' + post.image" alt="" class="w-100" />
        </div>
        <div class="col-4">
          <div class="d-flex flex-column">
            <div class="d-flex align-items-center gap-3">
              <img
                :src="
                  image
                    ? `/storage/${image}`
                    : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg'
                "
                class="rounded-circle profile-img"
                alt=""
              />

              <a :href="`/profile/${user.id}`" class="text-decoration-none"
                ><b>{{ user.username }}</b></a
              >
              <button
                class="btn btn-primary"
                v-if="current != post.user_id && !follow"
                @click="handleFollow(post.user_id)"
              >
                Follow
              </button>
            </div>
            <div class="mt-3">
              <b>{{ user.username }}</b>
              <p class="ml-4 d-inline">{{ post.caption }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr class="w-50 line" />
  </div>
</template>

<script>
export default {
  props: ["post", "user", "image", "current", "follows"],
  data() {
    return {
      follow: this.follows,
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
  },
};
</script>

<style scoped>
.profile-img {
  width: 50px;
  height: 50px;
  object-fit: cover;
}
.line {
  transform: translateX(50%);
}
</style>
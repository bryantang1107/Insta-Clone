<template>
  <div class="container" style="width: 70%">
    <div class="row">
      <div class="col-3 text-center align-self-center">
        <img
          :src="
            profile.image
              ? `/storage/${profile.image}`
              : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg'
          "
          class="rounded-circle profile-img"
          alt=""
        />
      </div>
      <div class="col-9">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div class="d-flex gap-3 align-items-center">
            <h4 class="mb-0">{{ user.username }}</h4>
            <template v-if="canview == 0">
              <button
                :class="follow ? 'btn btn-danger' : 'btn btn-primary'"
                @click="handleFollow(user.id)"
              >
                {{ follow ? "Unfollow" : "Follow" }}
              </button>
            </template>
          </div>

          <a href="/p/create" v-if="canview == 1">Make Post</a>
        </div>
        <a
          :href="'/profile/' + user.id + '/edit'"
          class="d-block mb-3"
          v-if="canview == 1"
        >
          Edit Profile
        </a>
        <div class="row mb-3">
          <div class="col-3 d-flex align-items-center gap-2">
            <span>{{ posts.length }}</span>
            <b>Posts</b>
          </div>
          <div class="col-3 d-flex align-items-center gap-2">
            <span>{{ followers }}</span>
            <b>Followers</b>
          </div>
          <div class="col-3 d-flex align-items-center gap-2">
            <span>{{ following }}</span>
            <b>Following</b>
          </div>
        </div>
        <h5>{{ profile.title }}</h5>
        <p>
          {{ profile.description }}
        </p>
        <a :href="profile.url">{{ profile.url }}</a>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-4 mt-3 mb-3" v-for="(post, index) in posts" :key="index">
        <a :href="'/p/' + post.id">
          <img :src="'/storage/' + post.image" class="post-img" alt="" />
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: [
    "user",
    "profile",
    "posts",
    "canview",
    "follows",
    "followercount",
    "followingcount",
  ],
  data() {
    return {
      followers: this.followercount,
      follow: this.follows,
      following: this.followingcount,
    };
  },
  mounted() {
    console.log("helo");
  },
  methods: {
    async handleFollow(id) {
      try {
        await axios.post(`/follow/${id}`);
        if (this.follow) {
          this.followers = parseInt(this.followers) - 1;
        } else {
          this.followers = parseInt(this.followers) + 1;
        }
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
  width: 150px;
  height: 150px;
  object-fit: cover;
  box-shadow: rgba(0, 0, 0, 0.17) 0px -23px 25px 0px inset,
    rgba(0, 0, 0, 0.15) 0px -36px 30px 0px inset,
    rgba(0, 0, 0, 0.1) 0px -79px 40px 0px inset, rgba(0, 0, 0, 0.06) 0px 2px 1px,
    rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px,
    rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
}

.post-img {
  width: 100%;
  box-shadow: rgba(0, 0, 0, 0.17) 0px -23px 25px 0px inset,
    rgba(0, 0, 0, 0.15) 0px -36px 30px 0px inset,
    rgba(0, 0, 0, 0.1) 0px -79px 40px 0px inset, rgba(0, 0, 0, 0.06) 0px 2px 1px,
    rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px,
    rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
}

.post-img:hover {
  transform: translateY(-5px);
  transition: transform 0.3s ease-in-out;
}
</style>
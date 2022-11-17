<template>
  <div class="container" style="width: 70%">
    <div class="row">
      <div class="col-3 text-center align-self-center">
        <img
          :src="
            user.profile.image
              ? `/storage/${user.profile.image}`
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
            <template>
              <button
                :class="requested ? 'btn btn-danger' : 'btn btn-primary'"
                @click="handleFollow(user.id)"
              >
                {{ requested ? "Requested" : "Follow" }}
              </button>
            </template>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-3 d-flex align-items-center gap-2">
            <span>{{ postslength }}</span>
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
        <h5>{{ user.profile.title }}</h5>
        <p>
          {{ user.profile.description }}
        </p>
        <a :href="user.profile.url">{{ user.profile.url }}</a>
      </div>
    </div>
    <hr class="mt-5" />
    <div
      class="d-flex align-items-center justify-content-center p-5 flex-column"
      style="margin-top: 10%"
    >
      <font-awesome-icon
        icon="fa-solid fa-lock"
        style="font-size: 5rem; color: rgba(0, 0, 0, 0.7)"
      />
      <p class="m-0 mt-4">This account is private.</p>
      <p class="m-0">Follow to see their photos and videos.</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  props: [
    "user",
    "postslength",
    "followercount",
    "followingcount",
    "followrequest",
  ],
  data() {
    return {
      followers: this.followercount,
      following: this.followingcount,
      requested: this.followrequest,
    };
  },
  methods: {
    async handleFollow(id) {
      try {
        await axios.post(`/follow/${id}`, {
          data: {
            is_following: 0,
            type: "request",
          },
        });
        if (this.requested) {
          this.$toast.open({
            message: `You have removed the request to follow ${this.user.username}`,
            type: "error",
            position: "top-right",
          });
        } else {
          this.$toast.open({
            message: `You have requested to follow ${this.user.username}`,
            type: "success",
            position: "top-right",
          });
        }
        this.requested = !this.requested;
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
        this.$toast.open({
          message: error.message,
          type: "error",
          position: "top-right",
        });
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
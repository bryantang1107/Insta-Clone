<template>
  <!-- User's profile -->
  <div class="container user-profile-page">
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
            <template v-if="!is_user && user.profile.is_private">
              <button
                class="btn btn-primary"
                @click="handleFollow(user.id)"
                v-if="!follow"
              >
                Follow
              </button>
              <button
                v-else
                class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#unfollowUser"
              >
                Unfollow
              </button>
            </template>
            <template v-else-if="!is_user">
              <button
                :class="follow ? 'btn btn-danger' : 'btn btn-primary'"
                @click="handleFollow(user.id)"
              >
                {{ follow ? "Unfollow" : "Follow" }}
              </button>
            </template>
            <div
              class="modal fade bd-example-modal-xl"
              id="unfollowUser"
              tabindex="-1"
              aria-labelledby="unfollowUserLabel"
              aria-hidden="true"
            >
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">
                      Unfollow <b>{{ user.username }}</b> ?
                    </h5>
                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"
                    ></button>
                  </div>
                  <div class="modal-body">
                    <p class="m-0 text-muted">
                      If you change your mind, you'll have to request to follow
                      <b>{{ user.username }}</b> again.
                    </p>
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-secondary"
                      data-bs-dismiss="modal"
                    >
                      Close
                    </button>
                    <button
                      type="submit"
                      class="btn btn-danger"
                      @click="handleFollow(user.id)"
                    >
                      Confirm
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <a href="/p/create" v-if="is_user">Make Post</a>
        </div>
        <a
          :href="'/profile/' + user.id + '/edit'"
          class="d-block mb-3"
          v-if="is_user"
        >
          Edit Profile
        </a>
        <div class="row mb-3">
          <div class="col-3 d-flex align-items-center gap-2">
            <span>{{ posts.length }}</span>
            <b>Posts</b>
          </div>
          <FollowerList
            :followers="followers"
            :user_id="user.id"
            :is_user="is_user"
            class="col-3"
          ></FollowerList>
          <FollowingList
            :following="following"
            class="col-3"
            :is_user="is_user"
            :user_id="user.id"
          ></FollowingList>
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
const FollowerList = () => import("./FollowerList.vue");
const FollowingList = () => import("./FollowingList.vue");
export default {
  components: { FollowerList, FollowingList },
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
      followers: parseInt(this.followercount),
      follow: parseInt(this.follows),
      following: parseInt(this.followingcount),
      is_user: this.canview ? true : false,
    };
  },
  methods: {
    async handleFollow(id) {
      try {
        await axios.post(`/follow/${id}`, {
          data: {
            is_following: this.follow,
            type: "follow",
          },
        });
        if (this.follow && this.user.profile.is_private) {
          window.location.reload();
        } else if (this.follow) {
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
.user-profile-page {
  width: 70%;
}

.profile-img {
  width: 75%;
  height: 75%;
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

@media (max-width: 1076px) {
  .user-profile-page {
    width: 100%;
  }
}
</style>
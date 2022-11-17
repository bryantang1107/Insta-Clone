<template>
  <!-- User's profile -->
  <div class="container user-profile-page">
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
            <template v-if="!is_user && user.profile.is_private">
              <button
                class="btn btn-primary"
                @click="handleFollow(user)"
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
                @click="handleFollow(user)"
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
                      @click="handleFollow(user)"
                    >
                      Confirm
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <a
              :href="'/profile/' + user.id + '/edit'"
              v-if="is_user"
              class="btn btn-dark"
            >
              Edit Profile
            </a>
            <a href="/p/create" v-if="is_user" class="btn btn-light">
              Make Post
            </a>
          </div>
        </div>

        <div class="mb-3 d-flex align-items-center" style="gap: 4em">
          <div class="d-flex align-items-center gap-2">
            <span>{{ posts.length }}</span>
            <b>Posts</b>
          </div>
          <FollowerList :user_id="user.id" :is_user="is_user"></FollowerList>
          <FollowingList :is_user="is_user" :user_id="user.id"></FollowingList>
        </div>
        <h5>{{ user.profile.title }}</h5>
        <p>
          {{ user.profile.description }}
        </p>
        <a :href="user.profile.url">{{ user.profile.url }}</a>
      </div>
    </div>
    <hr />
  </div>
</template>

<script>
import axios from "axios";
const FollowerList = () => import("./FollowerList.vue");
const FollowingList = () => import("./FollowingList.vue");
export default {
  components: { FollowerList, FollowingList },
  props: ["user", "posts", "follows", "followercount", "followingcount"],
  data() {
    return {
      follow: parseInt(this.follows),
    };
  },
  computed: {
    followers() {
      return this.$store.getters.getFollow;
    },
    following() {
      return this.$store.getters.getFollowing;
    },
    is_user() {
      return this.$store.getters.getUser.id === this.user.id ? true : false;
    },
  },
  methods: {
    async handleFollow(user) {
      try {
        await axios.post(`/follow/${user.id}`, {
          data: {
            is_following: this.follow,
            type: "follow",
          },
        });
        if (this.follow && user.profile.is_private) {
          window.location.reload();
        } else if (this.follow) {
          this.$store.dispatch("unfollowOtherUser");
          this.$toast.open({
            message: `You have unfollowed ${user.username}!`,
            type: "error",
            position: "top-right",
            queue: true,
          });
        } else {
          this.$store.dispatch("followOtherUser");
          this.$toast.open({
            message: `You are now following ${user.username}!`,
            type: "success",
            position: "top-right",
          });
        }
        this.follow = !this.follow;
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
  mounted() {
    this.$store.dispatch("setFollow", this.followercount);
    this.$store.dispatch("setFollowing", this.followingcount);
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

@media (max-width: 1076px) {
  .user-profile-page {
    width: 100%;
  }
}
</style>
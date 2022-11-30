<template>
  <div>
    <div
      class="modal fade"
      id="unfollowUserFromFollowingList"
      tabindex="-1"
      aria-labelledby="unfollowUserFromFollowingListLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <Modal
          v-if="show_modal"
          :message="message"
          :header="`Unfollow <b>${user.user.username} ?`"
          label="unfollowUser"
          @unfollowUser="unfollowUser"
        ></Modal>
      </div>
    </div>
    <div v-if="followingInt > 0">
      <div
        data-bs-toggle="modal"
        data-bs-target="#followingList"
        @click="getFollowing"
      >
        <div class="follows p-2 rounded-2 d-flex align-items-center gap-2">
          <span>{{ followingInt }}</span>
          <b>Following</b>
        </div>
      </div>
      <div
        class="modal fade bd-example-modal-xl"
        id="followingList"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="followingListLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content" style="height: 50%">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="followingListLabel">
                Following
              </h1>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <Search @searchUsername="searchUsername"></Search>
              <template v-if="isLoading">
                <cube-spin></cube-spin>
              </template>
              <template
                v-else-if="filtered_following_list.length < 1 && !isLoading"
              >
                <p class="m-5 text-muted text-center">No username found.</p>
              </template>
              <template v-else>
                <div
                  v-for="(user, index) in filtered_following_list"
                  :key="index"
                >
                  <User
                    :user="user"
                    :is_user="is_user"
                    @unfollowUser="unfollowFollower"
                  ></User>
                </div>
                <infinite-loading
                  @distance="1"
                  @infinite="handleLoadMore"
                ></infinite-loading>
              </template>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
              >
                Dismiss
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else>
      <div class="p-2 rounded-2 d-flex align-items-center gap-2 text-muted">
        <span>0</span>
        <b>Following</b>
      </div>
    </div>
  </div>
</template>

<script>
import User from "./User.vue";
import Modal from "./Modal.vue";
import Search from "./Search.vue";
import CubeSpin from "../Animation/SquareGrid.vue";
export default {
  props: ["user_id", "is_user"],
  components: { User, Modal, Search, CubeSpin },
  data() {
    return {
      following_list: [],
      user: null,
      show_modal: false,
      message: null,
      username: null,
      filtered_following_list: [],
      isLoading: false,
      page: 1,
    };
  },
  computed: {
    followingInt() {
      return this.$store.getters.getFollowing;
    },
  },
  methods: {
    async handleLoadMore($state) {
      try {
        this.page = this.page + 1;
        const response = await axios.get(
          `/profile/${this.user_id}/following?page=` + this.page
        );
        if (response.data?.length > 0) {
          this.following_list = [...this.following_list, ...response.data];
          this.filtered_following_list = [...this.following_list,...response.data];
        } else {
          const loader = document.querySelector(".infinite-loading-container");
          loader.style.display = "none";
        }
        $state.loaded();
      } catch (error) {
        console.log(error);
      }
    },
    async getFollowing() {
      try {
        this.isLoading = true;
        this.filtered_following_list = [];
        this.page = 1;
        const response = await axios.get(`/profile/${this.user_id}/following`);
        this.isLoading = false;
        this.following_list = response.data;
        this.filtered_following_list = response.data;
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
        this.$toast.open({
          message: error.message,
          type: "error",
          position: "bottom",
        });
      }
    },
    unfollowFollower(following, message) {
      this.show_modal = true;
      this.user = following;
      this.message = message;
    },
    async unfollowUser() {
      try {
        await axios.post(`/follow/${this.user.user_id}`, {
          data: {
            is_following: this.user.is_following,
            type: "follow",
          },
        });
        this.$toast.open({
          message: `You have unfollowed ${this.user.user.username}!`,
          type: "error",
          position: "bottom",
          queue: true,
        });
        this.$store.dispatch("unfollowUser");
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
        this.$toast.open({
          message: error.message,
          type: "error",
          position: "bottom",
        });
      }
    },
    searchUsername(username) {
      this.username = username;
    },
  },
  watch: {
    username(value) {
      this.filtered_following_list = this.following_list.filter((following) => {
        return following.user.username.match(value);
      });
    },
  },
};
</script>

<style scoped>
.follows {
  width: max-content;
}
.follows:hover {
  background: rgb(199, 199, 225);
  transition: all 0.3s ease-in;
  cursor: pointer;
}
</style>
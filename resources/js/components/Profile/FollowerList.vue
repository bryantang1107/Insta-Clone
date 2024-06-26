<template>
  <div>
    <div
      class="modal fade"
      id="unfollowUserFromFollowerList"
      tabindex="-1"
      aria-labelledby="unfollowUserFromFollowerListLabel"
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
    <div
      class="modal fade"
      id="removeUser"
      tabindex="-1"
      aria-labelledby="removeUserLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <Modal
          v-if="show_modal"
          :message="message"
          :header="`Remove <b>${user.user.username}</b> From Follower List ?`"
          label="removeUser"
          @removeUser="removeUser"
        ></Modal>
      </div>
    </div>

    <div v-if="followersInt > 0">
      <div
        data-bs-toggle="modal"
        data-bs-target="#followerList"
        @click="getFollowers"
      >
        <div class="follows p-2 rounded-2 d-flex align-items-center gap-2">
          <span>{{ followersInt }}</span>
          <b>Followers</b>
        </div>
      </div>
      <div
        class="modal fade bd-example-modal-xl"
        id="followerList"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="followerListLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content" style="height: 50%">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="followerListLabel">Followers</h1>
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
                v-else-if="filtered_follower_list.length < 1 && !isLoading"
              >
                <p class="m-5 text-muted text-center">No username found.</p>
              </template>
              <template v-else>
                <div
                  v-for="(user, index) in filtered_follower_list"
                  :key="index"
                >
                  <User
                    :user="user"
                    :is_user="is_user"
                    :follower="true"
                    @removeFollower="removeFollower"
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
        <b>Followers</b>
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
  components: { User, Modal, Search, CubeSpin },
  props: ["user_id", "is_user"],
  data() {
    return {
      follower_list: [],
      user: null,
      show_modal: false,
      message: null,
      username: null,
      filtered_follower_list: [],
      isLoading: false,
      page: 1,
    };
  },
  //must put reactive state in computed (Vuex)
  computed: {
    followersInt() {
      return this.$store.getters.getFollow;
    },
  },
  methods: {
    async handleLoadMore($state) {
      try {
        this.page = this.page + 1;
        const response = await axios.get(
          `/profile/${this.user_id}/followers?page=` + this.page
        );
        if (response.data?.length > 0) {
          this.follower_list = [...this.follower_list, ...response.data];
          this.filtered_follower_list = this.follower_list;
        } else {
          const loader = document.querySelector(".infinite-loading-container");
          loader.style.display = "none";
        }
        $state.loaded();
      } catch (error) {
        console.log(error);
      }
    },
    async getFollowers() {
      try {
        this.isLoading = true;
        this.filtered_follower_list = [];
        this.page = 1;
        const response = await axios.get(`/profile/${this.user_id}/followers`);
        this.isLoading = false;
        this.follower_list = response.data;
        this.filtered_follower_list = response.data;
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
        this.$toast.open({
          message: error.message,
          type: "error",
          position: "top-right",
        });
      }
    },
    removeFollower(follower, message) {
      this.show_modal = true;
      this.user = follower;
      this.message = message;
    },
    async removeUser() {
      try {
        await axios.delete(`/remove/${this.user.user_id}`, {
          data: {
            is_user: this.is_user,
          },
        });
        this.$store.dispatch("removeFollower");
        this.$toast.open({
          message: `Successfully removed ${this.user.user.username} from follower list!`,
          type: "success",
          position: "bottom",
        });
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
        this.$toast.open({
          message: `Unable to remove ${this.user.user.username} from follower list!`,
          type: "error",
          position: "bottom",
        });
      }
    },
    unfollowFollower(follower, message) {
      this.show_modal = true;
      this.user = follower;
      this.message = message;
    },
    async unfollowUser() {
      //unfollow user (private) from follower list
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
          message: `Unable to remove ${this.user.user.username} from follower list!`,
          type: error.message,
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
      this.filtered_follower_list = this.follower_list.filter((follower) => {
        return follower.user.username.match(value);
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
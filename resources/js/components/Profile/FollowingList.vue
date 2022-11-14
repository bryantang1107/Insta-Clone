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
      <div data-bs-toggle="modal" data-bs-target="#followingList">
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
        <div class="modal-dialog modal-md">
          <div class="modal-content">
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

export default {
  props: ["following", "user_id", "is_user"],
  components: { User, Modal, Search },
  data() {
    return {
      following_list: [],
      followingInt: this.following,
      user: null,
      show_modal: false,
      message: null,
      username: null,
      filtered_following_list: [],
    };
  },
  methods: {
    async getFollowing() {
      try {
        const response = await axios.get(`/profile/${this.user_id}/following`);
        this.following_list = response.data;
        this.filtered_following_list = response.data;
      } catch (error) {
        this.$toast.open({
          message: error.message,
          type: "error",
          position: "top-right",
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
        window.location.reload();
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
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
  mounted() {
    this.getFollowing();
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
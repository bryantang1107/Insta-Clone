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
          :follower_id="user.id"
          v-if="show_modal"
          :message="message"
          :header="`Unfollow <b>${user.username} ?`"
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
          :follower_id="user.id"
          v-if="show_modal"
          :message="message"
          :header="`Remove <b>${user.username}</b> From Follower List ?`"
          label="removeUser"
          @removeUser="removeUser"
        ></Modal>
      </div>
    </div>

    <div v-if="followersInt > 0">
      <div data-bs-toggle="modal" data-bs-target="#followerList">
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
        <div class="modal-dialog modal-md">
          <div class="modal-content">
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
              <div
                v-for="(follower, index) in filtered_follower_list"
                :key="index"
              >
                <Follower
                  :follower="follower"
                  :is_user="is_user"
                  @removeFollower="removeFollower"
                  @unfollowUser="unfollowFollower"
                ></Follower>
              </div>
              <div v-if="filtered_follower_list.length < 1">
                <p class="m-5 text-muted text-center">No username found.</p>
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
        <b>Followers</b>
      </div>
    </div>
  </div>
</template>

<script>
import Follower from "./Follower.vue";
import Modal from "./Modal.vue";
import Search from "./Search.vue";
export default {
  components: { Follower, Modal, Search },
  props: ["followers", "user_id", "is_user"],
  data() {
    return {
      follower_list: [],
      followersInt: this.followers,
      user: null,
      show_modal: false,
      message: null,
      username: null,
      filtered_follower_list: [],
    };
  },
  methods: {
    async getFollowers() {
      const response = await axios.get(`/profile/${this.user_id}/followers`);
      this.follower_list = response.data;
      this.filtered_follower_list = response.data;
    },
    removeFollower(follower, message) {
      this.show_modal = true;
      this.user = follower;
      this.message = message;
    },
    async removeUser(follower_id) {
      await axios.delete(`/profile/remove/${follower_id}`);
      this.follower_list = this.follower_list.filter((follower) => {
        return follower.id != follower_id;
      });
      this.followersInt -= 1;
    },
    unfollowFollower(follower, message) {
      this.show_modal = true;
      this.user = follower;
      this.message = message;
    },
    unfollowUser(follower_id) {
      //remove user from follower list
      console.log(follower_id, "emit from modal");
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
  mounted() {
    this.getFollowers();
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
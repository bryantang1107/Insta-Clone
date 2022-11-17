<template>
  <div class="activity-log rounded-4 p-2 d-flex align-items-center gap-2">
    <UserProfile
      :image="activity.user.profile.image"
      :user="activity.user"
    ></UserProfile>
    <template v-if="!is_accept">
      <p class="m-0">{{ activity.message }}</p>
      <div
        class="d-flex align-items-center ms-auto"
        v-if="(activity.type = 'request')"
      >
        <button
          class="btn btn-primary me-2"
          @click="handleFollowRequest('accept', activity)"
        >
          Accept
        </button>
        <button
          class="btn btn-danger"
          @click="handleFollowRequest('decline', activity)"
        >
          Decline
        </button>
      </div></template
    >

    <div v-if="is_accept">is now following you</div>
  </div>
</template>

<script>
import UserProfile from "../UserProfile.vue";

export default {
  props: ["activity"],
  components: { UserProfile },
  data() {
    return {
      is_accept: false,
    };
  },
  methods: {
    async handleFollowRequest(d, user) {
      await axios.put("/user/follow", {
        data: {
          decision: d,
          user_id: user.user_id,
        },
      });
      if (d !== "decline") {
        this.is_accept = true;
        this.$toast.open({
          message: `${user.user.username} is now following you!`,
          type: "success",
          position: "bottom",
        });
        this.$emit('addFollower');
      } else {
        this.$emit("decline", user_id);
      }
      //change state to {name} is now following you no need remove activity
    },
  },
};
</script>

<style scoped>
.activity-log {
  background: #d0d3d59d;
  margin-bottom: 1em;
}
</style>
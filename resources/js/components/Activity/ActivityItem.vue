<template>
  <div class="activity-log rounded-4 p-2 d-flex align-items-center gap-2">
    <UserProfile :image="activity.image" :user="activity"></UserProfile>
    <template v-if="!is_accept">
      <p class="m-0">{{ activity.message }}</p>
      <div class="d-flex align-items-center" v-if="(activity.type = 'request')">
        <button
          class="btn btn-primary me-2"
          @click="handleFollowRequest('accept', activity.id)"
        >
          Accept
        </button>
        <button
          class="btn btn-danger"
          @click="handleFollowRequest('decline', activity.id)"
        >
          Decline
        </button>
      </div></template
    >

    <div v-if="is_accept">{{ activity.username }} is now following you</div>
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
    async handleFollowRequest(d, user_id) {
      await axios.put("/user/follow", {
        data: {
          decision: d,
          user_id,
        },
      });
      if (d !== "decline") {
        this.is_accept = true;
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
<template>
  <div>
    <p class="fw-bold">Follow Request</p>
    <template v-for="(activity, index) in activity_follow_list">
      <ActivityItem
        :key="index"
        :activity="activity"
        @decline="removeActivity"
      ></ActivityItem>
    </template>
    <p
      v-if="activity_follow_list.length < 1"
      class="text-muted m-0 text-center"
    >
      No Notifications
    </p>
    <hr />
    <p class="fw-bold">New</p>
    <template v-for="(activity, index) in activity_list">
      <a
        :href="`/p/${activity.post_id}`"
        v-if="activity.post_id"
        :key="index + 'A'"
        class="
          activity-log
          rounded-4
          p-2
          d-flex
          align-items-center
          gap-2
          text-decoration-none
        "
        style="background-color: #cef2d8"
      >
        <UserProfile
          :image="activity.user.profile.image"
          :user="activity"
        ></UserProfile>
        <p class="m-0 text-dark">{{ activity.message }}</p>
      </a>
      <div
        class="activity-log rounded-4 p-2 d-flex align-items-center gap-2"
        :key="index + 'A'"
        v-else
      >
        <UserProfile
          :image="activity.user.profile.image"
          :user="activity"
        ></UserProfile>
        <p class="m-0">{{ activity.message }}</p>
      </div></template
    >
    <p v-if="activity_list.length < 1" class="text-muted m-0 text-center">
      No Notifications
    </p>
  </div>
</template>

<script>
import axios from "axios";
import UserProfile from "../UserProfile.vue";
import ActivityItem from "../Activity/ActivityItem.vue";
export default {
  components: { ActivityItem, UserProfile },
  data() {
    return {
      activity_follow_list: [],
      activity_list: [],
    };
  },
  methods: {
    async getActivityFollow() {
      const response = await axios.get("/user/activity/follow");
      this.activity_follow_list = response.data;
    },
    async getActivities() {
      const response = await axios.get("/user/activity/all");
      this.activity_list = response.data;
      // this.activity_list = response.data;
    },
    removeActivity(user_id) {
      this.activity_follow_list = this.activity_follow_list.filter((item) => {
        return item.id != user_id;
      });
    },
  },
  mounted() {
    this.getActivityFollow();
    this.getActivities();
  },
};
</script>

<style scoped>
.activity-log {
  background: #d0d3d59d;
  margin-bottom: 1em;
}
</style>
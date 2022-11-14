<template>
  <div
    class="d-flex align-items-center justify-content-between p-2 rounded-2"
    :style="{ backgroundColor: user.is_user ? '#e8f2fa' : '' }"
  >
    <UserProfile :user="user.user" :image="user.image"> </UserProfile>
    <!-- If profile = auth user and profile not private -->
    <template v-if="!is_user">
      <template v-if="!user.is_user">
        <template v-if="!user.is_private">
          <!-- If following Profile -->
          <div
            v-if="follow"
            class="following-btn p-2 rounded-2 d-flex align-items-center gap-2"
            data-bs-toggle="dropdown"
          >
            <p class="m-0">Following</p>
            <font-awesome-icon icon="fa-solid fa-check" />
            <ul class="dropdown-menu">
              <li @click="handleFollow(user.user_id)" class="dropdown-item">
                Unfollow
              </li>
            </ul>
          </div>
          <!-- If not following profile -->
          <div
            v-else
            class="following-btn p-2 rounded-2 d-flex align-items-center gap-2"
            @click="handleFollow(user.user_id)"
          >
            Follow
            <font-awesome-icon icon="fa-regular fa-user" />
          </div>
        </template>
        <!-- If not auth user and profile is private -->
        <template v-else>
          <!-- If following private account -->
          <div
            v-if="follow && !requested"
            class="following-btn p-2 rounded-2 d-flex align-items-center gap-2"
            data-bs-toggle="dropdown"
          >
            <p class="m-0">Following</p>
            <font-awesome-icon icon="fa-solid fa-check" />
            <ul class="dropdown-menu">
              <li
                class="dropdown-item"
                data-bs-toggle="modal"
                data-bs-target="#unfollowUserFromFollowerList"
                @click="
                  $emit(
                    'unfollowUser',
                    user,
                    `If you change your mind, you'll have to request to follow
                      <b>${user.user.username}</b> again.`
                  )
                "
              >
                Unfollow
              </li>
            </ul>
          </div>
          <!-- If profile is private and requested to follow -->
          <div
            v-else-if="requested"
            class="following-btn p-2 rounded-2 d-flex align-items-center gap-2"
            @click="handleFollowPrivate(user.user_id)"
          >
            Requested
            <font-awesome-icon icon="fa-regular fa-user" />
          </div>
          <!-- If profile is private and not following -->
          <div
            v-else
            class="following-btn p-2 rounded-2 d-flex align-items-center gap-2"
            @click="handleFollowPrivate(user.user_id)"
          >
            Follow
            <font-awesome-icon icon="fa-regular fa-user" />
          </div> </template
      ></template>
    </template>
    <!-- If profile is auth user  -->
    <template v-else>
      <div class="d-flex align-items-center gap-2">
        <template v-if="!user.is_private">
          <!-- If following Profile -->
          <div
            v-if="follow"
            class="following-btn p-2 rounded-2 d-flex align-items-center gap-2"
            data-bs-toggle="dropdown"
          >
            <p class="m-0">Following</p>
            <font-awesome-icon icon="fa-solid fa-check" />
            <ul class="dropdown-menu">
              <li @click="handleFollow(user.user_id)" class="dropdown-item">
                Unfollow
              </li>
            </ul>
          </div>
          <!-- If not following profile -->
          <div
            v-else
            class="following-btn p-2 rounded-2 d-flex align-items-center gap-2"
            @click="handleFollow(user.user_id)"
          >
            Follow
            <font-awesome-icon icon="fa-regular fa-user" />
          </div>
        </template>
        <!-- If auth user and profile is private -->
        <template v-else>
          <!-- If following private account -->
          <div
            v-if="follow && !requested"
            class="following-btn p-2 rounded-2 d-flex align-items-center gap-2"
            data-bs-toggle="dropdown"
          >
            <p class="m-0">Following</p>
            <font-awesome-icon icon="fa-solid fa-check" />
            <ul class="dropdown-menu">
              <li
                data-bs-toggle="modal"
                data-bs-target="#unfollowUserFromFollowerList"
                @click="
                  $emit(
                    'unfollowUser',
                    user,
                    `If you change your mind, you'll have to request to follow
                      <b>${user.user.username}</b> again.`
                  )
                "
                class="dropdown-item"
              >
                Unfollow
              </li>
            </ul>
          </div>
          <!-- If profile is private and requested to follow -->
          <div
            v-else-if="requested"
            class="following-btn p-2 rounded-2 d-flex align-items-center gap-2"
            @click="handleFollowPrivate(user.user_id)"
          >
            Requested
            <font-awesome-icon icon="fa-regular fa-user" />
          </div>
          <!-- If profile is private and not following -->
          <div
            v-else
            class="following-btn p-2 rounded-2 d-flex align-items-center gap-2"
            @click="handleFollowPrivate(user.user_id)"
          >
            Follow
            <font-awesome-icon icon="fa-regular fa-user" />
          </div>
        </template>
        <button
          class="btn btn-light"
          data-bs-toggle="modal"
          data-bs-target="#removeUser"
          @click="
            $emit(
              'removeFollower',
              user,
              `InstaClone won't tell  <b>${user.user.username}</b> they have been removed from your followers.`
            )
          "
        >
          Remove
        </button>
      </div>
    </template>
  </div>
</template>

<script>
import UserProfile from "../UserProfile.vue";
import Modal from "./Modal.vue";
export default {
  props: ["user", "is_user", "follower"],
  components: { UserProfile, Modal },
  data() {
    return {
      follow: this.user.is_following,
      requested: this.user.is_requested ? this.user.is_requested : false,
    };
  },
  methods: {
    async handleFollow(id) {
      //follow/unfollow public account
      try {
        await axios.post(`/follow/${id}`, {
          data: {
            is_following: this.follow,
            type: "follow",
          },
        });
        this.follow = !this.follow;
        this.$toast.open({
          message: `You are now following ${this.user.user.username}!`,
          type: "success",
          position: "top-right",
           queue: true,
        });
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
        this.$toast.open({
          message: `Unable to follow ${this.user.user.username}, please try again later!`,
          type: "error",
          position: "top-right",
        });
      }
    },
    async handleFollowPrivate(id) {
      //request/unrequest follow private account
      try {
        await axios.post(`/follow/${id}`, {
          data: {
            is_following: this.follow,
            type: "request",
          },
        });
        this.requested = !this.requested;
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
      }
    },
  },
};
</script>

<style scoped>
.following-btn {
  background: #c3ccd5;
  cursor: pointer;
  color: white;
}
</style>
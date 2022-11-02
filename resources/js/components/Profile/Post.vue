<template>
  <div class="w-50 mx-auto border rounded mt-5">
    <!-- Start Header -->
    <div class="row p-2">
      <div class="d-flex align-items-center gap-3">
        <UserProfile :user="user" :image="image"></UserProfile>
        <button
          class="btn btn-primary"
          v-if="current != post.user_id && !follow"
          @click="handleFollow(post.user_id)"
        >
          Follow
        </button>
        <div
          class="dropdown"
          style="margin-left: auto; margin-right: 1em"
          v-if="current == post.user_id"
        >
          <font-awesome-icon
            class="dropdown-toggle"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            icon="fa-solid fa-ellipsis"
            style="
              font-size: 1.2rem;
              cursor: pointer;
              color: rgba(0, 0, 0, 0.4);
            "
          />

          <ul class="dropdown-menu">
            <li @click="scrollToElement()" style="cursor: pointer">
              <span
                class="dropdown-item d-flex align-items-center gap-3"
                href="#"
              >
                <font-awesome-icon
                  icon="fa-solid fa-pencil"
                  style="
                    font-size: 1.2rem;
                    cursor: pointer;
                    color: rgba(0, 0, 0, 0.4);
                  "
                />
                Edit Post</span
              >
            </li>
            <li
              data-bs-toggle="modal"
              data-bs-target="#deleteModal"
              style="cursor: pointer"
            >
              <span
                class="dropdown-item d-flex align-items-center gap-3"
                href="#"
              >
                <font-awesome-icon
                  icon="fa-solid fa-trash"
                  style="
                    font-size: 1.2rem;
                    cursor: pointer;
                    color: rgba(0, 0, 0, 0.4);
                  "
                />
                Delete Post</span
              >
            </li>
          </ul>
          <div
            class="modal fade bd-example-modal-xl"
            id="deleteModal"
            tabindex="-1"
            aria-labelledby="deleteModalLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Delete Post</h5>
                  <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                  ></button>
                </div>
                <div class="modal-body">
                  <p>Are you sure you want to permanently remove this post?</p>
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
                    type="button"
                    class="btn btn-danger"
                    @click="deletePost()"
                  >
                    Delete Post
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Header -->

    <!-- Start Post Image -->
    <div style="position: relative">
      <img
        :src="'/storage/' + post.image"
        alt=""
        class="w-100"
        @dblclick="handleLike(post.id, true)"
      />
      <font-awesome-icon
        icon="fa-solid fa-heart"
        :class="showIcon ? 'heart-icon' : 'heart-none'"
      />
    </div>
    <!-- End Post Image -->
    <LargePost
      :post="post"
      :user="user"
      :image="image"
      :current="current"
      :isLike="isLike"
      :likeCount="likeCount"
      :postdate="postdate"
      :commentCount="commentCount"
      :follow="follow"
      @handleLike="handleLike"
      @addComment="addComment"
    />

    <div class="card-title p-2" v-if="!edit" :ref="'focus'">
      <b>{{ user.username }}</b>
      <p class="d-inline">{{ post.caption }}</p>
      <p class="text-muted mb-0 mt-2">{{ postdate }}</p>
    </div>
    <div class="card-title p-2" v-else>
      <b class="d-block">{{ likeCount }} likes</b>
      <b>{{ user.username }}</b>
      <div class="input-group">
        <input
          type="text"
          name="caption"
          :value="post.caption"
          class="form-control"
          :ref="'caption'"
        />
        <button
          class="post-btn text-primary"
          type="button"
          id="button-addon2"
          @click="editCaption()"
        >
          Done
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { mdiCardsHeartOutline } from "@mdi/js";
import UserProfile from "../UserProfile.vue";
import LargePost from "./LargePost.vue";

export default {
  components: { LargePost, UserProfile },
  props: [
    "post",
    "user",
    "image",
    "current",
    "follows",
    "like",
    "likecount",
    "postdate",
    "commentcount",
  ],
  data() {
    return {
      follow: this.follows,
      isLike: this.like,
      likeCount: this.likecount,
      commentCount: this.commentcount,
      icons: {
        mdiCardsHeartOutline,
      },
      edit: false,
      showIcon: false,
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
        this.follow = !this.follow;
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
      }
    },
    async handleLike(id, doubleClick = false) {
      try {
        if (this.isLike && doubleClick) {
          this.showIcon = true;
          setTimeout(() => {
            this.showIcon = false;
          }, 1000);
        } else {
          await axios.post(`/likes/${id}`);
          setTimeout(() => {
            this.showIcon = false;
          }, 1000);
          if (this.isLike && doubleClick) {
            this.showIcon = true;
            return;
          }
          if (this.isLike) {
            this.likeCount = parseInt(this.likeCount) - 1;
          } else {
            this.showIcon = true;
            this.likeCount = parseInt(this.likeCount) + 1;
          }
          this.isLike = !this.isLike;
        }
      } catch (error) {
        if (error.response.status == 401) return (window.location = "/login");
      }
    },
    scrollToElement() {
      this.edit = true;
      const el = this.$refs.focus;
      if (el) {
        el.scrollIntoView({ behavior: "smooth" });
        el.focus();
        el.style.backgroundColor = "rgba(0,0,0,0.1)";
        el.style.transition = "background-color 0.3s ease-in";
        setTimeout(() => {
          el.style.backgroundColor = "";
        }, 1500);
      }
    },
    async editCaption() {
      try {
        if (this.post.caption === this.$refs.caption.value) {
          return (this.edit = false);
        }
        await axios.post(`/p/${this.post.id}`, {
          data: {
            caption: this.$refs.caption.value,
          },
        });
        this.post.caption = this.$refs.caption.value;
        this.edit = false;
      } catch (error) {
        console.log(error);
      }
    },
    async deletePost() {
      try {
        await axios.delete(`/p/${this.post.id}`);
        window.location = `/profile/${this.user.id}`;
      } catch (error) {
        console.log(error);
      }
    },
    addComment(length) {
      this.commentCount = length;
    },
  },
};
</script>

<style>
.profile-img-comment {
  width: 25px;
  height: 25px;
  object-fit: cover;
}

.line {
  transform: translateX(50%);
}

.post-btn {
  border: none;
  outline: none;
  background: transparent;
}

.custom-body::-webkit-scrollbar {
  width: 8px;
  height: 8px;
  background-color: #f9f9f9;
}
.custom-body::-webkit-scrollbar-thumb {
  border-radius: 10px;
  box-shadow: inset 0 0 6px rgb(0 0 0 / 30%);
  background-color: #c1c7d0;
}
.custom-body::-webkit-scrollbar-track {
  border-radius: 10px;
  background-color: #f9f9f9;
}
.custom-body > img {
  width: 60%;
}

.comment-section {
  height: 70%;
}

.heart-icon {
  position: absolute;
  font-size: 10em;
  top: 50%;
  left: 50%;
  color: white;
  transform: translate(-50%, -50%);
  transform-origin: bottom;
  transition: all 0.3s ease-in;
}
.heart-none {
  visibility: hidden;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
@media (max-width: 1000px) {
  .modal-dialog.custom-modal {
    height: 70vh;
    min-width: 70vw;
  }
  .modal-dialog.custom-modal > .modal-content {
    height: 100%;
    width: 100%;
  }
  .custom-body {
    display: flex;
    flex-direction: column;
  }
  .custom-body > img {
    width: 100%;
  }
}

@media (max-width: 876px) {
  .profile-img {
    width: 25px;
    height: 25px;
  }
  .profile-img-comment {
    width: 15px;
    height: 15px;
  }
}
</style>
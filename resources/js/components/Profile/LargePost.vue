<template>
  <section>
    <!-- Like/Comment Section -->
    <div class="d-flex align-items-center gap-3 p-2 border-bottom">
      <font-awesome-icon
        :color="isLike ? '#ff0000' : '#000'"
        :icon="isLike ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"
        @click="$emit('handleLike', post.id)"
        style="font-size: 1.2rem; cursor: pointer"
      />
      <b>{{ likeCount }} likes</b>
      <font-awesome-icon
        icon="fa-regular fa-comment"
        style="font-size: 1.2rem; cursor: pointer"
        data-bs-toggle="modal"
        :data-bs-target="'#exampleModal' + post.id"
      />
      <b>{{ commentCount }} comments</b>
    </div>
    <!-- End Like/Comment Section -->
    <div
      class="modal fade bd-example-modal-xl"
      :id="'exampleModal' + post.id"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl custom-modal">
        <div class="modal-content p-2">
          <div
            class="modal-body d-flex gap-3 custom-body"
            style="overflow-y: scroll; overflow-x: hidden"
          >
            <img :src="'/storage/' + post.image" alt="" />
            <div class="row p-2 align-content-between">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                  <UserProfile :user="user" :image="image"></UserProfile>
                  <button
                    class="btn btn-primary"
                    v-if="current != post.user_id && !follow"
                    @click="handleFollow(post.user_id)"
                  >
                    Follow
                  </button>
                </div>
                <div class="dropdown ms-2">
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
                          <p>
                            Are you sure you want to permanently remove this
                            post?
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
              <div class="comment-section">
                <div class="card-title p-2 text-wrap">
                  <b>{{ user.username }}</b>
                  <p class="d-inline">{{ post.caption }}</p>
                </div>
                <hr style="margin: 0; margin-bottom: 1em" />
                <p class="text-muted text-end m-0">
                  comment({{ commentCount }})
                </p>
                <div
                  v-for="(item, index) in comment_list"
                  :key="index"
                  class="card-title text-wrap mb-2"
                >
                  <div class="d-flex align-items-center gap-2">
                    <UserProfile
                      :image="item.user.profile.image"
                      :user="item.user"
                      className="profile-img-comment"
                    ></UserProfile>
                    <p class="d-inline mb-0">{{ item.text }}</p>
                  </div>
                </div>
              </div>
              <div class="d-flex flex-column p-2">
                <div class="d-flex align-items-center justify-content-between">
                  <div class="d-flex align-items-center gap-3 mb-1">
                    <font-awesome-icon
                      :color="isLike ? '#ff0000' : '#000'"
                      :icon="
                        isLike ? 'fa-solid fa-heart' : 'fa-regular fa-heart'
                      "
                      @click="$emit('handleLike', post.id)"
                      style="font-size: 1.2rem; cursor: pointer"
                    />
                    <font-awesome-icon
                      icon="fa-regular fa-comment"
                      style="font-size: 1.2rem; cursor: pointer"
                    />
                    <b>{{ likeCount }} likes</b>
                  </div>
                  <p class="text-muted mb-0">{{ postdate }}</p>
                </div>
                <form @submit.prevent="addComment" class="input-group mt-2">
                  <input
                    type="text"
                    class="form-control"
                    name="comment"
                    placeholder="Add a comment..."
                    aria-label="comment"
                    aria-describedby="button-addon2"
                    v-model="comment"
                  />
                  <button
                    class="post-btn text-primary"
                    type="button"
                    id="button-addon2"
                    @click="addComment"
                  >
                    Post
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import UserProfile from "../UserProfile.vue";
export default {
  components: { UserProfile },
  props: [
    "post",
    "user",
    "image",
    "current",
    "isLike",
    "likeCount",
    "postdate",
    "commentCount",
    "follow",
  ],
  data() {
    return {
      comment: "",
      comment_list: [],
    };
  },
  methods: {
    async getComment() {
      try {
        const response = await axios.get(`/p/comment/${this.post.id}`);
        this.comment_list = response.data;
      } catch (error) {
        console.log(error);
      }
    },
    async addComment() {
      try {
        const response = await axios.post(`/p/comment/${this.post.id}`, {
          comment: this.comment,
        });
        this.comment_list.push({
          text: this.comment,
          user: {
            username: this.user.username,
            profile: {
              image: response.data,
            },
          },
        });
        this.comment = "";
        this.$emit("addComment", this.comment_list.length);
      } catch (error) {
        console.log(error);
      }
    },
  },
  mounted() {
    this.getComment();
  },
};
</script>

<style scoped>
</style>
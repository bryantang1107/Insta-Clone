<template>
  <div class="container" style="width: 70%">
    <div class="row">
      <div class="col-3 text-center align-self-center">
        <img
          src="https://images.unsplash.com/photo-1517849845537-4d257902454a?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=735&q=80"
          class="rounded-circle profile-img"
          alt=""
        />
      </div>
      <div class="col-9">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <h4 v-if="username != null">{{ username }}</h4>
          <a href="/p/">Make Post</a>
        </div>
        <div class="row mb-3">
          <div class="col-3">posts</div>
          <div class="col-3">followers</div>
          <div class="col-3">following</div>
        </div>
        <h5>{{ profile.title }}</h5>
        <p>
          {{ profile.description }}
        </p>
        <a
          :href="
            profile.url
              ? profile.url
              : 'https://getbootstrap.com/docs/5.2/utilities/spacing/#margin-and-padding'
          "
          >{{
            profile.url
              ? profile.url
              : "https://getbootstrap.com/docs/5.2/utilities/spacing/#margin-and-padding"
          }}</a
        >
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-4">
        <img
          src="https://images.unsplash.com/photo-1662499840616-f68c556abf3d?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80"
          class="post-img"
          alt=""
        />
      </div>
      <div class="col-4">
        <img
          src="https://images.unsplash.com/photo-1662499840616-f68c556abf3d?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80"
          class="post-img"
          alt=""
        />
      </div>
      <div class="col-4">
        <img
          src="https://images.unsplash.com/photo-1662499840616-f68c556abf3d?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80"
          class="post-img"
          alt=""
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["username", "profile"],
  //username is string,
  //profile is object
  mounted() {
    console.log("helo");
  },
};
</script>

<style scoped>
.profile-img {
  width: 150px;
  height: 150px;
  object-fit: cover;
}

.post-img {
  width: 100%;
}
</style>
<template>
  <div class="container">
    <div class="row">
      <div class="col-8">
        <img :src="'/storage/' + post.image" alt="" class="w-100" />
      </div>
      <div class="col-4">
        <div>
          <div class="d-flex align-items-center gap-3">
            <img
              src="https://images.unsplash.com/photo-1517849845537-4d257902454a?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=735&q=80"
              class="rounded-circle profile-img"
              alt=""
            />
            <h4>
              {{ user.username }}
            </h4>
          </div>
          <p class="mt-3 d-flex gap-3">
            <b>{{ user.username }}</b> {{ post.caption }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["post", "user"],
};
</script>

<style scoped>
.profile-img {
  width: 50px;
  height: 50px;
  object-fit: cover;
}
</style>
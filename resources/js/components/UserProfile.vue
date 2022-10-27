<template>
  <div class="d-flex align-items-center gap-3">
    <img
      :src="
        image
          ? `/storage/${image}`
          : 'https://upload.wikimedia.org/wikipedia/commons/a/ac/Default_pfp.jpg'
      "
      :class="[
        className
          ? `rounded-circle ${className}`
          : 'rounded-circle profile-img',
      ]"
      alt=""
    />

    <a :href="`/profile/${user.id}`" class="text-decoration-none"
      ><b>{{ user.username }}</b></a
    >
  </div>
</template>

<script>
export default {
  props: ["user", "image", "className"],
};
</script>

<style scoped>
.profile-img {
  width: 45px;
  height: 45px;
  object-fit: cover;
}
</style>
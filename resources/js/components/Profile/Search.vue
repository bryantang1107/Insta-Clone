<template>
  <section style="position: relative">
    <div class="input">
      <font-awesome-icon
        icon="fa-solid fa-search"
        :style="{ display: showIcon ? '' : 'none' }"
        class="search-icon"
        v-if="showIcon"
      />
      <font-awesome-icon
        v-else
        icon="fa-solid fa-xmark"
        class="search-icon icon-right"
        @click="handleDelete"
      />
      <input
        type="text"
        class="input-form"
        :style="{
          marginLeft: showIcon ? '' : '0',
        }"
        placeholder="Search"
        @focus="handleFocus"
        v-model="searchUsername"
      />
    </div>
  </section>
</template>

<script>
import { debounce } from "../../helpers/debounce.helper";
import UserProfile from "../UserProfile.vue";
export default {
  components: { UserProfile },
  data() {
    return {
      showIcon: true,
      searchUsername: "",
      selected: null,
    };
  },
  methods: {
    handleFocus() {
      this.showIcon = false;
    },
    handleDelete() {
      this.searchUsername = "";
      this.showIcon = true;
    },
    redirect(user_id) {
      window.location = `${window.location.origin}/profile/${user_id}`;
    },
  },
  watch: {
    searchUsername: debounce(async function (newVal) {
      this.$emit("searchUsername", newVal);
    }, 300),
  },
};
</script>
<style scoped>
.input {
  border-radius: 8px;
  align-items: center;
  display: flex;
  flex: 0 1 auto;
  flex-direction: column;
  height: 36px;
  min-width: 125px;
  position: relative;
  width: 95%;
  background: rgb(239, 239, 239);
}

.input-form {
  -webkit-appearance: none;
  border: 0;
  background: rgb(239, 239, 239);
  border-radius: 8px;
  box-sizing: border-box;
  color: rgb(38, 38, 38);
  font-size: 16px;
  height: 100%;
  outline: none;
  padding: 3px 16px;
  width: 100%;
  z-index: 2;
  margin-left: 50px;
}

.search-icon {
  position: absolute;
  left: 0;
  margin-left: 10px;
  top: 25%;
  z-index: 3;
  font-size: 1.2rem;
  cursor: pointer;
  color: rgba(0, 0, 0, 0.55);
}

.icon-right {
  position: absolute;
  left: 100%;
  margin-left: 10px;
}

@media (max-width: 767px) {
  .input {
    display: none;
  }
}
</style>
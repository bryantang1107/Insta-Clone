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
    <div class="username-list shadow mb-5" v-if="showUserList">
      <template v-if="isLoading">
        <cube-spin></cube-spin>
      </template>
      <template v-if="options.length > 0">
        <div
          v-for="(option, index) in options"
          :key="index"
          class="user p-3 d-flex align-items-center gap-3"
          @click="redirect(option.id)"
        >
          <UserProfile
            :image="option.profile.image"
            :user="option"
          ></UserProfile>
        </div>
        <infinite-loading
          @distance="1"
          @infinite="handleLoadMore"
        ></infinite-loading>
      </template>
    </div>
    <div
      class="
        username-list
        shadow
        mb-5
        d-flex
        align-items-center
        justify-content-center
      "
      style="height: 80px"
      v-if="showUserList && options.length == 0 && !isLoading"
    >
      <p class="text-muted">No User Found</p>
    </div>
  </section>
</template>

<script>
import { debounce } from "../helpers/debounce.helper";
import UserProfile from "./UserProfile.vue";
import CubeSpin from "./Animation/SquareGrid.vue";
export default {
  components: { UserProfile, CubeSpin },
  data() {
    return {
      showIcon: true,
      searchUsername: "",
      selected: null,
      showUserList: false,
      options: [],
      username: "",
      page: 1,
      isLoading: false,
    };
  },
  methods: {
    handleFocus() {
      this.showIcon = false;
    },
    handleDelete() {
      this.searchUsername = "";
      this.showIcon = true;
      this.showUserList = false;
    },
    redirect(user_id) {
      window.location = `${window.location.origin}/profile/${user_id}`;
    },
    async handleLoadMore($state) {
      try {
        this.page = this.page + 1;
        const response = await axios.post(`/user/search?page=` + this.page, {
          data: {
            username: this.username,
          },
        });
        if (response.data.data.length > 0) {
          this.options = [...this.options, ...response.data.data];
        } else {
          const loader = document.querySelector(".infinite-loading-container");
          loader.style.display = "none";
        }
        $state.loaded();
      } catch (error) {
        console.log(error);
      }
    },
  },
  watch: {
    searchUsername: debounce(async function (newVal) {
      if (!newVal) {
        this.options = [];
        this.showUserList = false;
        return;
      }
      this.options = [];
      this.username = newVal;
      this.page = 1;
      this.isLoading = true;
      const response = await axios.post(`/user/search`, {
        data: {
          username: newVal,
        },
      });
      this.isLoading = false;
      this.options = response.data.data;
      this.showUserList = true;
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
  width: 268px;
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

.username-list {
  position: absolute;
  transform-origin: top center;
  align-items: stretch;
  background: white;
  border: 0;
  border-radius: 6px;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  font: inherit;
  font-size: 100%;
  margin: 0;
  overflow-x: hidden;
  overflow-y: auto;
  padding: 0;
  vertical-align: baseline;
  height: auto;
  max-height: 300px;
  width: 100%;
  z-index: 10;
}

/* width */
.username-list::-webkit-scrollbar {
  width: 8px;
  height: 8px;
  background-color: #f9f9f9;
}

/* Handle */
.username-list::-webkit-scrollbar-thumb {
  border-radius: 10px;
  box-shadow: inset 0 0 6px rgb(0 0 0 / 30%);
  background-color: #c1c7d0;
}
.user {
  cursor: pointer;
}
.user:hover {
  background: rgb(239, 239, 239);
}

@media (max-width: 767px) {
  .input {
    display: none;
  }
}
</style>
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
import "material-design-icons-iconfont/dist/material-design-icons.css";

window.Vue = require("vue").default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
import Welcome from "./components/Welcome.vue";
import Home from "./components/Profile/Home.vue";
import Post from "./components/Profile/Post.vue";
import Search from "./components/Search.vue";
import PrivateProfile from "./components/PrivateProfile.vue";
import Activity from "./components/Activity/Activity.vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faHeart,
    faComment,
    faBell,
    faUser,
} from "@fortawesome/free-regular-svg-icons";
import {
    faHeart as faHeartSolid,
    faPencil,
    faEllipsis,
    faTrash,
    faSearch,
    faXmark,
    faLock,
    faCheck,
} from "@fortawesome/free-solid-svg-icons";
import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
library.add(
    faHeart,
    faHeartSolid,
    faComment,
    faPencil,
    faEllipsis,
    faTrash,
    faSearch,
    faXmark,
    faLock,
    faBell,
    faCheck,
    faUser
);
Vue.component("font-awesome-icon", FontAwesomeIcon);
Vue.use(VueToast);
const app = new Vue({
    el: "#app",
    components: {
        "welcome-component": Welcome,
        "home-component": Home,
        "post-component": Post,
        "search-component": Search,
        "private-profile-component": PrivateProfile,
        "activity-component": Activity,
    },
});

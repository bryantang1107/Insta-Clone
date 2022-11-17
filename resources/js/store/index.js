//entry point
//this file combines all modules (each module has state, action, reducer)
//ui -->(call) action, action -->(commits) reducer/mutation, reducer/mutation -->(mutate) state

//redux vs vuex
//vuex mutation directly modify state
//redux reducer update data immutably by making copies and modifying copies
import Vue from "vue";
import Vuex from "vuex";
import profile from "./modules/profile";
import user from "./modules/user";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        profile,
        user,
    },
});

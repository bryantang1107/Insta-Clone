export default {
    state() {
        return {
            follow: 0,
            following: 0,
        };
    },
    mutations: {
        SET_FOLLOW(state, amount) {
            state.follow = amount;
        },
        SET_FOLLOWING(state, amount) {
            state.following = amount;
        },
        REMOVE_FOLLOWER(state) {
            state.follow = parseInt(state.follow) - 1;
        },
        FOLLOW_USER(state) {
            state.following = parseInt(state.following) + 1;
        },
        UNFOLLOW_USER(state) {
            state.following = parseInt(state.following) - 1;
        },
        FOLLOW_OTHER_USER(state) {
            state.follow = parseInt(state.follow) + 1;
        },
        UNFOLLOW_OTHER_USER(state) {
            state.follow = parseInt(state.follow) - 1;
        },
    },
    actions: {
        setFollow({ commit }, amount) {
            commit("SET_FOLLOW", amount);
        },
        setFollowing({ commit }, amount) {
            commit("SET_FOLLOWING", amount);
        },
        removeFollower({ commit }) {
            commit("REMOVE_FOLLOWER");
        },
        followUser({ commit }) {
            commit("FOLLOW_USER");
        },
        unfollowUser({ commit }) {
            commit("UNFOLLOW_USER");
        },
        followOtherUser({ commit }) {
            commit("FOLLOW_OTHER_USER");
        },
        unfollowOtherUser({ commit }) {
            commit("UNFOLLOW_OTHER_USER");
        },
    },
    getters: {
        getFollow(state) {
            return state.follow;
        },
        getFollowing(state) {
            return state.following;
        },
    },
};

export default {
    state() {
        return {
            currentUser: {},
        };
    },
    mutations: {
        SET_USER(state, user) {
            state.currentUser = user;
        },
    },
    actions: {
        setUser({ commit }, user) {
            commit("SET_USER", user);
        },
    },
    getters: {
        getUser(state) {
            return state.currentUser;
        },
    },
};

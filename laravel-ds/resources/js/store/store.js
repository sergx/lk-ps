import Vue from "vue";
import Vuex from "vuex";
import httpClient from "../httpClient";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    // В компонентах - this.$store.state.auth.permissions
    auth: {
      user: {},
      permissions: []
    },
    cache: {}
  },
  mutations: {
    // Только изменяют state
    // Вызов из компонентов - this.$store.commit('setUserAuthData', {name: 'myName'})
    // Вызов из store - context.commit("setUserAuthData", context.state.cache[url]); // В функцию, где вызывается должен быть передан первым аргемент context.

    setAuthUser(state, payload) {
      state.auth.user = payload;
    },
    setAuthPermissions(state, payload) {
      state.auth.permissions = payload;
    },
    addToCashe(state, payload) {
      state.cache[payload.hash] = payload.data;
      console.log(state.cache);
    }
  },
  actions: {
    // TODO Спросить - нормально ли такое вообще делать? То есть использовать метод Action в другом Action..
    ajax_basic: function(context, { data, url }) {
      return httpClient(data, url).then(response => {
        if (typeof response.data.auth.user !== "undefined") {
          context.commit("setAuthUser", response.data.auth.user);
        }
        if (typeof response.data.auth.permissions !== "undefined") {
          context.commit("setAuthPermissions", response.data.auth.permissions);
        }
        return response;
      });
    },
    // Можем что-то делать, и вызывать mutations
    // Вызов из компонентов - this.$store.dispatch('loadAuthData', {some_payload});

    // updateUserData({commit}, payload) { commit("setAuthUserData", context.state.cache[url]);} // Без context
    test(context) {
      context
        .dispatch("ajax_basic", { data: {}, url: "/lk/api/quiz/" })
        .then(response => {
          console.log(response.data, "response");
        });
    },

    get_user_data({ commit, state }) {
      if (!state.auth.user.id) {
        return httpClient({}, "/lk/api/user/auth-data").then(response => {
          if (typeof response.data.auth.user !== "undefined") {
            commit("setAuthUser", response.data.auth.user);
          }
          if (typeof response.data.auth.permissions !== "undefined") {
            commit("setAuthPermissions", response.data.auth.permissions);
          }
          return response;
        });
      }
    }
  },
  getters: {
    getProductInfo: state => key => {
      return state[key].name + " - " + state[key].id;
    }
  }
});

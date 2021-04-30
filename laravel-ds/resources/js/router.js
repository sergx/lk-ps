import VueRouter from "vue-router";
import store from "./store/store";

import notFound404 from "./components/notFound404";
import permission from "./pages/permission";
import users from "./pages/user-index";
import user from "./pages/user-item";
import quizEdit from "./pages/quiz-edit";
import quizIndex from "./pages/quiz-index";

import quizShow from "./pages/quiz-show";
import quizAnswerIndex from "./pages/quiz-answer-index";
import quizAnswerShow from "./pages/quiz-answer-show";

//store.dispatch("get_user_data");

const router = new VueRouter({
  mode: "history",
  routes: [
    { path: "/lk/permission", component: permission, name: "permission" },

    { path: "/lk/user", component: users, name: "user.index" },
    { path: "/lk/user/create", component: user, name: "user.create" },
    { path: "/lk/user/:id(\\d+)", component: user, name: "user.edit" },

    { path: "/lk/quiz/edit", component: quizEdit, name: "quiz.create" },
    { path: "/lk/quiz/:id(\\d+)", component: quizShow, name: "quiz.show" },
    { path: "/lk/quiz/:id(\\d+)/edit", component: quizEdit, name: "quiz.edit" },
    { path: "/lk/quiz", component: quizIndex, name: "quiz.index" },
    {
      path: "/lk/quiz-answer",
      component: quizAnswerIndex,
      name: "quiz.answer.index"
    },
    {
      path: "/lk/quiz-answer/:id(\\d+)",
      component: quizAnswerShow,
      name: "quiz.answer.show"
    },

    { path: "/lk/page-not-found", component: notFound404, name: "notFound404url" },
    { path: "/lk/*", component: notFound404, name: "notFound404" }
    //{path: '/buyer', component: ModuleBuyerIndex},
  ]
});
router.beforeEach((to, from, next) => {
  switch (to.matched[0].path) {
    case "/lk/quiz":
    case "/lk/page-not-found":
    case "/lk/*":
      // Эти пути доступны любому авторизованному (Проверка авторизации происходит на уровне middleware Laravel)
      next();
      break;
    default:
      // Остальные пути доступны только для Админа
      store.dispatch("get_user_data").then(() => {
        if (store.state.auth.permissions.indexOf("admin only") === -1) {
          next({ name: "quiz.index" });
        } else {
          next();
        }
      });
      break;
  }

});
export default router;

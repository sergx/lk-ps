<template>
  <div class="container">
    <h1>
      Список <span v-if="!$can('admin only')">доступных для Вас</span> опросов
      <router-link class="btn btn-primary btn-sm" :to="{ name : 'quiz.create'}" v-if="$can('admin only')">+ Создать новый</router-link>
    </h1>

    <p v-if="$can('admin only')"><router-link :to="{ name : 'quiz.answer.index'}">Ответы</router-link></p>

    <table class="table" v-if="quizzes_loaded && quizzes.length">
      <thead>
        <tr>
          <th>Название</th>
          <th>Описание</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="quiz in quizzes" :key="quiz.id">
          <td>
            <div><strong>{{quiz.name}}</strong></div>
            <div class="mt-1 mb-2"><router-link class="btn btn-primary" :to="{ name : 'quiz.show', params: { id: quiz.id} }">Пройти опрос</router-link></div>
            <div class="mt-1" v-if="$can('admin only')"><small><router-link :to="{ name : 'quiz.answer.show', params: { id: quiz.id} }">Ответы</router-link></small></div>
            <div class="mt-1" v-if="$can('admin only')"><small><router-link :to="{ name : 'quiz.edit', params: { id: quiz.id} }">Редактировать опрос</router-link></small></div>
          </td>
          <td>
            {{quiz.description}}
            <!-- <ul>
              <li v-for="(question, index) in quiz.steps" :key="index" class="mb-2">
                <div><strong>{{ question.generator_values.title }}</strong></div>
                <div v-if="question.generator_values.description"><small>{{ question.generator_values.description }}</small></div>
              </li>
            </ul> -->
          </td>
        </tr>
      </tbody>
    </table>
    <div class="alert alert-info" v-if="quizzes_loaded && !quizzes.length">
      Нет опросов, доступных для прохождения
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {
      quizzes:[],
      quizzes_loaded:false,
    }
  },
  mounted(){
    this.getItems();
  },
  methods:{
    getItems(){
      return this.$root.ajax_basic({ }, '/lk/api/quiz' ).then(response => {
        this.quizzes = response.data.props;
        this.quizzes_loaded = true;
      });
    },
  }
}
</script>

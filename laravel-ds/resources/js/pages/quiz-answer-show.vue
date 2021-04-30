<template>
  <div class="container stat_wr">
    <b-breadcrumb :items="breadcrumbs"></b-breadcrumb>
    <h1 class="mb-4">Ответы на опрос «{{quiz.name}}»</h1>

    <div v-for="(step, index) in quiz.steps" :key="index">
      <AnswerVisualStat
        v-if="['radio','checkbox'].indexOf(step.generator_values.input_type) !== -1"
        :answers="quiz.quiz_answer"
        :step="step"
        :stepIndex="index"
      />
    </div>

    <h2 class="mt-5 mb-5">Список ответов</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Пользователь</th>
          <th v-for="(step, index) in quiz.steps" :key="index">{{step.generator_values.title}}</th>
          <th>Дата</th>
        </tr>
      </thead>
      <tbody>
        <AnswerShowRow
          v-for="answer in quiz.quiz_answer" :key="answer.id"
          :answer="answer"
          :steps="quiz.steps"
        />
      </tbody>
    </table>
  </div>
</template>

<script>
import AnswerShowRow from '../components/quiz/AnswerShowRow.vue';
import AnswerVisualStat from '../components/quiz/AnswerVisualStat.vue';

export default {
  components:{ AnswerShowRow, AnswerVisualStat },
  computed:{
    breadcrumbs(){
      return [
        {
          text: 'Список опросов',
          to: { name : 'quiz.index' }
        },
        {
          text: this.quiz.name,
          active: true
        }
      ]
    },
  },
  data(){
    return {
      quiz:{}
    }
  },
  mounted(){
    this.getItems();
  },
  methods:{
    getItems(){
      return this.$root.ajax_basic({ id: this.$route.params.id }, '/lk/api/quiz-answer/show' ).then(response => {
        this.quiz = response.data.props;
      });
    },
    consoleLogJSON(item){
      console.group(item.quiz.name+" steps");
      console.log(JSON.stringify(item.steps, null, 2));
      console.groupEnd();
    },
  }
}
</script>
<style>
.stat_wr td{
  vertical-align: middle;
}
</style>

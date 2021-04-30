<template>
  <div class="container">
    <b-breadcrumb :items="breadcrumbs"></b-breadcrumb>
    <h1>{{quiz.name}}</h1>
    <p v-if="$can('admin only')"><router-link :to="{ name : 'quiz.edit', params : { id : $route.params.id } }">Редактировать этот опрос</router-link></p>
    <div class="row">
      <div class="col-12 col-sm-6 col-md-7 col-lg-8">
        <div v-for="(step, index) in quiz.steps" :key="index">
          <div v-show="active_step_index === index">
            <FormulateForm v-model="step.values" :schema="quiz.schema_question[index]">
              <FormulateInput
                v-if="step.values.question && step.values.question.indexOf('custom_answer') !== -1"
                type="textarea"
                label="Свой вариант ответа"
                name="custom_answer"
              />
              <hr>
              <div class="switch_steps row mb-4">
                <div class="col">
                  <button
                    type="button" class="btn btn-sm btn-block btn-outline-primary"
                    @click="goToStep(active_step_index-1)" v-if="active_step_index !== 0">Предыдущий</button>
                </div>
                <div class="col">
                  <button
                    type="button" class="btn btn-sm btn-block btn-primary"
                    @click="goToStep(active_step_index+1)" v-if="quiz.steps.length-1 > active_step_index">Следующий</button>
                  <button
                    type="button" class="btn btn- btn-block btn-success"
                    @click="saveAnswer" v-if="quiz.steps.length-1 == active_step_index">Сохранить результаты</button>
                </div>
              </div>
            </FormulateForm>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
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
    }
  },
  data(){
    return{
      active_step_index:0,
      quiz:{},
    }
  },
  mounted(){
    this.loadQuiz();
  },
  methods:{
    saveAnswer(){
      return this.$root.ajax_basic({
        steps: this.quiz.steps,
        quiz_id: this.$route.params.id,
      }, '/lk/api/quiz-answer/save' ).then(response => {
        this.$router.push({name: 'quiz.index'});
      });
    },
    loadQuiz(){
      // TODO refactor to ().then(({data}) => ({pld}) => {
      return this.$root.ajax_basic({}, '/lk/api/quiz/'+this.$route.params.id+'/show' ).then(response => {
        if(response.data.props.redirect){
          return this.$router.push(response.data.props.redirect.to);
        }

        response.data.props.quiz.steps.map(step => {
          if(Array.isArray(step.values)){
            step.values = {};
          }
        });
        this.quiz = response.data.props.quiz;
        this.goToStep(0);
      });
    },
    goToStep(index){
      this.active_step_index = index;
    }
  }
}
</script>

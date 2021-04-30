<template>
  <div class="container">
    <h1>Ответы</h1>

    <table class="table">
      <thead>
        <tr>
          <th>Пользователь</th>
          <th>Опрос</th>
          <th>Ответы</th>
          <th>Дата</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in quizAnswer" :key="item.id">
          <td>
            <div><strong>{{item.user.name}}</strong></div>
            <div>{{item.user.child}}</div>
          </td>
          <td>
            <div v-if="item.quiz">
              <div>{{item.quiz.name}}</div>
              <ul>
                <li><router-link :to="{ name : 'quiz.edit', params: { id: item.quiz.id} }">Редактировать опрос</router-link></li>
                <li><router-link :to="{ name : 'quiz.answer.show', params: { id: item.quiz.id} }">Ответы на этот опрос</router-link></li>
              </ul>
            </div>
            <div v-else>Опрос удален</div>
          </td>
          <td>
            <ul>
              <li v-for="(question, index) in item.steps" :key="index" class="mb-2">
                <div><strong>{{ question.generator_values.title }}</strong></div>
                <div v-if="typeof question.values.question == 'string' && question.values.question.indexOf('custom_answer') === -1">
                  <div>{{question.values.question}}</div>
                </div>
                <div v-if="typeof question.values.question == 'object'">
                  <div>{{question.values.question.join("; ")}}</div>
                </div>
                <div v-if="question.values.question.indexOf('custom_answer') !== -1">
                  <div><small>Свой ответ:</small> {{ question.values.custom_answer }}</div>
                </div>
              </li>
            </ul>
          </td>
          <td>
            <div>{{item.created_at}}</div>
          </td>
          <td>
            <button class="btn btn-warning btn-sm px-1 py-0" @click="deleteItem(item)">X</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data(){
    return {
      quizAnswer:[]
    }
  },
  mounted(){
    this.getItems();
  },
  methods:{
    getItems(){
      return this.$root.ajax_basic({ }, '/lk/api/quiz-answer' ).then(response => {
        this.quizAnswer = response.data.props;
      });
    },
    deleteItem(item){
      return this.$root.ajax_basic({ }, '/lk/api/quiz-answer/'+item.id+'/destroy' ).then(response => {
        let index = this.quizAnswer.findIndex((elem) => item.id === elem.id);
        this.quizAnswer.splice(index, 1);
      });
    }
  }
}
</script>

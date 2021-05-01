<template>
  <div class="container">
    <b-breadcrumb :items="breadcrumbs"></b-breadcrumb>
    <h1>
      Конструктор опроса
      <button
      :disabled="!!answers.length"
      @click="save"
      type="button" class="btn btn-sm btn-primary"
      >Сохранить</button>
    </h1>
    <div class="alert alert-danger" v-if="answers.length">
      У опроса {{name}} уже есть Ответы ({{answers.length}} шт). Поэтому опрос уже нельзя реактировать.
    </div>

    <div class="input-group input-group-sm mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">Название опроса:</span>
      </div>
      <input type="text" class="form-control" v-model="name">
      <div class="input-group-append">
        <router-link class="input-group-text" style="text-decoration:underline;" :to="{ name : 'quiz.show', params : { id : $route.params.id } }">Пройти этот опрос</router-link>
      </div>
    </div>

    <div class="mb-4 mt-4">
      <h2>Шаги <button type="button" class="btn btn-primary btn-sm" @click="addStep" :disabled="!!answers.length">+ Добавить</button></h2>
      <div class="switch_steps mb-3">
        <div class="d-inline-block mb-2"
          v-for="(step, index) in steps" :key="index"
        >
          <template v-if="active_step_index === index && index > 0">
            <button class="btn btn-sm mr-1 ml-3 mt-1 mb-1 pt-0 pb-0 btn-outline-success" @click="changeStepOrder(index, index-1)">&lt;</button>
          </template>
          <button
            type="button" class="btn btn-sm"
            :class="{'btn-success': active_step_index === index, 'btn-outline-primary mr-2': active_step_index !== index}"
            @click="goToStep(index)">
              <template v-if="step.generator_values.title.length">
                {{ step.generator_values.title }}
              </template>
              <template v-else>
                Шаг {{index+1}}
              </template>
          </button>
          <template v-if="active_step_index === index && index < steps.length-1">
            <button class="btn btn-sm mr-3 ml-1 mt-1 mb-1 pt-0 pb-0 btn-outline-success" @click="changeStepOrder(index, index+1)">&gt;</button>
          </template>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-sm-6 col-md-5 col-lg-4">
        <div v-if="steps.length">
          <div v-for="(step, index) in steps" :key="index">
            <div v-show="index === active_step_index">
              <h2>Шаг {{index+1}}</h2>
              <FormulateForm class="form_generator" v-model="step.generator_values" :schema="schema_generator" >
                <hr v-if="step.generator_values.input_type">

                <FormulateInput
                  type="checkbox"
                  label="Добавить изображения"
                  name="has_images"
                />
                <FormulateInput
                  v-if="step.generator_values.input_type"
                  key="question_label"
                  name="question_label"
                  label="Подпись для вопроса"
                />

                <FormulateInput
                  v-if="['radio', 'checkbox'].indexOf(step.generator_values.input_type) !== -1"
                  type="group"
                  name="question_options"
                  :repeatable="true"
                  label="Варианты ответов"
                  add-label="+ Добавить вариант"
                  validation="required"
                >
                  <FormulateInput
                    name="val"
                  />
                </FormulateInput>
                <FormulateInput
                  v-if="['radio', 'checkbox'].indexOf(step.generator_values.input_type) !== -1"
                  type="checkbox"
                  label="Разрешить «Свой вариант»?"
                  name="custom_answer_enable"
                />
                <FormulateInput
                  v-if="step.generator_values.has_images"
                  type="group"
                  name="images"
                  :repeatable="true"
                  label="Изображения"
                  add-label="+ Еще одно"
                  validation="required"
                >
                  <FormulateInput
                    type="image"
                    name="image"
                    validation="mime:image/jpeg,image/png,image/gif"
                  />
                  <FormulateInput
                    name="image_description"
                  />
                </FormulateInput>

              </FormulateForm>
              <hr class="mt-5 mt-4">
              <div class="btn btn-danger btn-sm" @click="removeStep(index)">Удалить шаг</div>
            </div>
          </div>
        </div>
        <div v-else>
          <div class="alert alert-info">
            Нажмите "+ Добавить" Чтобы добавить шаг опроса..
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-7 col-lg-8">
        <div v-for="(step, index) in steps" :key="index">
          <div v-if="active_step_index === index">
            <h2>Предпросмотр для шага {{index + 1}}</h2>
            <hr>
            <FormulateForm v-model="step.values" :schema="schema_question[index]">
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
                    @click="goToStep(active_step_index+1)" v-if="steps.length-1 > active_step_index">Следующий</button>
                </div>
              </div>
            </FormulateForm>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-3 pt-3 border-top" v-if="steps.length && $route.params.id">

      <button class="btn btn-danger btn-sm" @click="removeQuiz">Удалить опрос</button>
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
          text: this.name,
          active: true
        }
      ]
    },
    schema_question(){
      let schema = [];
      if(this.active_step_index === null){
        return schema;
      }
      this.steps.map(step => {
        let GV = step.generator_values;
        let TA = [];

        if(GV.title){ TA.push({
            component: "h2",
            children: GV.title
        });}

        if(GV.description){ TA.push({
            component: "p",
            class: "mt-3 mb-3",
            children: GV.description
        });}

        if(GV.images){
          let img_block = {
            component: "div",
            class: "row mt-3 mb-4",
            children: []
          };
          if(GV.images){
            let added_count = 0;
            GV.images.map(item => {
              if(!item || !item.image || !item.image.results || !item.image.results[0] || !item.image.results[0].url){
                return true;
              }
              let image_item = {
                component: "div",
                //class: "col-12 col-sm-6 col-md-4 col-lg-3 mb-3",
                class: "col-12 col-lg-6 mb-3",
                children: [{
                  component: "div",
                  class: "card",
                  children: [
                    {
                      component: "a",
                      target: "_blank",
                      href: item.image.results[0].url,
                      children: [
                        {
                          component: "img",
                          class: "card-img-top",
                          src: item.image.results[0].url,
                        },
                      ]
                    }
                  ],
                }
                ],
              };
              if(item.image_description){
                image_item.children[0].children.push({
                    component: "div",
                    class: "card-body",
                    children: [
                      {
                        component: "p",
                        class: "card-text",
                        children: item.image_description,
                      },
                    ],
                  });
              }
              img_block.children.push(Object.assign({}, image_item));
              added_count++;
            });
            if(added_count){
              TA.push(Object.assign({}, img_block));
            }
          }
        }

        if(GV.input_type && ( ['radio', 'checkbox'].indexOf(GV.input_type) === -1 || GV.question_options )){

          let input = {type: GV.input_type, label: GV.question_label};
          input.name = "question";
          if(GV.question_options){
            input.options = {};
            GV.question_options.map((item, index) => {
              input.options[item.val] = item.val;
            });
            if(GV.custom_answer_enable){
              input.options['custom_answer'] = "Свой вариант";
            }
          }
          TA.push(input);
        }
        schema.push(TA);
      });
      return schema;
    }
  },
  data() {
    return {
      active_step_index: null,
      name: "Новый опрос",
      steps: [],
      answers: [],
      schema_generator: [
        {
          label: "Вопрос",
          validationName: "Вопрос",
          name: "title",
          validation: "required",
        },
        {
          label: "Пояснение",
          validationName: "Пояснение",
          name: "description",
          type: "textarea",
        },
        {
          label: "Тип поля ввода",
          validationName: "Тип поля ввода",
          name: "input_type",
          type: "radio",
          options: {
            radio: "Радио-переключатель",
            checkbox: "Чекбокс",
            text: "Текст (коротко)",
            textarea: "Текст (развернуто)",
          },
        },
      ],
    };
  },
  mounted(){
    this.loadExistingQuiz();
  },
  methods: {
    loadExistingQuiz(){
      // Функция актуальна только для существующих записей
      if(typeof this.$route.params.id === 'undefined'){ return; }

      return this.$root.ajax_basic({}, '/lk/api/quiz/'+this.$route.params.id+'/edit' ).then(response => {
        if(!response.data.props){
          this.$root.notify({ type: 'error', duration: 12000, text: 'Невозможно получить запись с id '+this.$route.params.id+". Вы перенаправлены на форму для создание новой записи.", });

          this.$router.push({ name: 'quiz.create' });
        }
        if(response.data.props && response.data.props.steps){
          // Фиксим ошибку, которая возникает при передачи пустого массива в качестве values. А именно в пустой массив превращает JSON parse
          response.data.props.steps.map(step => {
            if(Array.isArray(step.values)){
              step.values = {};
            }
          });
          this.steps = response.data.props.steps;
          this.name = response.data.props.name;
          this.answers = response.data.props.quiz_answer;
          if(response.data.props.quiz_answer.length){
            this.$root.notify({ type: 'error', duration: 15000, text: 'У опроса <strong>'+response.data.props.name+'</strong> уже есть Ответы ('+response.data.props.quiz_answer.length+' шт). Поэтому опрос уже нельзя реактировать' });
          }
          this.goToStep(0);
        }
      });
    },
    save(){
      if(this.answers.length){
        alert('У опроса <strong>'+this.name+'</strong> уже есть Ответы ('+this.answers.length+' шт). Поэтому опрос уже нельзя реактировать');
        return;
      }
      return this.$root.ajax_basic({
        id: this.$route.params.id || 0,
        name: this.name,
        steps: this.stepsExport(),
        schema_question: this.schema_question,
       }, '/lk/api/quiz/save' ).then(response => {
        if(!this.$route.params.id){
          this.$router.push({ name: 'quiz.edit', params : { id : response.data.props.item.id }});
        }
      });
    },
    changeStepOrder(index, new_index){
      let item_temp = this.steps[index];
      //console.log(item);
      Vue.set(this.steps, index, this.steps[new_index]);
      Vue.set(this.steps, new_index, item_temp);
      this.goToStep(new_index);
    },
    removeStep(index){
      if(confirm('Уверены, что хотите удалить шаг '+(index+1)+'?')){
        this.steps.splice(index, 1);
        this.goToStep(0);
      }
    },
    addStep(){
      this.steps.push({
        values:{},
        generator_values:{
          title:"Вопрос"+(this.steps.length+1)+"..",
          description:"",
          custom_answer_enable:true,
        }
      });
      this.goToStep(this.steps.length -1);
    },
    goToStep(index){
      this.active_step_index = index;
    },
    stepsExport(){
      let result = [];

      this.steps.map((step, index) => {
        if(step.generator_values.images && step.generator_values.images.length){
          let imgs = [];
          step.generator_values.images.map((item) => {
            if(item && item.image && item.image.results && item.image.results[0] && item.image.results[0].url){
              let ta = {image: [{url : item.image.results[0].url, type: 'image/jpeg' }]};
              if(typeof item.image_description != "undefined" && item.image_description.length){
                ta.image_description = item.image_description;
              }
              imgs.push(ta);
            }
          });
          result[index] = { generator_values: Object.assign({}, step.generator_values, {images : imgs}), values:{} };
        }else{
          result[index] = { generator_values: Object.assign({}, step.generator_values), values:{} };
        }
      });
      return result;
    },
    removeQuiz(){
      if(confirm('Уверены, что хотите удалить этот опрос навсегда?')){
        return this.$root.ajax_basic({}, '/lk/api/quiz/'+this.$route.params.id+'/destroy' ).then(response => {
          this.$router.push({name: 'quiz.index'});
        });
      }
    }
  },
};
</script>

<style scoped>
.pagination .page-item{
  cursor: pointer;
}
</style>

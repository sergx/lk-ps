<template>
  <div class="card mb-3">
    <div class="card-body">
      <h4 class="card-title">{{ step.generator_values.title }}</h4>
      <table class="table">
        <tr v-for="val in unicValues" :key="val">
          <td style="width:200px;" v-html="valHtml(val)"></td>
          <td>
            <div class="progress">
              <div class="progress-bar" role="progressbar" :style="'width:'+valPersent(val)+';'">{{valPersent(val, true)}}</div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  computed:{
    specAnswers(){
      return this.answers.map(a => a.steps.filter((s,i) => i === this.stepIndex)[0].values );
    },
    allValues(){
      let res = [];
      this.specAnswers.map(a => {
        let value = a.custom_answer ? a.custom_answer+" (Свой ответ)" : a.question;
        if(typeof value === 'string'){
          value = [value];
        }
        value.map(v => res.push(v));
      });
      return res;
    },
    unicValues(){
      let res = [];
      this.allValues.map(a => {
        if(res.indexOf(a) === -1){
          res.push(a);
        }
      });
      return res;
    },
  },
  props:{
    answers: Array,
    step: Object,
    stepIndex: Number,
  },
  methods:{
    valHtml(val){
      let GV = this.step.generator_values;
      let res = "";
      if(GV.images && GV.images.length && GV.images[0].image && GV.images[0].image.length){
        GV.images.map(item => {
          if(val === item.image_description){
            res = "<span>"+val+"</span> <img src='"+item.image[0].url+"' style='max-width:100px;max-height:100px;'>";
          }
        });
      }
      if(!res){
        res = "<span>"+val+"</span>";
      }
      return res;
    },
    valPersent(val, showCount = false){
      let count = this.allValues.filter(item => item === val).length;
      let res = (this.allValues.filter(item => item === val).length / this.specAnswers.length) * 100 + "%";
      if(showCount){
        res += " ("+count+")"
      }
      return res;
    }
  },
  // mounted(){
  //   console.group(this.step.generator_values.title);
  //   console.log(this.stepIndex, 'stepIndex');
  //   console.log(this.answers, 'answers');
  //   console.log(this.step, 'step');
  //   console.log(this.specAnswers, 'specAnswers');
  //   console.log(this.allValues, 'allValues');
  //   console.log(this.unicValues, 'unicValues');
  //   console.groupEnd();
  // },

}
</script>

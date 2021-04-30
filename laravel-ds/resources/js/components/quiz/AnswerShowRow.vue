<template>
  <tr>
    <td>
      <div><strong>{{answer.user.name}}</strong></div>
    </td>
    <td v-for="(step, index) in steps" :key="index">
      <div v-html="printAnswer(answer.steps[index].values, step.generator_values)"></div>
      <!-- <button class="btn btn-sm btn-dark" @click="consoleLogJSON({answer: answer.steps[index].values, step: step.generator_values}, 'Step['+index+']')">log</button> -->
    </td>
    <td>
      <small>{{answer.created_at}}</small>
    </td>
  </tr>
</template>

<script>
export default {
  props:{
    answer: Object,
    steps: Array,
  },
  methods:{
    printAnswer(data, step){
      let answer;
      let res = '';

      if (data.question === "custom_answer"){
        answer = data.custom_answer+ "<br><small>Свой ответ</small>";
      }else{
        answer = data.question;
      }

      switch(typeof answer){
        case "string":
          res = answer;
        break;
        case "object":
          let selectedImage = [];
          if(step.images && step.images.length && step.images[0].image && step.images[0].image.length){
            step.images.map(item => {
              answer.map(a => {
                if(a === item.image_description){
                  selectedImage.push("<span>"+a+"</span> <img src='"+item.image[0].url+"' style='max-width:100px;max-height:100px;'>");
                }
              });
            });
          }

          if(selectedImage.length){
            res = selectedImage.join('<div class="mt-1"></div>');
          }else{
            res = answer.join(", ");
          }
        break;
      }
      return res;
    },
    consoleLogJSON(data, label){
      console.group(label);
      console.log(JSON.stringify(data, null, 2));
      console.groupEnd();
    },
  }
}
</script>

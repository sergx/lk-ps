<template>
  <div class="container">



    <div class="row justify-content-center">
      <div class="col-md-8">
        <b-breadcrumb :items="breadcrumbs"></b-breadcrumb>
        <h1 class="mt-3 mb-4">
          <template v-if="user.id"> Пользователь {{user.name}}</template>
          <template v-else>Создать нового пользователя</template>
        </h1>

        <div class="card">
          <div class="card-body">
              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Имя родителя</label>

                <div class="col-md-6">
                  <input type="text" class="form-control" :class="{'is-invalid': !!errors.name}" name="name" v-model="user.name" autocomplete="name" autofocus>

                  <span class="invalid-feedback" role="alert" v-if="errors.name">
                    <strong>{{errors.name.join(' ')}}</strong>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Ребенок</label>

                <div class="col-md-6">
                  <input type="text" class="form-control" :class="{'is-invalid': !!errors.child}" name="child" v-model="user.child">

                  <span class="invalid-feedback" role="alert" v-if="errors.child">
                    <strong>{{errors.child.join(' ')}}</strong>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right">Телефон</label>

                <div class="col-md-6">
                  <input type="text" class="form-control" :class="{'is-invalid': !!errors.phone}" name="phone" v-model="user.phone">

                  <span class="invalid-feedback" role="alert" v-if="errors.phone">
                    <strong>{{errors.phone.join(' ')}}</strong>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail</label>

                <div class="col-md-6">
                  <input type="email" class="form-control" :class="{'is-invalid': !!errors.email}" name="email" v-model="user.email" autocomplete="email">

                  <span class="invalid-feedback" role="alert" v-if="errors.email">
                    <strong>{{errors.email.join(' ')}}</strong>
                  </span>
                </div>
              </div>

              <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>

                <div class="col-md-6">
                  <input type="password" class="form-control" :class="{'is-invalid': !!errors.password}" name="password" v-model="user.password" autocomplete="new-password">

                  <small v-if="user.id" class="form-text text-muted">Указывайте пароль только если хотите его поменять</small>

                  <span class="invalid-feedback" role="alert" v-if="errors.password">
                    <strong>{{errors.password.join(' ')}}</strong>
                  </span>
                </div>
              </div>

              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button v-if="user.id" @click="updateUser" class="btn btn-primary">Сохранить</button>
                  <button v-if="user.id" @click="deleteUser" class="btn btn-danger ml-4 btn-sm">Удалить</button>
                  <button v-else @click="createUser" class="btn btn-primary">Создать</button>
                </div>
              </div>
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
          text: 'Список пользователей',
          to: { name : 'user.index' }
        },
        {
          text: 'Пользователь '+ this.user.name,
          active: true
        }
      ]
    }
  },
  data(){
    return {
      user:{
        name:"",
        email:"",
        phone:"",
        child:"",
        password:"",
      },

      errors: {}
    }
  },
  mounted(){
    if(this.$route.params.id){
      this.getUser(this.$route.params.id);
    }
  },
  methods:{
    getUser(id){
      return this.$root.ajax_basic({}, '/lk/api/user/'+id+'/data' )
      .then(response => {
        console.log(response.data.props.user);
        this.user = response.data.props.user;
      });
    },
    createUser(){
      return this.$root.ajax_basic({
        name: this.user.name,
        child: this.user.child,
        phone: this.user.phone,
        email: this.user.email,
        password: this.user.password,
        password_confirmation: this.user.password,
      }, '/lk/api/user/create' )
      .then(response => {
        this.$router.push({name:'user.index'});
      })
      .catch(error => {
        this.errors = error.response.data.props.errors;
      });
    },
    deleteUser(){
      if(confirm("Удалить пользователя и все его ответы?")){
        return this.$root.ajax_basic(this.user, '/lk/api/user/'+this.user.id+'/destroy' )
        .then(response => {
          //this.user = response.data.props.user;
        });
      }
    },
    updateUser(){
        return this.$root.ajax_basic(this.user, '/lk/api/user/'+this.user.id+'/update' )
        .then(response => {
          this.user = response.data.props.user;
        });
      }
    },
  }
</script>

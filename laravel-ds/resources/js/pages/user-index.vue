<template>
  <div class="container">
    <h1>
      Пользователи
      <router-link class="btn btn-primary btn-sm" v-if="$can('admin only')" :to="{ name : 'user.create'}">+ Создать</router-link>
    </h1>
    <p><router-link :to="{ name : 'permission'}">Роли и права</router-link></p>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Пользователь</th>
            <th>Активные роли</th>
            <th>Возможные роли</th>
          </tr>
        </thead>
        <tbody>
          <user-row
             v-for="user in users" :key="user.id"
             :user="user"
             :roles="roles"
             @roleUpdated="user.roles = $event"
          ></user-row>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
//
// TODO Написать на WebsTool про роли, и создать отдельный даже репозиторий для быстрого копирования..
//
import UserRow from '../components/user/UserRow';
export default {
  components:{
    UserRow
  },
  data(){
    return {
      users:[],
      roles:[],
    }
  },
  mounted(){
    this.getUsersWithRoles();
  },
  methods:{
    getUsersWithRoles(){
      return this.$root.ajax_basic({ }, '/lk/api/user/get-users-with-roles' ).then(response => {
        this.users = response.data.props.users;
        this.roles = response.data.props.roles;
      });
    }
  }
}
</script>

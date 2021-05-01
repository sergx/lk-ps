<template>
  <tr>
    <td>
      <div><strong><router-link :to="{name: 'user.edit', params:{id:user.id}}">{{user.name}}</router-link></strong></div>
      <span v-if="false">(это Вы)</span>
      <div>{{user.child}}</div>
      <div><small><a :href="'mailto:'+user.email">{{user.email}}</a></small></div>
      <div v-if="user.phone"><small><a :href="'tel:'+user.phone">{{user.phone}}</a></small></div>
    </td>
    <td>
      <ul class="list-group" v-if="user.roles">
        <li v-for="role in user.roles" :key="role.id" :style="styleForBtn" :class="classForBtn">
            <span class="mr-auto text-nowrap">{{role.name}}</span>
            <button class="btn btn-primary btn-sm text-nowrap" @click="toggleRole(role.name)">Отменить</button>
        </li>
      </ul>
      <ul class="list-group" v-else>
        <li :style="styleForBtn" :class="classForBtn">
          Ролей нет..
        </li>
      </ul>
    </td>
    <td>
      <ul class="list-group">
        <template v-for="role in roles">
          <li v-if="userRolesIds.indexOf(role.id) === -1" :key="role.id" :style="styleForBtn" :class="classForBtn">
            <span class="mr-auto text-nowrap">{{ role.name }}</span>
            <button class="btn btn-primary btn-sm text-nowrap" @click="toggleRole(role.name)">Назначить</button>
          </li>
        </template>
      </ul>
    </td>
  </tr>
</template>

<script>
export default {
  props:{
    user: Object,
    roles: Array,
  },
  computed:{
    userRolesIds(){
      return this.user.roles.map(role => role.id);
    },
  },
  data(){
    return {
      styleForBtn: "background-color:transparent;",
      classForBtn: "list-group-item d-flex align-items-center",
    }
  },
  methods:{
    toggleRole(role_name){
      return this.$root.ajax_basic({user_id: this.user.id, role_name }, '/lk/api/user/toggle-user-role' ).then(response => {
        //this.user.roles = response.user.roles;
        this.$emit("roleUpdated", response.data.props.user.roles)
      });
    },
  }
}
</script>

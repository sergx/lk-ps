<template>
  <div class="container">
    <h1>Permission and Roles</h1>
    <p><router-link :to="{ name : 'user.index'}">Пользователи</router-link></p>
    <div class="row">
      <div class="col-6">
        <h2>Permission</h2>
        <ul>
          <li v-for="permission in permissions" :key="permission.id">
            {{ permission.name }}
            <button
            v-if="!permission.roles.length"
            class="btn btn-danger" style="font-size: 0.5rem;	padding: 0.125rem 0.25rem;"
            @click="removePermission(permission.id)">x</button>
          </li>
        </ul>
        <div class="mt-4 form-inline">
          <input type="text" class="form-control" placeholder="Add permission" v-model="new_permission">
          <button class="btn btn-primary ml-3" @click="addPermission">Add</button>
        </div>
      </div>
      <div class="col-6">
        <h2>Roles</h2>
        <ul>
          <role
            v-for="role in roles"
            :role="role"
            :permissions_pluck="permissions_pluck"
            :key="role.id"
            @changes_done="loadPermissionsAndRoles"
          ></role>
        </ul>
        <div class="mt-4 form-inline">
          <input type="text" class="form-control" placeholder="Add role" v-model="new_role">
          <button class="btn btn-primary ml-3" @click="addRole">Add</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Role from '../components/permission/Role.vue';

export default {
  components:{
    Role,
  },
  data(){
    return {
      permissions:[],
      roles:[],
      permissions_pluck:{},
      new_permission:"",
      new_role:"",
    }
  },
  mounted(){
    this.loadPermissionsAndRoles();
  },
  methods:{
    loadPermissionsAndRoles(){
      return this.$root.ajax_basic({}, '/lk/api/permissions-and-roles/').then(response => {
        this.permissions = response.data.props.permissions;
        this.roles = response.data.props.roles;
        this.permissions_pluck = response.data.props.permissions_pluck;
        this.new_permission = "";
        this.new_role = "";
      });
    },
    addPermission(){
      return this.$root.ajax_basic({ permission:this.new_permission }, '/lk/api/permissions-and-roles/permission/add').then(response => {
        this.loadPermissionsAndRoles();
      });
    },
    addRole(){
      return this.$root.ajax_basic({ role:this.new_role }, '/lk/api/permissions-and-roles/role/add').then(response => {
        this.loadPermissionsAndRoles();
      });
    },
    removePermission(permission_id){
      return this.$root.ajax_basic({ permission_id }, '/lk/api/permissions-and-roles/remove-permission').then(response => {
        this.loadPermissionsAndRoles();
      });
    },
  }
}
</script>

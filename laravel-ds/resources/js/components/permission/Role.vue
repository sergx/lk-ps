<template>
  <li class="mb-4">
    {{ role.name }}
    <ul v-if="role.permissions" class="mb-3">
      <li v-for="permission in role.permissions" :key="permission.id">
        {{permission.name}}
        <button class="btn btn-danger" style="font-size: 0.5rem;	padding: 0.125rem 0.25rem;" @click="revokePermissionFromRole(permission.name)">x</button>
      </li>
    </ul>
    <div class="form-inline">
      <select class="form-control" v-model="permission_to_assign_id">
        <option :value="id" v-for="(name, id) in permissions_pluck" :key="id">{{name}}</option>
      </select>
      <button class="btn btn-primary ml-3 btn-sm" @click="assignPermissionToRole">Assign</button>
    </div>
  </li>
</template>
<script>
  export default {
    props:{
      role:Object,
      permissions_pluck:Object,
    },
    // TODO Filter Permissions
    // computed:{
    //   permissions_pluck_prepared(){
    //     console.log(this.permissions_pluck);
    //     return this.permissions_pluck.filter(permission_name => {
    //       if(this.role.permissions.map(permission => permission.name).indexOf(permission_name) !== -1){
    //         return false;
    //       }
    //       return true;
    //     });
    //   }
    // },
    data(){
      return {
        permission_to_assign_id:0,
      }
    },
    methods:{
      assignPermissionToRole(){
        return this.$root.ajax_basic({ role_id: this.role.id, permission_id : this.permission_to_assign_id }, '/lk/api/permissions-and-roles/assign-permission-to-role').then(response => {
          this.$emit('changes_done');
        });
      },
      revokePermissionFromRole(permission_name){
        return this.$root.ajax_basic({ role_id: this.role.id, permission_name }, '/lk/api/permissions-and-roles/revoke-permission-from-role').then(response => {
          this.$emit('changes_done');
        });
      },
    }
  }
</script>

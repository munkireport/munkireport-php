<template>
  <div class="list-group">
    <a href="#" class="list-group-item list-group-item-info">
      <span class="list-group-item-heading">{{ name }}</span>
      <span class="glyphicon glyphicon-remove pull-right"></span>
      <p class="list-group-item-text"></p>
    </a>
    <a href="#" class="list-group-item" @click="selected = 'managers';">
      <span class="glyphicon glyphicon-triangle-right"></span>
      managers
      <span v-if="managerCount" class="badge">{{ managerCount }}</span>
    </a>
    <a href="#" class="list-group-item" @click="selected = 'users';">
      <span class="glyphicon glyphicon-triangle-right"></span>
      users
      <span v-if="userCount" class="badge">{{ userCount }}</span>
    </a>
    <a href="#" class="list-group-item" @click="selected = 'machineGroups';">
      <span class="glyphicon glyphicon-triangle-right"></span>
      machine groups
      <span v-if="machineGroupCount" class="badge">{{ machineGroupCount }}</span>
    </a>
  </div>
</template>

<script>
export default {
  name: "BusinessUnit",
  props: ['name', 'managerCount', 'userCount', 'machineGroupCount'],
  data() {
    return {
      "selected": null,
    }
  },
  methods: {
    selected(listName) {
      console.log(listName);
    }
  }
}
</script>

<style scoped>

</style>

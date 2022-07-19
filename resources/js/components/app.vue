<template>
  <div class="container w-100 m-auto text-center mt-3">
    <img
      src="http://al-hakeema.com/wp-content/uploads/2021/12/Al-Hakeema.png"
      alt=""
    />
    <h1 style="color: #db2726">Todo List</h1>

    <dialog-task :items="items"></dialog-task>

    <list-view
      :items="items"
      v-on:reloadlist="getItems()"
      class="text-center"
    />

  </div>
</template>

<script>
import listView from "./listView";
import dialogTask from "./Dialog";

export default {
  components: {
    listView,
    dialogTask,
  },
  props: ["items"],

  data: function () {
    return {
      items: [],
    };
  },
  mounted() {
    Echo.channel("Todo").listen("TodoUpdate", (event) => {
      this.items = event.todo;
    });
  },
  methods: {
    getItems() {
      axios
        .get("api/todos")
        .then((res) => {
          this.items = res.data.data;
          console.log(res.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },

  created() {
    this.getItems();
  },
};
</script>

<style scoped></style>

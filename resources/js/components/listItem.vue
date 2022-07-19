<template>
  <v-expansion-panels focusable>
    <v-expansion-panel class="mx-3 my-3" :headers="item">
      <v-expansion-panel-header
        ><h5 class="d-flex flex-row">
          {{ item.title }}
          <v-btn :color= " item.status == 'COMPLETED' ? '#40A040' : (item.status =='CANCELLED' ? '#DB2726' : (item.status =='HOLD' ? '#808080' : '#00008b'))" class="mx-2" outlined rounded small>{{item.status}}</v-btn>
        </h5>

        <div class="d-flex flex-row-reverse">
          <v-icon medium color="#DB2726" @click="removeItem">
            mdi-trash-can
          </v-icon>
          <dialog-update :item="item"></dialog-update>
        </div>
      </v-expansion-panel-header>

      <v-expansion-panel-content class="my-2"> {{ item.desc }}</v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script>
import dialogUpdate from "./DialogUpdate";

export default {
  components: {
    dialogUpdate,
  },

  props: ["item"],
  methods: {
    iconName(status) {
      status == "CANCELLED" ? "mdi-video" : "mdi-pencil";
    },

    updateItem() {
      axios
        .put(`api/item/${this.item.id}`, {
          item: this.item,
        })
        .then((res) => {
          if (res.status == 200) {
            this.$emit("itemchanged");
          }
        })
        .catch((error) => {
          console.log("error from axios put", errors);
        });
    },
    removeItem() {
      axios
        .delete(`api/todos/${this.item.id}`)
        .then((res) => {
          if (res.status == 200) {
            console.log(this.$emit);
            this.$emit("itemchanged");
          }
        })
        .catch((error) => {
          console.log("error from axios delete ", error);
        });
    },
  },
};
</script>

<style>
.completed {
  text-decoration: line-through;
  color: gray;
}
.item {
  word-break: break-all;
}
i.v-icon.v-icon {
  color: red;
}
</style>

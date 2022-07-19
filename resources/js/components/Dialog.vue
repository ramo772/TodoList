<template>
  <v-container class="form-container">
    <v-app id="inspire">
      <div class="d-flex justify-center ma-4"></div>

      <v-row justify="center">
        <v-dialog v-model="dialog" persistent max-width="600px">
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="#DB2726" dark v-bind="attrs" v-on="on">
              Add Task
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5">{{ item == null ? "Add" : "Update" }}  Task</span>
            </v-card-title>
            <v-card-text>
              <v-container>
                <v-row>
                  <v-text-field
                    :rules="[rules.required]"
                    v-model="form.title"
                    label="Task title*"
                    required
                  ></v-text-field>
                </v-row>
                <v-spacer></v-spacer>
                <v-row>
                  <v-select
                    :rules="[rules.required]"
                    v-model="form.status"
                    :items="['CANCELLED', 'INPROGRESS', 'COMPLETED', 'HOLD']"
                    label="Status*"
                    required
                  ></v-select>
                </v-row>
                <v-row>
                  <v-textarea
                    :rules="[rules.required]"
                    v-model="form.desc"
                    name="input-7-1"
                    auto-grow
                    label="Bio*"
                    rows="1"
                  ></v-textarea>
                </v-row>
              </v-container>
              <small>*indicates required field</small>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="dialog = false">
                Close
              </v-btn>
              <v-btn color="blue darken-1" text @click="submit"> Save </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-row>
    </v-app>
  </v-container>
</template>

<script>
export default {
  props: ["item"],

  data() {
    return {
      rules: {
        required: (value) => !!value || "Required.",
      },
      dialog: false,
      notifications: false,
      sound: true,
      widgets: false,
      form: {
        title: "",
        status: "",
        desc: "",
      },
    };
  },
  methods: {
    submit() {
      axios
        .post(`api/todos`, {
          title: this.form.title,
          status: this.form.status,
          desc: this.form.desc,
        })
        .then((res) => {
          if (res.data.status_code == 200) {
            this.dialog = false;
            console.log(this.dialog);
          }
        })
        .catch((error) => {
          console.log("error from axios put", error);
        });
    },
  },
};
</script>
<style>
.v-application--wrap {
  min-height: 0vh !important;
}
</style>

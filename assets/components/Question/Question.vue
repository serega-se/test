<template>
  <v-form v-on:submit.prevent="submitForm">
        <v-card v-for="(step, stepIndex) in steps" v-bind:key="step.id" v-if="stepIndex===currentStep">
          <v-card-text>
            <v-container fluid v-if="step.hasMultianswer">
              <p>{{ step.questionText }}</p>
              <v-checkbox v-for="(answer, key, index) in step.answers"
                          v-model="formData[step.id]"
                          :key="answer.id"
                          :name="step.id"
                          :value="answer.id"
                          :label="answer.answerText">
              </v-checkbox>
            </v-container>
            <v-container fluid v-else>
              <p>{{ step.questionText }}</p>
              <v-radio-group v-model="formData[step.id]">
                <v-radio
                    v-for="(answer, key, index) in step.answers"
                    :key="answer.id"
                    :value="answer.id"
                    :label="answer.answerText"
                ></v-radio>
              </v-radio-group>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-btn @click="previous" v-if="stepIndex > 0">Предыдущий вопрос</v-btn>
            <v-spacer></v-spacer>
            <v-btn @click="next" v-if="stepIndex < steps.length - 1">Следующий вопрос</v-btn>
            <v-spacer></v-spacer>
            <v-btn type="submit" v-if="stepIndex > 0">Отправить результаты</v-btn>
          </v-card-actions>
        </v-card>
  </v-form>
</template>

<script>
    import axios from "axios";

    export default {
        name: 'Question',
        data: () => ({
          currentStep: 0,
          formData: {},
          steps: [],
          errors: []
        }),
        methods: {
          next() {
            this.currentStep++;
          },
          previous() {
            this.currentStep--;
          },
          submitForm() {
            axios.post('http://localhost:8000/api/v1/statistics', this.formData)
                .then((res) => {
                  console.log(res);
                  // response.status Check status code
                })
                .catch((error) => {
                  console.log(error);
                  // error.response.status Check status code
                }).finally(() => {
                  //
                });
            this.$router.push('/statistics');
          },
        },
        created() {
          axios.get('http://localhost:8000/api/v1/question')
          .then(response => {
            this.steps = response.data
          })
          .catch(e => {
            this.errors.push(e)
          })
        },

    };
</script>

<style scoped>
</style>

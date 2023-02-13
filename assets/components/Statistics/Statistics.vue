<template>
  <v-simple-table>
    <template v-slot:default>
      <thead>
      <tr>
        <th class="text-left">
          Вопрос
        </th>
        <th class="text-left">
          Кол-во ответов
        </th>
        <th class="text-left">
          Доля
        </th>
      </tr>
      </thead>
      <tbody>
      <tr
          v-for="item in statistic"
      >
        <td>{{ item.label }}</td>
        <td>{{ item.col1 }}</td>
        <td>{{ item.col2 }}</td>
      </tr>
      </tbody>
    </template>
  </v-simple-table>
</template>

<script>
    import axios from "axios";


    export default {
        name: 'Statistics',
        data: () => ({
          statistic: [],
          errors: []
        }),
        created() {
          axios.get('http://localhost:8000/api/v1/statistics')
            .then(response => {
              for (const element of response.data) {
                console.log(element);
                let row = {};
                row.label = element.question ?? "Noname";
                row.col1 = "";
                row.col2 = "";
                this.statistic.push(row);
                for (const answer of element.answers){
                  console.log(answer)
                  let row = {};
                  row.label = answer.answer_text ?? "";
                  row.col1 = answer.count_answers ?? 0;
                  row.col2 = answer.fraction ?? 0;
                  this.statistic.push(row);
                }
              }
            })
            .catch(e => {
              this.errors.push(e)
            })
        },
    };
</script>

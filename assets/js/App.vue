<template>
  <div class="rewiew-form">
    <div class="item">
      <h1>Review Form</h1>
    </div>
    <form @change="check" @submit.prevent="send">
      <div class="item">
        <h1>1.</h1>
        <div class="scope">
          <h3 class="title">
            Score
            <span class="gray">(required)</span>
          </h3>
          <label>
            <input type="radio" value="1" v-model="score" />
            <span>
              1
              <div class="icon">&#9790;</div>
            </span>
            <div class="bage red">Terrible</div>
          </label>
          <label>
            <input type="radio" value="2" v-model="score" />
            <span>
              2
              <div class="icon">(</div>
            </span>
          </label>
          <label>
            <input type="radio" value="3" v-model="score" />
            <span>
              3
              <div class="icon">|</div>
            </span>
          </label>
          <label>
            <input type="radio" value="4" v-model="score" />
            <span>
              4
              <div class="icon">)</div>
            </span>
          </label>
          <label>
            <input type="radio" value="5" v-model="score" />
            <span>
              5
              <div class="icon">&#9789;</div>
            </span>
            <div class="bage green">Exelellent</div>
          </label>
        </div>
      </div>

      <div class="item">
        <h1>2.</h1>
        <h3 class="title">Comment</h3>
        <div class="comment">
          <textarea
            placeholder="Tell us more!"
            rows="10"
            v-model="comment"
            @keydown.enter.exact.prevent
            @keyup.enter.exact="send"
            @keydown.ctrl.enter.exact="newline"
          ></textarea>
          <div class="helper-text">
            ENTER to go to the next question | CTRL + ENTER to make a line break
          </div>
        </div>
      </div>

      <div class="item">
        <button :disabled="hold" type="submit">
          SUBMIT
        </button>
      </div>
    </form>
    <br />
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: { hotelId: String },
  data: () => ({
    score: 0,
    comment: "",
    hold: true,
  }),
  methods: {
    newline() {
      this.comment = `${this.comment}\n`;
    },
    check() {
      if (this.score > 0) {
        this.hold = false;
      }
    },
    async send() {
      this.hold = true;
      if (this.score === 0) {
        alert("Please choise an SCORE");
      } else {
        axios
          .post("/api/create-review", {
            ...this.$data,
            hotelId: this.hotelId,
          })
          .then((response) => {
            alert(response.data.status);
            this.hold = false;
            this.score = 0;
            this.comment = "";
          })
          .catch((error) => {
            this.hold = false;
            console.log("error", error);
          });
      }
    },
  },
};
</script>

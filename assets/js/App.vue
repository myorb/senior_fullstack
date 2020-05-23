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
            <span>(required)</span>
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
  data: () => ({
    hotelId: 2,
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
          .post("/api/create-review", this.$data)
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

<style scoped>
h1 {
  color: #54c9c1;
}

label {
  float: left;
  width: 5em;
}

button:disabled {
  background: #dddddd;
}

input[type="radio"] {
  width: 70px;
  height: 70px;
  border-radius: 50px;
  background-color: #eeeeee;
  -webkit-appearance: none;
  -moz-appearance: none;
  box-shadow: inset 0px 2px 4px 1px grey;
}

input[type="radio"]:focus {
  outline: none;
}

input[type="radio"]:checked {
  background-color: #54c9c1;
  box-shadow: none;
}

input[type="radio"]:checked ~ span:first-of-type {
  color: white;
}

label span:first-of-type {
  position: relative;
  top: -55px;
  left: 32px;
  font-size: 19px;
  color: gray;
  font-family: monospace;
}

label span {
  position: relative;
  top: -12px;
}

label div:first-of-type {
  position: absolute;
  display: block;
  margin: auto;
}

textarea {
  width: 100%;
  border-radius: 8px;
  font-size: 18px;
  padding: 5px;
}

button {
  padding: 15px 50px;
  background-color: #5b75bc;
  border-radius: 10px;
  color: white;
  font-size: large;
}

.comment {
  width: 65%;
}

.helper-text {
  float: right;
}

.icon {
  transform: rotate(90deg);
}

.rewiew-form {
  font-family: sans-serif;
}

.item {
  display: block;
  width: 100%;
  padding: 10px;
  float: left;
}

.bage {
  padding: 3px;
  border-radius: 5px;
  background-color: gray;
}

.red {
  color: #d94d5b;
  background-color: #e8afb2;
}

.green {
  color: #65c6ba;
  background-color: #b5e7e3;
}
</style>

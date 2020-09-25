<template>
    <div class="container">
        <div class="row h-100 m-auto">
            <div class="m-auto d-table" style="width:480px;">
                <div class="card card-block d-table-cell align-middle cbg-color">
                    <div class="card-body poll h-100">
                        <form @submit.prevent="createPoll">
                            <h3 class="w-75 border-0 question" @input="updateQuestion" contenteditable>
                            </h3>
                            <hr>
                            <span class="form-field" v-for="(answer, index) in answers" :key="index">
                                <h5 @keydown="addItem(index)" class="cbg-color w-75 border-0 answer" @input="updateAnswer($event, index)" contenteditable></h5>
                            </span>
                            <div class="">

                            </div>
                            <div class="form-group answer">
                                <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="multivote" v-model="checked">
                                <label class="custom-control-label" for="multivote">Allow multiple Votes</label>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-danger m-4">Create Poll</button>
                            </div>
                            <p class="text-danger">{{ alert }}</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data:
        function() {
            return {
                question: '',
                answers: ['0'],
                checked: false,
                alert: '',
            }
    },
    methods: {
        createPoll: function () {
            console.log(this.question, this.answers, this.checked);
            if(this.question != '' && this.answers[0] != '' && this.answers[1] != '')
            {
                axios.post('/createPoll', {
                    answers: this.answers,
                    question: this.question,
                    multivote: this.checked
                }).then((response)=>{
                    window.location.href = '/poll?p='+response.data;
                });
            }else
            {
                this.alert = 'Please make sure to provide a question and at least 2 answers';
            }
        },
        updateQuestion: function (e){
            this.question = e.target.innerText;
        },
        updateAnswer: function (e, index){
            this.answers[index] = e.target.innerText;
        },
        addItem: function (index) {
            if (index === (this.answers.length - 1)) {
                this.answers.push("")
            }
        }
    }
  }
</script>

<style scoped>
        .cbg-color{
            background-color: rgb(245, 222, 93);
        }
        .poll::before {
            content: '';
            position: absolute;
            top: 0;
            left: 65px;
            bottom: 0;
            width: 8px;
            border-left: 2px solid #ca302c;
        }

        hr{
            position: absolute;
            padding: 0;
            margin: 0;
            left: 0;
            width:100%;
            border: 1px solid blue;
        }
        textarea{
            overflow-y: hidden;
            font-size:32;
        }

        .answer{
            margin-left: 15%;
            line-height: 38px;
        }

        .answer:empty::before {
            content: 'Enter an answer';
            display: block;
            color: #766327;
        }

        .answer::after{
            display: block;
            content: "";
            position: absolute;
            padding: 0;
            margin: 0;
            left: 0;
            width:100%;
            border: 1px solid blueviolet;
        }

        .question{
            margin-left: 15%;
            margin-top: 10%;
        }

        .question:empty::before {
            content: 'Type your question here';
            display: block;
            color: #766327;
        }

        p{
            margin-left:15%;
        }
</style>
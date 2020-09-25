<template>
    <div class="container">
        <div class="row h-100 m-auto">
            <div class="m-auto d-table" style="width:480px;">
                <div class="card card-block d-table-cell align-middle cbg-color">
                    <div class="card-body poll h-100 col">
                        <form @submit.prevent="castVote">
                            <h1 class="question">{{ finalPoll.question }}</h1>
                            <hr>
                            <div v-for="answer in finalAnswers">
                                <div class="answer">
                                    <input :type="type" :value="answer.answer" :style="displayCheckbox" v-model="checkedAnswer">
                                    <label for=""><h4>{{ answer.answer }}<span :style="displayChart"> : {{ answer.votes }}</span></h4></label>
                                </div>
                            </div>

                            <div class="text-center">
                                <button class="btn btn-success m-4" :style="displayCheckbox">Submit Vote</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div :style="displayChart">
                <div class="m-auto d-table" style="width:480px;">
                    <div class="card card-block d-table-cell align-middle cbg-color">
                        <div class="card-body poll h-100 col">
                            <apexchart class="question" type="pie" width="380" :options="chartOptions" :series="series"></apexchart>
                            <span v-for="f in follow">
                            <button class="btn btn-success mt-5" ref="follow" @click="followPoll">{{ followText }} this Poll</button>
                        </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import ApexCharts from 'vue-apexcharts';

export default {
    props:{
        poll: String,
        answers: String,
        id: String
    },

    data:
        function() {
            return {
                displayChart: 'display:none',
                displayCheckbox: '',
                checkedAnswer: [],


                series: [],
                chartOptions: {
                    chart: {
                    width: 380,
                    type: 'pie',
                    },
                    labels: [],
                    responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                        width: 200
                        },
                        legend: {
                        position: 'bottom'
                        }
                    }
                    }]
                },


            }
    },

    computed:{
        finalPoll: function ()
        {
            return JSON.parse(this.poll.slice(1,-1));
        },
        finalAnswers: function ()
        {
            var answers = JSON.parse(this.answers);
            answers.forEach(element => {
                this.chartOptions.labels.push(element.answer); 
                this.series.push(element.votes); 
            });
            return answers;
        },
        type: function ()
        {
            if(JSON.parse(this.poll.slice(1,-1)).multivote == true){
                return 'checkbox';
            }else{
                return 'radio';
            }
        },
        follow: function ()
        {
            if(this.user)
            {
                var user = JSON.parse(this.user);
                
                return [''];
            }
        },
        dummy: function ()
        {
            axios.post('/follow', {
                poll_id: this.id,
            }).then((response)=>{
                if(!response.data)
                {
                    this.followText = 'Follow';
                } 
                else
                {
                    this.followText = 'Unfollow';
                }
                    
            });
        }
    },

    methods: {
        castVote: function () {
            if(this.checkedAnswer.length > 0)
            {
                axios.post('/vote', {
                        id: this.id,
                        answers: this.checkedAnswer
                    }).then((response)=>{
                        this.displayCheckbox = "display:none";
                        this.displayChart = '';
                        console.log(response);
                        //window.location.href = '/poll?p='+this.id;
                });
            }   
        },
        copyLink: function () {
            this.window = 'cha-bo-poll/poll?p=' + this.id;
            let url = this.$refs.url;
            url.value = this.window;
            url.select();
            document.execCommand("copy");
        },
        followPoll: function () {
            axios.post('/follow', {
                poll_id: this.id,
            }).then((response)=>{
                if(!response.data)
                {
                    this.followText = 'Follow';
                } 
                else
                {
                    this.followText = 'Unfollow';
                }
            });
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
            margin-left: 25%;
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
</style>
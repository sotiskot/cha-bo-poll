<template>
    <div class="container">
        <div class="row h-100 m-auto">
            <div class="m-auto d-table" style="width:480px;">
                <div class="card card-block d-table-cell align-middle cbg-color">
                    <div class="card-body poll h-100 col">
                        <h1 class="question">{{ finalPoll.question }}</h1>
                        <hr>
                        <div class="answer" v-for="answer in finalAnswers">
                            <h4>{{ answer.answer }} : {{ answer.votes }}</h4>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-success m-4" @click.stop.prevent="copyLink"><img src="/images/share.png" width="18" alt="share"> Get Share Link <input id="tes" type="text" ref="url"></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="m-auto d-table" style="width:480px;">
                <div class="card card-block d-table-cell align-middle cbg-color">
                    <div class="card-body poll h-100 col text-center">
                        <apexchart class="question" type="pie" width="380" :options="chartOptions" :series="series"></apexchart>
                        <span v-for="f in follow">
                            <button class="btn btn-success mt-5" ref="follow" @click="followPoll" :style="showFollow">Follow this Poll</button>
                            <button class="btn btn-danger mt-5" ref="follow" @click="followPoll" :style="showUnfollow">Unfollow this Poll</button>
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import ApexCharts from 'vue-apexcharts';

export default {
    created () {
            axios.post('/follow', {
                poll_id: this.id,
            }).then((response)=>{
                if(!response.data)
                {
                    this.showFollow = '';
                    this.showUnfollow = 'display:none';
                } 
                else
                {
                    this.showFollow = 'display:none';
                    this.showUnfollow = '';
                }
            });
    },

    props:{
        poll: String,
        answers: String,
        id: String,
        user: String
    },

    data:
        function() {
            return {
                showFollow: 'display:none',
                showUnfollow: 'display:none',
                checkedAnswer: [],
                window: '',
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
                        console.log(response);
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
                    this.showFollow = '';
                    this.showUnfollow = 'display:none';
                } 
                else
                {
                    this.showFollow = 'display:none';
                    this.showUnfollow = '';
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
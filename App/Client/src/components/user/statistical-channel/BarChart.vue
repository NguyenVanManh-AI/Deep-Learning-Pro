<template>
    <div>
        <div class="row mt-3">
            <div class="col-6 pl-0 pr-2">
                <canvas class="shadow p-2" id="charBartMessage"></canvas>
            </div>
            <div class="col-6 pr-0 pl-2">
                <canvas class="shadow p-2" id="chartBarFollow"></canvas>
            </div>
            <br>
        </div>
    </div>
</template>

<script >
import Chart from 'chart.js/auto';
import useEventBus from '@/composables/useEventBus';
const { onEvent } = useEventBus();

export default {
    name: 'BarChart',
    props: {
        type_chart: String,
    },
    mounted() {
        var barChartMessage = document.getElementById('charBartMessage');
        var chartMessage = new Chart(barChartMessage, {});

        var barChartFollow = document.getElementById('chartBarFollow');
        var chartFollow = new Chart(barChartFollow, {});

        onEvent('eventPropStatistical', dataChannel => {
            this.paginateVisible = false;
            this.$nextTick(() => { this.paginateVisible = true; });
            chartMessage.destroy(); 
            chartFollow.destroy(); 
            let dataMessage = {
                labels: dataChannel.bar_chart.label,
                datasets: [
                    {
                        label: 'Api Broadcast',
                        data: dataChannel.bar_chart.api_broadcast,
                        borderColor: ['rgb(29, 215, 48)'],
                        backgroundColor: ['rgb(29, 215, 48, 0.6'], 
                    },
                    {
                        label: 'Api Multicast',
                        data: dataChannel.bar_chart.api_multicast,
                        borderColor: ['rgb(255, 159, 64)'],
                        backgroundColor: ['rgba(153, 102, 255, 0.6)'],
                    }
                ]
            };
            chartMessage = new Chart(barChartMessage, {
                type: this.type_chart,
                data: dataMessage,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Detailed statistics of messages'
                        }
                    }
                },
            });

            let dataFollow = {
                labels: dataChannel.bar_chart.label,
                datasets: [
                    {
                        label: 'Followers',
                        data: dataChannel.bar_chart.followers,
                        borderColor: ['rgb(255, 205, 86)'],
                        backgroundColor: ['rgba(255, 205, 86, 0.6)'],
                    },
                    {
                        label: 'Targeted Reaches',
                        data: dataChannel.bar_chart.targeted_reaches,
                        borderColor: ['rgb(75, 192, 192)'],
                        backgroundColor: ['rgba(54, 162, 235, 0.6)'],
                    },
                    {
                        label: 'Blocks',
                        data: dataChannel.bar_chart.blocks,
                        borderColor: ['rgb(54, 162, 235)'],
                        backgroundColor: ['rgba(255, 99, 132, 0.6)'],
                    }
                ]
            };
            chartFollow = new Chart(barChartFollow, {
                type: this.type_chart,
                data: dataFollow,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Detailed statistics of the number of followers'
                        }
                    }
                },
            });
        });
    },
}

</script>
<style scoped>
.hidden {
    display: none;
}

.show {
    display: block;
}
</style>

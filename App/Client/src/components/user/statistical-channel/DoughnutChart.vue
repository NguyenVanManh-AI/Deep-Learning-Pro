<template>
    <div>
        <div class="row mt-4">
            <div class="col-3 pl-0 pr-2">
                <canvas class=" p-2" id="chartDoughnutMessage"></canvas>
            </div>
            <div class="col-3 pr-0 pl-2">
                <canvas class=" p-2" id="chartDoughnutFollow"></canvas>
            </div>
            <div class="col-3 pr-0 pl-2">
                <canvas class=" p-2" id="chartDoughnutMember"></canvas>
            </div>
            <div class="col-3 pr-0 pl-2">
                <canvas class=" p-2" id="chartDoughnutContent"></canvas>
            </div>
        </div>
    </div>
</template>

<script >
import Chart from 'chart.js/auto';
import useEventBus from '@/composables/useEventBus';
const { onEvent } = useEventBus();

export default {
    name: 'DoughnutChart',
    data() {
        return {

        }
    },
    mounted() {

        var doughnutChartMessage = document.getElementById('chartDoughnutMessage');
        var chartMessage = new Chart(doughnutChartMessage, {});

        var doughnutChartFollow = document.getElementById('chartDoughnutFollow');
        var chartFollow = new Chart(doughnutChartFollow, {});

        var doughnutChartMember = document.getElementById('chartDoughnutMember');
        var chartMember = new Chart(doughnutChartMember, {});

        var doughnutChartContent = document.getElementById('chartDoughnutContent');
        var chartContent = new Chart(doughnutChartContent, {});

        onEvent('eventPropStatistical', dataChannel => {
            chartMessage.destroy();
            chartFollow.destroy();
            chartMember.destroy();
            chartContent.destroy();

            var dataContent = {
                labels: [...dataChannel.doughnut_chart.content.label, ...dataChannel.doughnut_chart.content_delete.label],
                datasets: [
                    {
                        label: 'Active content',
                        data: dataChannel.doughnut_chart.content.data,
                        backgroundColor: [
                            'silver',
                            '#FFCD56',
                            '#36A2EB',
                        ],
                        hoverOffset: 4
                    },
                    {
                        label: 'Deleted content',
                        data: dataChannel.doughnut_chart.content_delete.data,
                        backgroundColor: [
                            '#FF4069',
                            '#008037',
                        ],
                        hoverOffset: 4
                    },
                ]
            };
            chartContent = new Chart(doughnutChartContent, {
                type: 'pie',
                data: dataContent,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                generateLabels: function (chart) {
                                    const original = Chart.overrides.pie.plugins.legend.labels.generateLabels;
                                    const labelsOriginal = original.call(this, chart);
                                    let datasetColors = chart.data.datasets.map(function (e) {
                                        return e.backgroundColor;
                                    });
                                    datasetColors = datasetColors.flat();
                                    labelsOriginal.forEach(label => {
                                        label.datasetIndex = (label.index - label.index % 2) / 2;
                                        label.hidden = !chart.isDatasetVisible(label.datasetIndex);
                                        label.fillStyle = datasetColors[label.index];
                                    });

                                    return labelsOriginal;
                                }
                            },
                            onClick: function (mouseEvent, legendItem, legend) {
                                legend.chart.getDatasetMeta(
                                    legendItem.datasetIndex
                                ).hidden = legend.chart.isDatasetVisible(legendItem.datasetIndex);
                                legend.chart.update();
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const labelIndex = (context.datasetIndex * 2) + context.dataIndex;
                                    return context.chart.data.labels[labelIndex] + ': ' + context.formattedValue;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Donut chart statistics the number of content'
                        }
                    },
                },
            });

            var dataMember = {
                labels: dataChannel.doughnut_chart.member.label,
                datasets: [{
                    label: 'Order number',
                    data: dataChannel.doughnut_chart.member.data,
                    backgroundColor: [
                        '#FF6384',
                        '#4BC0C0',
                    ],
                    hoverOffset: 4
                }]
            };
            chartMember = new Chart(doughnutChartMember, {
                type: 'pie',
                data: dataMember,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Donut chart statistics the number of member'
                        }
                    }
                }
            });

            var dataMessage = {
                labels: dataChannel.doughnut_chart.message.label,
                datasets: [{
                    label: 'Order number',
                    data: dataChannel.doughnut_chart.message.data,
                    backgroundColor: [
                        'rgb(255, 205, 86)',
                        'rgb(54, 162, 235)',
                    ],
                    hoverOffset: 4
                }]
            };
            chartMessage = new Chart(doughnutChartMessage, {
                type: 'doughnut',
                data: dataMessage,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Donut chart statistics the number of messages'
                        }
                    }
                }
            });

            var dataFollow = {
                labels: dataChannel.doughnut_chart.followers.label,
                datasets: [{
                    label: 'Order number',
                    data: dataChannel.doughnut_chart.followers.data,
                    backgroundColor: [
                        '#7ED957',
                        '#008037',
                        'rgb(255, 99, 132)',
                    ],
                    hoverOffset: 4
                }]
            };
            chartFollow = new Chart(doughnutChartFollow, {
                type: 'doughnut',
                data: dataFollow,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Donut chart statistics the number of messages'
                        }
                    }
                }
            });
        });
    },
}

</script>

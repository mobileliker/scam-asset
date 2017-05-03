<template>
    <content-component>
        <el-col :lg="24">
            <div class="page-header">
                <h3>总统计</h3>
            </div>
            <el-col :lg="4" class="box-card">
                <el-card>
                  <div slot="header" class="clearfix">
                    <h3>系统统计</h3>
                  </div>
                  <p>系统总人数：{{total.system.user_total}}</p>
                  <p>操作记录数：{{total.system.alog_total}}</p>
                  <p></p>
                  <p></p>
                </el-card>
            </el-col>
            <el-col :lg="4" class="box-card">
                <el-card>
                  <div slot="header" class="clearfix">
                    <h3>固定资产</h3>
                  </div>
                  <p>总数量：{{total.asset.number}}</p>
                  <p>总金额：{{total.asset.sum}}</p>
                  <p>本月新增新增数量：{{total.asset.month_add}}</p>
                  <p>本年新增数量：{{total.asset.year_add}}</p>
                </el-card>
            </el-col>
        </el-col>
        <el-col :lg="24">
            <div class="page-header">
                <h3>固定资产</h3>
            </div>
            <el-col :lg="6">
                <div id="asset-pie" style="width: 100%;height:400px;"></div>
            </el-col>
            <el-col :lg="18">
                <div id="asset-line" style="width: 100%;height:400px;"></div>
            </el-col>
        </el-col>
    </content-component>
</template>

<style scope>
    .box-card {
        padding : 20px;
    }
</style>

<script>
    import content from "./layouts/Content.vue"
    export default {
        components : {
            'content-component' : content
        },
        data() {
            return {
                total : {
                    system : {
                        user_total : '50',
                        alog_total : '200',
                    },
                    asset : {
                        number : '200',
                        sum : '2000',
                        month_add : '10',
                        year_add : 500
                    }
                },
                asset : {
                    pie : {
                        title: {
                            text: '固定资产分布图',
                            left: 'center',
                        },

                        tooltip : {
                            trigger: 'item',
                            formatter: "{a} <br/>{b} : {c} ({d}%)"
                        },

                        visualMap: {
                            show: false,
                            min: 80,
                            max: 600,
                            inRange: {
                                colorLightness: [0, 1]
                            }
                        },
                        series : [
                            {
                                name:'固定资产数量',
                                type:'pie',
                                radius : '55%',
                                center: ['50%', '50%'],
                                data:[
                                    {value:335, name:'动物展厅'},
                                    {value:310, name:'植物展厅'},
                                    {value:274, name:'土壤与岩石展厅'},
                                    {value:235, name:'农具展厅'},
                                    {value:400, name:'其他'}
                                ].sort(function (a, b) { return a.value - b.value}),
                                roseType: 'angle',
                                label: {
                                    normal: {
                                        textStyle: {
                                            color: 'rgba(255, 255, 255, 0.3)'
                                        }
                                    }
                                },
                                labelLine: {
                                    normal: {
                                        lineStyle: {
                                            color: 'rgba(255, 255, 255, 0.3)'
                                        },
                                        smooth: 0.2,
                                        length: 10,
                                        length2: 20
                                    }
                                },
                                itemStyle: {
                                    normal: {
                                        color: '#c23531',
                                        shadowBlur: 200,
                                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                                    }
                                },

                                animationType: 'scale',
                                animationEasing: 'elasticOut',
                                animationDelay: function (idx) {
                                    return Math.random() * 200;
                                }
                            }
                        ]

                    },
                    line : {
                        title: {
                            text: '堆叠区域图'
                        },
                        tooltip : {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'cross',
                                label: {
                                    backgroundColor: '#6a7985'
                                }
                            }
                        },
                        legend: {
                            data:['邮件营销','联盟广告','视频广告','直接访问','搜索引擎']
                        },
                        toolbox: {
                            feature: {
                                saveAsImage: {}
                            }
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        xAxis : [
                            {
                                type : 'category',
                                boundaryGap : false,
                                data : ['周一','周二','周三','周四','周五','周六','周日']
                            }
                        ],
                        yAxis : [
                            {
                                type : 'value'
                            }
                        ],
                        series : [
                            {
                                name:'邮件营销',
                                type:'line',
                                stack: '总量',
                                areaStyle: {normal: {}},
                                data:[120, 132, 101, 134, 90, 230, 210]
                            },
                            {
                                name:'联盟广告',
                                type:'line',
                                stack: '总量',
                                areaStyle: {normal: {}},
                                data:[220, 182, 191, 234, 290, 330, 310]
                            },
                            {
                                name:'视频广告',
                                type:'line',
                                stack: '总量',
                                areaStyle: {normal: {}},
                                data:[150, 232, 201, 154, 190, 330, 410]
                            },
                            {
                                name:'直接访问',
                                type:'line',
                                stack: '总量',
                                areaStyle: {normal: {}},
                                data:[320, 332, 301, 334, 390, 330, 320]
                            },
                            {
                                name:'搜索引擎',
                                type:'line',
                                stack: '总量',
                                label: {
                                    normal: {
                                        show: true,
                                        position: 'top'
                                    }
                                },
                                areaStyle: {normal: {}},
                                data:[820, 932, 901, 934, 1290, 1330, 1320]
                            }
                        ]
                    }
                },
            }
        },
        mounted() {
            this.loadAssetPie();
            this.loadAssetLine();
        },
        methods : {
            loadAssetPie() {
                var myChart = echarts.init(document.getElementById('asset-pie'));
                myChart.setOption(this.asset.pie);
            },
            loadAssetLine() {
                var myChart = echarts.init(document.getElementById('asset-line'));
                myChart.setOption(this.asset.line);
            }
        }
    }
</script>

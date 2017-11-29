/**
* @version 2.0
* @author: wuzhihui
* @date: 2017/9/30
* @description:
* （1）基本功能；
* （2）添加农具的相关统计数据；（2017/9/30）
* （3）添加岩石相关的统计数据；（2017/11/1）
*/

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
                  <p>本月新增数量：{{total.asset.month_add}}</p>
                  <p>本年新增数量：{{total.asset.year_add}}</p>
                </el-card>
            </el-col>

            <el-col :lg="4" class="box-card">
                <el-card>
                    <div slot="header" class="clearfix">
                        <h3>藏品总统计</h3>
                    </div>
                    <p>总数量：{{total.collection.number}}</p>
                    <p>图片总数量：{{total.collection.image_number}}</p>
                    <p>本月新增新增数量：{{total.collection.month_add}}</p>
                    <p>本年新增数量：{{total.collection.year_add}}</p>
                    <p>本月图片新增数量：{{total.collection.image_month_add}}</p>
                    <p>本年图片新增数量：{{total.collection.image_year_add}}</p>
                </el-card>
            </el-col>

            <el-col :lg="4" class="box-card">
                <el-card>
                    <div slot="header" class="clearfix">
                        <h3>农具</h3>
                    </div>
                    <p>总数量：{{total.farm.number}}</p>
                    <p>图片总数量：{{total.farm.image_number}}</p>
                    <p>本月新增新增数量：{{total.farm.month_add}}</p>
                    <p>本年新增数量：{{total.farm.year_add}}</p>
                    <p>本月图片新增数量：{{total.farm.image_month_add}}</p>
                    <p>本年图片新增数量：{{total.farm.image_year_add}}</p>
                </el-card>
            </el-col>
            <el-col :lg="4" class="box-card">
                <el-card>
                    <div slot="header" class="clearfix">
                        <h3>岩石</h3>
                    </div>
                    <p>总数量：{{total.rock.number}}</p>
                    <p>图片总数量：{{total.rock.image_number}}</p>
                    <p>本月新增新增数量：{{total.rock.month_add}}</p>
                    <p>本年新增数量：{{total.rock.year_add}}</p>
                    <p>本月图片新增数量：{{total.rock.image_month_add}}</p>
                    <p>本年图片新增数量：{{total.rock.image_year_add}}</p>
                </el-card>
            </el-col>
            <el-col :lg="4" class="box-card">
                <el-card>
                    <div slot="header" class="clearfix">
                        <h3>植物</h3>
                    </div>
                    <p>总数量：{{total.plant.number}}</p>
                    <p>图片总数量：{{total.plant.image_number}}</p>
                    <p>本月新增新增数量：{{total.plant.month_add}}</p>
                    <p>本年新增数量：{{total.plant.year_add}}</p>
                    <p>本月图片新增数量：{{total.plant.image_month_add}}</p>
                    <p>本年图片新增数量：{{total.plant.image_year_add}}</p>
                </el-card>
            </el-col>
            <el-col :lg="4" class="box-card">
                <el-card>
                    <div slot="header" class="clearfix">
                        <h3>段面土壤标本</h3>
                    </div>
                    <p>总数量：{{total.soilBig.number}}</p>
                    <p>图片总数量：{{total.soilBig.image_number}}</p>
                    <p>本月新增新增数量：{{total.soilBig.month_add}}</p>
                    <p>本年新增数量：{{total.soilBig.year_add}}</p>
                    <p>本月图片新增数量：{{total.soilBig.image_month_add}}</p>
                    <p>本年图片新增数量：{{total.soilBig.image_year_add}}</p>
                </el-card>
            </el-col>
            <el-col :lg="4" class="box-card">
                <el-card>
                    <div slot="header" class="clearfix">
                        <h3>纸盒土壤标本</h3>
                    </div>
                    <p>总数量：{{total.soilSmall.number}}</p>
                    <p>图片总数量：{{total.soilSmall.image_number}}</p>
                    <p>本月新增新增数量：{{total.soilSmall.month_add}}</p>
                    <p>本年新增数量：{{total.soilSmall.year_add}}</p>
                    <p>本月图片新增数量：{{total.soilSmall.image_month_add}}</p>
                    <p>本年图片新增数量：{{total.soilSmall.image_year_add}}</p>
                </el-card>
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
//                    system : {
//                        user_total : '',
//                        alog_total : '',
//                    },
//                    asset : {
//                        number : '',
//                        sum : '',
//                        month_add : '',
//                        year_add : '',
//                    },
//                    farm : {
//                        //number : '1',
//                        //month_add : '3',
//                        //year_add : '4',
//                        //image_month_add : '5',
//                        //image_year_add : '6'
//                    },
//                    rock : {
////                        number : '1',
////                        month_add : '3',
////                        year_add : '4',
////                        image_month_add : '5',
////                        image_year_add : '6'
//                    }
                },
            }
        },
        mounted() {
            axios.get('/api/')
                .then(response => {
                    //console.log(response.data);
                    this.total = response.data;
                }).catch(error => {
                    if(error.response.status == 401){
                        this.$router.push('/login');
                    }
            });
        },
        methods : {
        }
    }
</script>

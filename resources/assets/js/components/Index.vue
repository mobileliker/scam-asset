/**
* @version 2.0
* @author: wuzhihui
* @date: 2017/9/30
* @description:
* （1）基本功能；
* （2）添加农具的相关统计数据；（2017/9/30）
* （3）添加岩石相关的统计数据；（2017/11/1）
* （4）修改首页的显示样式为藏品分类统计与其他统计分开；（2017/11/29）
* （5）修复文字描述的问题；（2017/12/12）
*
* @version : 2.0.3
* @author : wuzhihui
* @date : 2018/1/11
* @description :
* (1)新增藏品入库统计功能；（2018/1/11）
* (2)新增固定资产入库统计功能；（2018/1/23）
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
                    <p>本月新增数量：{{total.collection.month_add}}</p>
                    <p>本年新增数量：{{total.collection.year_add}}</p>
                    <p>本月图片新增数量：{{total.collection.image_month_add}}</p>
                    <p>本年图片新增数量：{{total.collection.image_year_add}}</p>
                </el-card>
            </el-col>
        </el-col>
        <el-col :lg="24">
            <div class="page-header">
                <h3>固定资产进库统计</h3>
            </div>
            <el-table :data="total.assetCount" style="width: 100%">
                <el-table-column prop="year" label="年份" width="90"></el-table-column>
                <el-table-column prop="1" label="农业文明史"></el-table-column>
                <el-table-column prop="2" label="传统农具"></el-table-column>
                <el-table-column prop="3" label="土壤与岩石"></el-table-column>
                <el-table-column prop="4" label="植物世界"></el-table-column>
                <el-table-column prop="5" label="动物世界"></el-table-column>
                <el-table-column prop="6" label="昆虫世界"></el-table-column>
                <el-table-column prop="7" label="林业资源与生产"></el-table-column>
                <el-table-column prop="8" label="南海海洋生物"></el-table-column>
                <el-table-column prop="9" label="可转让科技成果"></el-table-column>
                <el-table-column prop="sum" label="合计"></el-table-column>
            </el-table>
        </el-col>
        <el-col :lg="24">
            <div class="page-header">
                <h3>藏品进库统计</h3>
            </div>
            <el-table :data="total.yearCounts" style="width: 100%">
                <el-table-column prop="year" label="年份"></el-table-column>
                <el-table-column prop="farm" label="农具"></el-table-column>
                <el-table-column prop="rock" label="岩石"></el-table-column>
                <el-table-column prop="plant" label="植物"></el-table-column>
                <el-table-column prop="forestry" label="林业"></el-table-column>
                <el-table-column prop="animal" label="动物"></el-table-column>
                <el-table-column prop="soilBig" label="土壤段面"></el-table-column>
                <el-table-column prop="soilSmall" label="土壤纸盒"></el-table-column>
                <el-table-column prop="sum" label="合计"></el-table-column>
            </el-table>
        </el-col>
        <el-col :lg="24">
          <div class="page-header">
          <h3>藏品分类统计</h3>
          </div>
          <el-col :lg="4" class="box-card">
              <el-card>
                  <div slot="header" class="clearfix">
                      <h3>农具</h3>
                  </div>
                  <p>总数量：{{total.farm.number}}</p>
                  <p>图片总数量：{{total.farm.image_number}}</p>
                  <p>本月新增数量：{{total.farm.month_add}}</p>
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
                  <p>本月新增数量：{{total.rock.month_add}}</p>
                  <p>本年新增数量：{{total.rock.year_add}}</p>
                  <p>本月图片新增数量：{{total.rock.image_month_add}}</p>
                  <p>本年图片新增数量：{{total.rock.image_year_add}}</p>
              </el-card>
          </el-col>
            <el-col :lg="4" class="box-card">
                <el-card>
                    <div slot="header" class="clearfix">
                        <h3>林业资源</h3>
                    </div>
                    <p>总数量：{{total.forestry.number}}</p>
                    <p>图片总数量：{{total.forestry.image_number}}</p>
                    <p>本月新增数量：{{total.forestry.month_add}}</p>
                    <p>本年新增数量：{{total.forestry.year_add}}</p>
                    <p>本月图片新增数量：{{total.forestry.image_month_add}}</p>
                    <p>本年图片新增数量：{{total.forestry.image_year_add}}</p>
                </el-card>
            </el-col>
            <el-col :lg="4" class="box-card">
                <el-card>
                    <div slot="header" class="clearfix">
                        <h3>植物资源</h3>
                    </div>
                    <p>总数量：{{total.plant.number}}</p>
                    <p>图片总数量：{{total.plant.image_number}}</p>
                    <p>本月新增数量：{{total.plant.month_add}}</p>
                    <p>本年新增数量：{{total.plant.year_add}}</p>
                    <p>本月图片新增数量：{{total.plant.image_month_add}}</p>
                    <p>本年图片新增数量：{{total.plant.image_year_add}}</p>
                </el-card>
            </el-col>
          <el-col :lg="4" class="box-card">
              <el-card>
                  <div slot="header" class="clearfix">
                      <h3>动物</h3>
                  </div>
                  <p>总数量：{{total.animal.number}}</p>
                  <p>图片总数量：{{total.animal.image_number}}</p>
                  <p>本月新增数量：{{total.animal.month_add}}</p>
                  <p>本年新增数量：{{total.animal.year_add}}</p>
                  <p>本月图片新增数量：{{total.animal.image_month_add}}</p>
                  <p>本年图片新增数量：{{total.animal.image_year_add}}</p>
              </el-card>
          </el-col>
          <el-col :lg="4" class="box-card">
              <el-card>
                  <div slot="header" class="clearfix">
                      <h3>段面土壤标本</h3>
                  </div>
                  <p>总数量：{{total.soilBig.number}}</p>
                  <p>图片总数量：{{total.soilBig.image_number}}</p>
                  <p>本月新增数量：{{total.soilBig.month_add}}</p>
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
                  <p>本月新增数量：{{total.soilSmall.month_add}}</p>
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

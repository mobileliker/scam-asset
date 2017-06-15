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
            }
        },
        mounted() {
            axios.get('/api/')
                .then(response => {
                    //console.log(response.data);
                    this.total = response.data;
                });
        },
        methods : {
        }
    }
</script>

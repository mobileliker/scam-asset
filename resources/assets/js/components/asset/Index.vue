<template>
    <content-component id="content">
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separator="/">
              <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
              <el-breadcrumb-item>资产管理</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <el-col :lg="4">
            <el-select v-model="search.type.value" placeholder="类型">
                <el-option v-for="item in search.type.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="4">
            <el-date-picker v-model="search.post_date" type="daterange" align="right" placeholder="选择日期范围" :picker-options="search.post_date.pickerOptions"> </el-date-picker>
        </el-col>
        <el-col :lg="4">
            <el-input placeholder="请输入名称进行查询" icon="search" v-model="search.query_text" :on-icon-click="handleIconClick"></el-input>
        </el-col>
        <el-col :lg="4" class="pull-right">
            <el-button type="success">导出</el-button>
            <el-button type="success"><router-link to="/asset/create">添加</router-link></el-button>
        </el-col>
        <el-col :lg="24" class="list">
          <el-table :data="list.data" border style="width: 100%" :default-sort = "{prop: 'date', order: 'descending'}">
            <el-table-column type="selection"></el-table-column>
            <el-table-column prop="post_date" label="入账日期" sortable></el-table-column>
            <el-table-column prop="type" label="类型" sortable></el-table-column>
            <el-table-column prop="category_number" label="分类" sortable></el-table-column>
            <el-table-column prop="name" label="名称" sortable></el-table-column>
            <el-table-column prop="serial" label="编号" sortable></el-table-column>
            <el-table-column prop="sum" label="金额" sortable></el-table-column>
            <el-table-column prop="consumer_id" label="领用" sortable></el-table-column>
            <el-table-column prop="handler_id" label="经手" sortable></el-table-column>
            <el-table-column prop="image" label="图片" sortable></el-table-column>
            <el-table-column fixed="right" label="操作" width="100">
                <template scope="scope">
                    <el-button type="text" size="small">编辑</el-button>
                    <el-button type="text" size="small">删除</el-button>
                </template>
            </el-table-column>
          </el-table>
        </el-col>
        <el-col :lg="24">
            <el-button type="success">删除</el-button>

            <el-pagination
              @size-change="handleSizeChange"
              @current-change="handleCurrentChange"
              :current-page="currentPage4"
              :page-sizes="[100, 200, 300, 400]"
              :page-size="100"
              layout="total, sizes, prev, pager, next, jumper"
              :total="400" class="pull-right">
            </el-pagination>
        </el-col>
    </content-component>
</template>

<style scope>
    #breadcrumb {
        font-size: 16px;
        padding: 8px 15px;
        margin-bottom: 22px;
        list-style: none;
        background-color: #f5f5f5;
        border-radius: 4px;
    }
    button a{
            color: #fff;
    }
    .list {
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>

<script>
    import content from '../layouts/Content.vue'
    export default {
        data() {
            return {
                search : {
                    type : {
                        value : '',
                        options : [
                            {
                                value : '',
                                label : '类型'
                            },
                            {
                                value : 1,
                                label : '农业文明史展厅A(单据号为1xxxxxxx)'
                            }
                        ]
                    },
                    post_date : {
                        value : '',
                        pickerOptions: {
                          shortcuts: [{
                            text: '最近一周',
                            onClick(picker) {
                              const end = new Date();
                              const start = new Date();
                              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                              picker.$emit('pick', [start, end]);
                            }
                          }, {
                            text: '最近一个月',
                            onClick(picker) {
                              const end = new Date();
                              const start = new Date();
                              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                              picker.$emit('pick', [start, end]);
                            }
                          }, {
                            text: '最近三个月',
                            onClick(picker) {
                              const end = new Date();
                              const start = new Date();
                              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                              picker.$emit('pick', [start, end]);
                            }
                          }]
                        }
                    },
                    query_text : ''
                },
                list : {
                    data : []
                }
            }
        },
        components : {
            'content-component' : content
        },
        mounted() {
            axios.get('/admin/asset')
                .then(response => {
                    console.log(response);
                    this.list = response.data;
                })
                .catch(error => {
                    console.log(error.response);
                });
        }
    }
</script>
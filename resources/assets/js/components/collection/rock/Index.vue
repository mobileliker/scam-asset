/**
* @version 2.0
* @author: wuzhihui
* @date: 2017/10/18
* @description:
* （1）基本功能；（2017/10/18）
*/

<template>
    <content-component>
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" seprator="/">
                <el-breadcrumb-item :to="{path : '/'}">首页</el-breadcrumb-item>
                <el-breadcrumb-item>岩石管理</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <el-col :lg="3">
            <el-select v-model="search.keeper_id.value" placeholder="保管人" clearable>
                <el-option v-for="item in search.keeper_id.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="3">
            <el-select v-model="search.user_id.value" placeholder="编辑人" clearable>
                <el-option v-for="item in search.user_id.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="3">
            <el-select v-model="search.is_asset.value" placeholder="固定资产" clearable>
                <el-option v-for="item in search.is_asset.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        <el-col :lg="4">
            <el-input placeholder="请输入名称、英文名称进行搜索" icon="search" v-model="search.query_text"
                      on-icon-click="handleSearchIconClick"></el-input>
        </el-col>
        <el-col :lg="2" class="pull-right">
            <router-link to="/collection/rock/create">
                <el-button type="success">添加</el-button>
            </router-link>
        </el-col>
        <el-col :lg="24" class="list">
            <el-table :data="list.data" border style="width: 100%" v-loading="loading" element-loading-text="拼命加载数据中"
                      @selection-change="handleSelectionChange" @sort-change="handleSortChange">
                <el-table-column type="selection"></el-table-column>
                <el-table-column type="index" label="序号" width="70"></el-table-column>
                <el-table-column prop="input_date" label="入库时间" sortable></el-table-column>
                <el-table-column prop="category_name" label="类别" sortable></el-table-column>
                <el-table-column prop="name" label="名称" sortable>
                    <template scope="scope">
                        <router-link :to="'/collection/rock/' + scope.row.id">{{scope.row.name}}</router-link>
                    </template>
                </el-table-column>
                <el-table-column prop="ename" label="英文名称" sortable></el-table-column>
                <el-table-column prop="serial" label="编号" sortable></el-table-column>
                <el-table-column prop="keeper" label="保管人" sortable></el-table-column>
                <el-table-column prop="user" label="编辑人" sortable></el-table-column>
                <el-table-column fixed="right" label="操作" width="200">
                    <template scope="scope">
                        <el-button type="text" size="small" @click="handleImageClick(scope.row.id)">图片管理</el-button>
                        <el-button type="text" size="small">
                            <router-link :to="'/collection/rock/' + scope.row.id + '/edit'">编辑</router-link>
                        </el-button>
                        <el-button type="text" size="small"
                                   @click="handleDeleteRow(scope.$index, scope.row.id, list.data)">删除
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-col>
    </content-component>
</template>

<style scoped>
    #breadcrumb {
        font-size: 16px;
        padding: 8px 15px;
        margin-bottom: 22px;
        list-style: none;
        background-color: #f5f5f5;
        border-radius: 4px;
    }

    .list {
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>

<script>
    import content from '../../layouts/Content.vue'

    export default {
        components: {
            'content-component': content
        },
        data() {
            return {
                token: {'X-CSRF-TOKEN': window.Laravel.csrfToken},
                loading: false,
                fullscreenLoading: false,
                search: {
                    keeper_id: {
                        value: '',
                        options: []
                    },
                    user_id: {
                        value: '',
                        options: []
                    },
                    is_asset: {
                        value: '',
                        options: []
                    },
                    query_text: ''
                },
                list: {
                    current_page: 1,
                    from: '',
                    'last_page': '',
                    'next_page_url': '',
                    'per_page': 10,
                    'prev_page_url': '',
                    'to': '',
                    'total': 0,
                    data: [
//                        {
//                            id: '3',
//                            input_date: '2015-07-24',
//                            category_id: '1',
//                            category_name: '岩石',
//                            name: '白云石大理岩',
//                            ename: 'Dolomite-marble',
//                            serial: 'SCAMC0200001',
//                            keeper: 'admin',
//                            keeper_id: '1',
//                            user: 'admin',
//                            user_id: '2',
//                        }
                    ]
                },
                batch: {
                    rock: [],
                    params: {
                        ids: []
                    }
                },
            }
        },
        computed: {
            options: function () {
                return {
                    params: {
                        paginate: this.list.per_page,
                        page: this.list.current_page,
                        keeper_id: this.search.keeper_id.value,
                        user_id: this.search.user_id.value,
                        is_asset: this.search.is_asset.value,
                        query_text: this.search.query_text,
                        _sort: this.search._sort,
                        _order: this.search._order
                    }
                }
            }
        },
        mounted() {
            axios.get('/api/user/all')
                .then(response => {
                    this.search.keeper_id.options = response.data;
                    this.search.user_id.options = response.data;
                    this.load();
                });
        },
        methods: {
            load() {
                this.loading = true;
                axios.get('/api/collection/rock', this.options)
                    .then(response => {
                        this.list = response.data;
                        this.loading = false;
                    }).catch(error => {
                    this.$message.error('加载数据错误');
                    this.list.data = '';
                    this.loading = false;
                });

            },
            handleSearchIconClick() { //搜索按钮
                console.log('handleSearchIconClick');
                // TODO
            },
            handleSelectionChange() { //选择按钮
                console.log('handleSelectionChange');
                // TODO
            },
            handleSortChange() { //排序按钮
                console.log('handleSortChange');
                // TODO
            },
            handleImageClick(id) { //图片管理
                console.log('handleImageClick');
                // TODO
            },
            handleDeleteRow(index, id, data) {
                //console.log('handleDeleteRow');
                this.$confirm('此操作将永久删除该岩石，是否继续？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.delete('/api/collection/rock/' + id)
                        .then(response => {
                            this.$message({
                                type : 'success',
                                message : '删除成功'
                            });
                            data.splice(index, 1);
                        }).catch(error => {
                            if(error.response.status == 404){
                                this.$message.error('欲删除的岩石不存在');
                            }else{
                                this.$message.error('删除失败');
                            }
                        });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            }
        }
    }
</script>
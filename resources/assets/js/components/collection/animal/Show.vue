<template>
    <content-component>
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separtor="/">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item :to="{path : '/collection/animal'}">动物管理</el-breadcrumb-item>
                <el-breadcrumb-item>查看</el-breadcrumb-item>
            </el-breadcrumb>
            <el-col :lg="24">
                <el-card>
                    <div slot="header">
                        <span>基础信息</span>
                    </div>
                    <el-col :lg="12" class="info-item">
                        <p>分类：{{info.category}}</p>
                        <p>入库日期：{{info.input_date}}</p>
                        <p>名称：{{info.name}}</p>
                        <p>位置：{{info.storage}}</p>
                        <p>数量：{{info.number}}</p>
                        <p>编号：{{info.serial}}</p>
                        <p>门：{{info.phylum}}</p>
                        <p>纲：{{info.clazz}}</p>
                        <p>目：{{info.order}}</p>
                        <p>科：{{info.family}}</p>
                        <p>属：{{info.genus}}</p>
                        <p>描述：{{info.description}}</p>
                    </el-col>
                    <el-col :lg="12" class="info-item">
                        <p>拉丁名：{{info.latin}}</p>
                        <p>尺寸：{{info.size}}</p>
                        <p>保护等级（1989）：{{info.level_1989}}</p>
                        <p>保护等级（2015）：{{info.level_2015}}</p>
                        <p>保护等级（CITES）：{{info.level_1989}}</p>
                        <p>分布范围：{{info.range}}</p>
                        <p>生境：{{info.habitat}}</p>
                        <p>批次：{{info.batch}}</p>
                        <p>来源：{{info.source}}</p>
                        <p>保管人：{{info.keeper_name}}</p>
                        <p>固定资产编号：{{info.asset_id}}</p>
                        <p>备注：{{info.memo}}</p>
                    </el-col>
                </el-card>
            </el-col>
            <el-col :lg="24">
                <el-card class="item">
                    <div slot="header">
                        <span>图片信息</span>
                    </div>
                    <el-col v-for="(o, index) in 4" :span="6" :key="o" :offset="0">
                        <el-card body-style="{padding: '0px' }" v-for="(image, key) in images"
                                 v-if="(key % 4) == index">
                            <img :src="image.url" class="image">
                            <div style="padding: 14px;">
                                <div class="bottom clearfix">
                                    <time class="time">{{image.time}}</time>
                                    <el-button type="text" class="button" @click="imageView(image.path)">查看</el-button>
                                </div>
                            </div>
                        </el-card>
                    </el-col>
                </el-card>
            </el-col>
            <el-col :lg="24">
                <el-card>
                    <div slot="header">
                        <span>相似动物</span>
                    </div>

                    <el-table :data="list.data" border style="width: 100%">
                        <el-table-column type="index" label="序号" width="70"></el-table-column>
                        <el-table-column prop="input_date" label="入库时间" sortable></el-table-column>
                        <el-table-column prop="category" label="分类" sortable></el-table-column>
                        <el-table-column prop="name" label="名称" sortable>
                            <template scope="scope">
                                <router-link :to="'/collection/animal/' + scope.row.id">{{scope.row.name}}</router-link>
                            </template>
                        </el-table-column>
                        <el-table-column prop="serial" label="编号" sortable></el-table-column>
                        <el-table-column prop="source" label="来源" sortable></el-table-column>
                        <el-table-column prop="keeper" label="保管人" sortable></el-table-column>
                        <el-table-column prop="user" label="编辑人" sortable></el-table-column>
                    </el-table>
                </el-card>
            </el-col>
        </el-col>

        <el-dialog v-model="dialog.view.visible">
            <img width="100%" :src="dialog.view.path">
        </el-dialog>
    </content-component>
</template>

<style scoped>
    .item {
        padding-bottom: 20px;
    }

    .info-item {
        font-size: 16px;
    }

    .time {
        font-size: 13px;
        color: #999;
    }

    .bottom {
        margin-top: 13px;
        line-height: 12px;
    }

    .button {
        padding: 0;
        float: right;
    }

    .image {
        width: 100%;
        display: block;
    }

    .clearfix:before,
    .clearfix:after {
        display: table;
        content: "";
    }

    .clearfix:after {
        clear: both
    }
</style>

<script>
    import content from '../../layouts/Content.vue'

    export default {
        components: {
            'content-component': content,
        },
        data() {
            return {
                info: [],
                images: [],
                dialog : {
                    view : {
                        visible : false,
                        path : '',
                    }
                },
                list : {
                    data : [],
                    options : {
                        params : {
                            query_text : '',
                        }
                    }
                }
            }
        },
        mounted() {
            this.load();
        },
        watch : {
            '$route.params.id' : {
                handler : function(val, oldVal) {
                    this.load();
                },
                deep : true
            }
        },
        methods: {
            imageView(path) {
                this.dialog.view.path = path;
                this.dialog.view.visible = true;
            },
            load() {
                axios.get('/api/collection/animal/' + this.$route.params.id)
                    .then(response => {
                        //console.log(response.data);
                        this.info = response.data;

                        this.list.options.params.query_text = response.data.name;
                        //console.log(this.list.params);

                        axios.get('api/collection/animal/' + this.$route.params.id + '/relate', this.list.options)
                            .then(response => {
                                //console.log(response.data);
                                this.list.data = response.data;
                            });
                    });

                axios.get('api/collection/animal/' + this.$route.params.id + '/image')
                    .then(response => {
                        //console.log(response.data);
                        this.images = response.data;
                    });
            }
        }
    }
</script>

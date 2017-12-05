/**
* @version 2.0.2
* @author: wuzhihui
* @date: 2017/12/5
* @description:
* (1)基本功能；
* (2)新增source字段；（2017/12/5）
* (3)优化没有图片时显示提示字段；（2017/12/5）
*/

<template>
	<content-component>
		<el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separtor="/">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item :to="{path : '/collection/rock'}">岩石管理</el-breadcrumb-item>
                <el-breadcrumb-item>查看</el-breadcrumb-item>
            </el-breadcrumb>
            <el-col :lg="24">
            	<el-card>
            		<div slot="header">
            			<span>基础信息</span>
            		</div>
            		<el-col :lg="12" class="info-item">
            			<p>入库时间：{{info.input_date}}</p>
                        <p>名称：{{info.name}}</p>
                        <p>英文名称：{{info.ename}}</p>
                        <p>分类：{{info.classification}}</p>
                        <p>产地：{{info.origin}}</p>
                        <p>存放地点：{{info.storage}}</p>
												<p>来源：{{info.source}}</p>
                        <p>固定资产编号：{{info.asset_id}}</p>
                        <!--<p>状态：{{info.status}}</p>-->
            		</el-col>
            		<el-col :lg="12" class="info-item">
                        <p>种类：{{info.category}}</p>
                        <p>编号：{{info.serial}}</p>
                        <p>类别：{{info.category_name}}</p>
                        <p>特征：{{info.feature}}</p>
                        <p>尺寸：{{info.size}}</p>
                        <p>保管人：{{info.keeper_name}}</p>
												<p>最后编辑人：{{info.user_name}}</p>
												<p>最后编辑时间：{{info.updated_at}}</p>
            		</el-col>
                    <el-col :lg="24" class="info-item">
                        <p>描述：{{info.description}}</p>
                        <p>备注：{{info.memo}}</p>
                    </el-col>
            	</el-card>
            	<el-card class="item">
                    <div slot="header">
                        <span>图片信息</span>
                    </div>
										<template v-if="images.length > 0">
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
										</template>
										<template v-else>
										<p class="image-empty">暂无图片</p>
										</template>
            	</el-card>
            </el-col>
            <el-col :lg="24">
                <el-card>
                    <div slot="header">
                        <span>相似岩石</span>
                    </div>

                    <el-table :data="list.data" border style="width: 100%">
                        <el-table-column type="index" label="序号" width="70"></el-table-column>
                        <el-table-column prop="input_date" label="入库时间" sortable></el-table-column>
                        <el-table-column prop="category" label="类别" sortable></el-table-column>
                        <el-table-column prop="name" label="名称" sortable>
                            <template scope="scope">
                                <router-link :to="'/collection/rock/' + scope.row.id">{{scope.row.name}}</router-link>
                            </template>
                        </el-table-column>
                        <el-table-column prop="ename" label="英文名称" sortable></el-table-column>
                        <el-table-column prop="serial" label="编号" sortable></el-table-column>
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

		.image-empty {
			text-align :center;
			font-size : 16px;
		}
</style>

<script>
    import content from '../../layouts/Content.vue'
    export default {
    	components : {
    		'content-component' : content,
    	},
    	data () {
    		return {
    			info : [],
    			images : [],
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
    	methods : {
            imageView(path) {
                this.dialog.view.path = path;
                this.dialog.view.visible = true;
            },

    		load() {
    			axios.get('/api/collection/rock/' + this.$route.params.id)
    				.then(response => {
    					//console.log(response.data);
    					this.info = response.data;

                        this.list.options.params.query_text = response.data.name;
                        //console.log(this.list.params);

                        axios.get('api/collection/rock/' + this.$route.params.id + '/relate', this.list.options)
                            .then(response => {
                                //console.log(response.data);
                                this.list.data = response.data;
                            });

    				});


                axios.get('api/collection/rock/' + this.$route.params.id + '/image')
                    .then(response => {
                        //console.log(response.data);
                        this.images = response.data;
                    });
    		}
    	}
    }
</script>

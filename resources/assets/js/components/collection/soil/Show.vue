/**
* @version 2.0.2
* @author: wuzhihui
* @date: 2017/12/7
* @description:
* (1)基本功能；
* (2)优化没有图片时显示提示字段；（2017/12/7）
* (3)相似土壤新增最后编辑时间字段；（2017/12/7）
* (4)详情添加最后编辑人、最后编辑时间字段；（2017/12/7）
* (5)使用新的显示图片接口；（2017/12/12）
*/

<template>
	<content-component>
		<el-col :lg="24" v-loading.body="view.saveLoading">
            <el-breadcrumb id="breadcrumb" separtor="/">
                <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
                <el-breadcrumb-item :to="{path : '/collection/soil'}">土壤管理</el-breadcrumb-item>
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
                        <p>地区：{{info.region}}</p>
                        <p>编号：{{info.serial}}</p>
                        <p>采集地点：{{info.origin}}</p>
                        <p>经纬度：{{info.location}}</p>
                        <p>保管人：{{info.keeper_name}}</p>
												<p>最后编辑人：{{info.user_name}}</p>
												<p>最后编辑时间：{{info.updated_at}}</p>
            		</el-col>
            		<el-col :lg="12" class="info-item">
                        <p>海拔：{{info.altitude}}</p>
                        <p>地形：{{info.terrain}}</p>
                        <p>坡度：{{info.gradient}}</p>
                        <p>母质：{{info.matrix}}</p>
                        <p>植被：{{info.vegetation}}</p>
                        <p>利用状况：{{info.use_status}}</p>
                        <p>土层深度：{{info.depth}}</p>
                        <p>采集人：{{info.collecter}}</p>
                        <p>固定资产编号：{{info.asset_id}}</p>
            		</el-col>
                    <el-col :lg="24" class="info-item">
                        <p>描述：{{info.description}}</p>
                        <p>备注：{{info.memo}}</p>
                    </el-col>
            	</el-card>
            	<el-card class="item">
                    <div slot="header">
                        <span>图片信息(土壤断面和纸盒标本图片同时显示)</span>
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
									<span>段面标本</span>
									<div class="pull-right"><el-button type="success" @click="handleAdd('soil-big')">添加</el-button></div>
								</div>
								<el-table :data="list.soilBig.data" border style="width: 100%">
										<el-table-column type="index" label="序号" width="70"></el-table-column>
										<el-table-column prop="serial" label="编号" sortable></el-table-column>
										<el-table-column prop="storage" label="存放地点" sortable></el-table-column>
										<el-table-column fixed="right" label="操作" width="200">
											<template scope="scope">
												<el-button type="text" size="small" @click="handleImageClick('soil-big',scope.row.id)">图片管理</el-button>
												<el-button type="text" size="small" @click="handleEdit('soil-big', scope.row.id, scope.$index)">编辑</el-button>
												<el-button type="text" size="small" @click="handleDeleteRow('soil-big', scope.$index, scope.row.id, list.soilBig.data)">删除</el-button>
											</template>
										</el-table-column>
								</el-table>
							</el-card>
						</el-col>
						<el-col :lg="24">
							<el-card>
								<div slot="header">
									<span>纸盒标本</span>
									<div class="pull-right"><el-button type="success" @click="handleAdd('soil-small')">添加</el-button></div>
								</div>
								<el-table :data="list.soilSmall.data" border style="width: 100%">
										<el-table-column type="index" label="序号" width="70"></el-table-column>
										<el-table-column prop="serial" label="编号" sortable></el-table-column>
										<el-table-column prop="storage" label="存放地点" sortable></el-table-column>
										<el-table-column fixed="right" label="操作" width="200">
											<template scope="scope">
												<el-button type="text" size="small" @click="handleImageClick('soil-small',scope.row.id)">图片管理</el-button>
												<el-button type="text" size="small" @click="handleEdit('soil-small', scope.row.id, scope.$index)">编辑</el-button>
												<el-button type="text" size="small" @click="handleDeleteRow('soil-small', scope.$index, scope.row.id, list.soilSmall.data)">删除</el-button>
											</template>
										</el-table-column>
								</el-table>
							</el-card>
						</el-col>
            <el-col :lg="24">
                <el-card>
                    <div slot="header">
                        <span>相似土壤</span>
                    </div>
                    <el-table :data="list.data" border style="width: 100%">
                        <el-table-column type="index" label="序号" width="70"></el-table-column>
                        <el-table-column prop="input_date" label="入库时间" sortable></el-table-column>
                        <el-table-column prop="name" label="名称" sortable>
                            <template scope="scope">
                                <router-link :to="'/collection/soil/' + scope.row.id">{{scope.row.name}}</router-link>
                            </template>
                        </el-table-column>
                        <el-table-column prop="ename" label="英文名称" sortable></el-table-column>
                        <el-table-column prop="origin" label="地区" sortable></el-table-column>
                        <el-table-column prop="serial" label="编号" sortable></el-table-column>
                        <el-table-column prop="keeper" label="保管人" sortable></el-table-column>
                        <el-table-column prop="user" label="编辑人" sortable></el-table-column>
												<el-table-column prop="updated_at" label="最后编辑时间" sortable></el-table-column>
                    </el-table>
                </el-card>
            </el-col>
		</el-col>


        <el-dialog v-model="dialog.view.visible">
            <img width="100%" :src="dialog.view.path">
        </el-dialog>

				<el-dialog title="新增数据" v-model="dialog.add.visible" size="small">
				<error-component v-if="Object.getOwnPropertyNames(dialog.add.errors).length > 1" :berrors="dialog.add.errors"></error-component>
					<el-form v-model="dialog.add.model" ref="addModel" :rules="rules">
						<el-form-item label="编号" prop="serial">
							<el-input v-model="dialog.add.model.serial" placeholder="编号"></el-input>
						</el-form-item>
						<el-form-item label="存放地点" prop="storage">
							<el-input v-model="dialog.add.model.storage" placeholder="存放地点"></el-input>
						</el-form-item>
					</el-form>
					<div slot="footer" class="dialog-footer">
							<el-button @click="dialog.add.visible=false">取消</el-button>
							<el-button type="primary" @click="handleAddSave" element-loading-text="保存中..." v-loading.fullscreen.lock="dialog.add.fullscreenLoading">保存</el-button>
					</div>
				</el-dialog>
        <szy-image-dialog :prefix="dialog.image.prefix" :data="dialog.image.data"
                          v-model="dialog.image.visible"></szy-image-dialog>
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
    import error from '../../layouts/Error.vue'
    import szyImageDialog from '../../Basic/Dialog/SzyImageDialog.vue'
    export default {
    	components : {
    		'content-component' : content,
				'error-component': error,
				'szy-image-dialog': szyImageDialog
    	},
    	data () {
    		return {
					rules : {
						//serial : [
						//	{required : true, message : '请输入编号', trigger : "blur"},
						//	{max : 10, message : '编号不能超过255个字', trigger : "blur"}
						//],
						//storage : [
						//	{max : 10, message : '存放地点不能超过255个字', trigger : "blur"}
						//]
					},
					view : {
						saveLoading : false
					},
    			info : [],
    			images : [],
                dialog : {
                    view : {
                        visible : false,
                        path : '',
                    },
										add : {
											prefix : '',
											visible : false,
											id : '',
											index : '',
											errors : {},
											fullscreenLoading : false,
											model : {
												serial : '',
												storage : ''
											},
										},
                    image: {
                        visible: false,
                        prefix: '',
                        data: [],
                        modal: false
                    },
                },
                list : {
                    data : [],
                    options : {
                        params : {
                            query_text : '',
                        }
                    },
										soilBig : {
											data : []
										},
										soilSmall : {
											data : []
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
    			axios.get('/api/collection/soil/' + this.$route.params.id)
    				.then(response => {
    					//console.log(response.data);
    					this.info = response.data;

                        this.list.options.params.query_text = response.data.name;
                        //console.log(this.list.params);

                        axios.get('api/collection/soil/' + this.$route.params.id + '/relate', this.list.options)
                            .then(response => {
                                //console.log(response.data);
                                this.list.data = response.data;
                            });

    				});


                axios.get('api/collection/soil/' + this.$route.params.id + '/image2')
                    .then(response => {
                        //console.log(response.data);
                        this.images = response.data;
                    });

					axios.get('api/collection/soil/' + this.$route.params.id + '/soil-big')
						.then((response) => {
							this.list.soilBig.data = response.data;
						});

				axios.get('api/collection/soil/' + this.$route.params.id + '/soil-small')
					.then((response) => {
						this.list.soilSmall.data = response.data;
					});
    		},

				handleAdd(prefix) {
					this.dialog.add.prefix = prefix;
					this.dialog.add.visible = true;
				},

				handleAddSave()
				{
					//console.log('handleAddSave');
					this.$refs['addModel'].validate( (valid) => {
						if(valid) {
							this.view.savaLoading = true;

							if(this.dialog.add.id != '') {
						 		//axios.put('/api/collection/soil/' + this.$route.params.id + '/' + this.dialog.add.prefix +'/' + this.dialog.add.id, this.dialog.add.model)
							 	axios.post('/api/collection/soil/' + this.$route.params.id + '/' + this.dialog.add.prefix +'/' + this.dialog.add.id + '/update', this.dialog.add.model)
						 			.then((response) => {
						 				if(this.dialog.add.prefix == 'soil-big') Vue.set(this.list.soilBig.data, this.dialog.add.index, response.data);
						 				else  Vue.set(this.list.soilSmall.data, this.dialog.add.index, response.data);
						 				this.$message('保存成功');
						 				this.dialog.add.visible = false;
						 			});
						 } else {
								axios.post('/api/collection/soil/' + this.$route.params.id + '/' + this.dialog.add.prefix, this.dialog.add.model)
									.then( (response) => {
										this.$message('保存成功');
										this.dialog.add.visible = false;
										if(this.dialog.add.prefix == 'soil-big') this.list.soilBig.data.unshift(response.data);
										else this.list.soilSmall.data.unshift(response.data);
									}).catch((error) => {
										if(error.response.status == 422) {
											this.dialog.add.errors = error.response.data;
										}else {
											this.$message.error(error.response);
										}
									});
							}
							this.view.saveLoading = false;
						}
					})
				},

				handleEdit(prefix, id, index)
				{
					this.dialog.add.prefix = prefix;
					this.dialog.add.id = id;
					this.dialog.add.index = index;

					axios.get('/api/collection/soil/' + this.$route.params.id + '/' + prefix + '/' + id + '/edit')
						.then((response) => {
							this.dialog.add.model = response.data;
							this.dialog.add.visible = true;
						}).catch((error) => {
							this.$message.error('获取标本信息失败');
						})
				},

				handleDeleteRow(prefix, index, id, data)
				{
						this.$confirm('此操作将永久删除该标本，是否继续？', '提示', {
								confirmButtonText: '确定',
								cancelButtonText: '取消',
								type: 'warning'
						}).then(() => {
							//axios.delete('/api/collection/soil/' + this.$route.params.id + '/' + prefix + '/' + id)
							axios.get('/api/collection/soil/' + this.$route.params.id + '/' + prefix + '/' + id + '/delete')
								.then((response) => {
									data.splice(index, 1);
											this.$message({
													type : 'success',
													message : '删除成功'
											});
								}).catch((error) => {
								if(error.response.status == 404){
										this.$message.error('欲删除的标本不存在');
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
				},

				handleImageClick(prefix, id) {
					console.log(prefix);
						this.dialog.image.prefix = 'api/collection/soil/' + this.$route.params.id + '/' + prefix + '/' + id;
						this.dialog.image.visible = true;
				},
    	}
    }
</script>

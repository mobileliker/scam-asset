/**
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/5
 * description:
 * （1）去除原有的type；（2017/7/5）
 * （2）去除用户列表的范例数据；（2017/7/14）
 *
 * @version: 2.0.2
 * @author: wuzhihui
 * @date: 2017/12/14
 * @description:
 * （1）修改用户删除和更新的接口；（2017/12/14）
 */
<template>
    <content-component id="content">
        <!--user-index-->
        <el-col :lg="24">
            <el-breadcrumb id="breadcrumb" separator="/">
              <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
              <el-breadcrumb-item>用户管理</el-breadcrumb-item>
            </el-breadcrumb>
        </el-col>
        <!--
        <el-col :lg="4">
            <el-select v-model="search.type.value" placeholder="类型" @change="typeChange">
                <el-option v-for="item in search.type.options" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-col>
        -->
        <el-col :lg="4">
            <el-input placeholder="请输入名称进行查询" icon="search" v-model="search.query_text" :on-icon-click="textQuery"></el-input>
        </el-col>
        <el-col :lg="4" class="pull-right">
            <el-button type="success" @click="showAddDialog">新增</el-button>
        </el-col>
        <el-col :lg="24" class="list">
          <el-table :data="list.data" border style="width: 100%" v-loading="view.table.loading" element-loading-text="拼命加载数据中" @selection-change="handleSelectionChange" @sort-change="sortChange">
            <el-table-column type="selection"></el-table-column>
            <el-table-column type="index" label="序号" width="70"></el-table-column>
            <el-table-column prop="name" label="用户名" sortable></el-table-column>
            <el-table-column prop="email" label="邮箱" sortable></el-table-column>
            <!--<el-table-column prop="type" label="类型" sortable></el-table-column>-->
            <el-table-column fixed="right" label="操作" width="150">
                <template scope="scope">
                    <el-button type="text" size="small" @click="showEditDialog(scope.$index, scope.row.id)">编辑</el-button>
                    <el-button type="text" size="small" @click="deleteRow(scope.$index, scope.row.id, list.data)">删除</el-button>
                </template>
            </el-table-column>
          </el-table>
        </el-col>
        <el-col :lg="24">
            <el-button type="success" @click="batchDelete">删除</el-button>
            <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="list.current_page" :page-sizes="['10', '15', '20', '50']" :page-size="list.per_page" layout="total, sizes, prev, pager, next, jumper" :total="list.total" class="pull-right">
            </el-pagination>
        </el-col>

        <el-dialog :title="view.dialog.title" v-model="view.dialog.visible" size="small">
            <error-component v-if="Object.getOwnPropertyNames(dialog.errors).length > 1" :berrors="dialog.errors"></error-component>
              <el-form :model="dialog.model" ref="modelForm" :rules="dialog.rules">
                <el-form-item label="用户名" prop="name">
                  <el-input v-model="dialog.model.name" placeholder="用户名"></el-input>
                </el-form-item>
                <el-form-item label="邮箱" prop="email">
                  <el-input :disabled="view.dialog.emailVisible" v-model="dialog.model.email" placeholder="邮箱"></el-input>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                  <el-input v-model="dialog.model.password" placeholder="密码"></el-input>
                </el-form-item>
                <!--
                <el-form-item label="类型" prop="type">
                  <el-select v-model="dialog.model.type" placeholder="类型">
                    <el-option v-for="item in dialog.content.type.options" :label="item.label" :value="item.value"></el-option>
                  </el-select>
                </el-form-item>
                -->
                <el-form-item label="角色" prop="role_ids">
                     <el-checkbox-group v-model="dialog.model.role_ids">
                        <el-checkbox v-for="item in dialog.content.role" :label="item.value">{{item.label}}</el-checkbox>
                      </el-checkbox-group>
                </el-form-item>
              </el-form>
              <div slot="footer" class="dialog-footer">
                <el-button>取 消</el-button>
                <el-button type="primary" @click="dialogSave">确 定</el-button>
              </div>
        </el-dialog>
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
    .el-button--success a{
            color: #fff;
    }
    .list {
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>

<script>
    import content from '../layouts/Content.vue'
    import error from '../layouts/Error.vue'
    export default {
        components : {
            'content-component' : content,
            'error-component' : error
        },
        data() {
            var checkEmail = (rule, value, callback) => {
                var parames = {
                    'id' : this.dialog.model.id,
                    'field' : 'email',
                    'value' : value
                };
                setTimeout(() => {
                    axios.post('/api/user/check', parames)
                        .then(response => {
                            if(response.data.res == 'true') callback();
                            else callback(new Error('您输入的邮箱已被使用'));
                        });
                }, 1000);
            };
            return {
                search : {
                    type : {
                        value : '',
                        options : [
                            {
                                label : '管理员',
                                value : '1'
                            },
                            {
                                label : '用户',
                                value : '2'
                            }
                        ]
                    },
                    query_text : '',
                    _sort : 'id',
                    _order : 'desc',
                },
                list : {
                    current_page: 1,
                    from : '',
                    'last_page' : '',
                    'next_page_url' : '',
                    'per_page' : 10,
                    'prev_page_url' : '',
                    'to' : '',
                    'total' : 0,
                    data : [
//                        {
//                            id : '1',
//                            name : '张三',
//                            email : 'abc@123.com'
//                        },
//                        {
//                            id : '1',
//                            name : '张三',
//                            email : 'abc@123.com'
//                        }
                    ]
                },
                batch : {
                    list : [],
                    params : {
                        ids : []
                    }
                },
                dialog : {
                    content : {
                        type : {
                            options : [
                                {
                                    value : 1,
                                    label : '用户'
                                },
                                {
                                    value : 2,
                                    label : '管理员'
                                }
                            ]
                        },
                        role : [
                            {
                                value : 1,
                                label : '用户'
                            },
                            {
                                value : 2,
                                label : '管理员'
                            }
                        ]
                    },
                    model : {
                        name : '',
                        email : '',
                        password : '',
                        type : 1,
                        role_ids: [],
                    },
                    rules : {
                        name : [
                            { required : true, message: '请输入用户名', trigger: 'blur' },
                            {max: 255, message: '用户名不能操作20个字', trigger: 'blur'}
                        ],
                        email : [
                            { required : true, message: '请输入邮箱', trigger: 'blur' },
                            { type: 'email', message: '请输入正确的邮箱地址', trigger: 'blur,change' },
                            { validator: checkEmail, trigger: 'blur' }
                        ],
                        password : [
                            { min: 8, max: 20, message: '请输入8至20位的用户密码', trigger: 'blur,change' }
                        ],
                    },
                    errors : {},
                },
                view : {
                    table : {
                        loading : false,
                    },
                    dialog : {
                        method : '',
                        index : '',
                        title : '',
                        visible : false,
                        emailVisible : true,
                    }
                }
            }
        },
        computed : {
            params : function() {
                return {
                    index : {
                        params : {
                            paginate: this.list.per_page,
                            page: this.list.current_page,
                            type: this.search.type.value,
                            'query_text' : this.search.query_text,
                            '_sort' : this.search._sort,
                            '_order' : this.search._order
                        }
                    }
                }
            }
        },
        mounted() {
            //console.log('mounted');
            this.load();
        },
        methods : {
            load() {
                //console.log('load');
                this.view.table.loading = true;

                axios.get('/api/user', this.params.index)
                    .then(response => {
                        //console.log(response);
                        this.list = response.data;

                    }).catch(error => {
                        console.log(error);
                    });

                this.view.table.loading = false;

                axios.get('/api/role/all')
                    .then(response => {
                        //console.log(response.data);
                        this.dialog.content.role = response.data;
                        for (var i = 0; k < this.dialog.content.role.length; k++) {
                            this.dialog.content.role[i].value = parseInt(this.dialog.content.role[i].value);
                        }
                    });
            },
            typeChange() {
                this.list.current_page = 1;
                this.load();
            },
            //文本查询
            textQuery() {
                //console.log('testQuery');
                this.list.current_page = 1;
                this.load();
            },
            //多选框选择
          handleSelectionChange(val) {
            //console.log(val);
            this.batch.list = val;
          },

          //排序选择
          sortChange(val) {
            //console.log(val);
            this.search._sort = val.prop;
            if(val.order == 'descending')  this.search._order = 'desc';
            else this.search._order = 'asc';
            this.load();
          },

          //单个删除
          deleteRow(index, id, data) {
            //console.log('deleteRow');
                this.$confirm('此操作将永久删除该文件, 是否继续?', '提示', {
                  confirmButtonText: '确定',
                  cancelButtonText: '取消',
                  type: 'warning'
                }).then(() => {
                    axios.get('/api/user/' + id + '/delete')
                        .then(response => {
                          this.$message({
                            type: 'success',
                            message: '删除成功!'
                          });
                          data.splice(index, 1);
                        }).catch(error => {
                            if(error.response.status == 404){
                                this.$message.error('欲删除的用户不存在');
                            }else if(error.response.status == 500){
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

          //每页条数更改
          handleSizeChange(val) {
            this.list.per_page = val;
            //console.log(`每页 ${val} 条`);
            this.load();
          },

          //跳转页码
          handleCurrentChange(val) {
            this.list.current_page = val;
            //console.log(`当前页: ${val}`);
            this.load();
          },

          //批量删除
          batchDelete() {
            //console.log('batchDelete');
            var ids = new Array();
            for(var i in this.batch.list){
                //console.log(this.batch.list[i]);
                ids.push(this.batch.list[i].id);
            }
            //console.log(ids);
            this.batch.params.ids = ids;
            console.log(this.batch.params);
            axios.post('/api/user/batch-delete', this.batch.params)
                .then(response => {
                    //console.log(response);
                    this.load();
                }).catch(error => {
                    console.log(error);
                });
          },

          //显示新增弹窗
          showAddDialog(){
            this.view.dialog.method = 'ADD';
            this.view.dialog.title = "新增用户";
            if(this.$refs['modelForm'] != null) this.$refs['modelForm'].resetFields();
            console.log(this.dialog.rules.password.length);
            if(this.dialog.rules.password.length == 1) this.dialog.rules.password.push({ required : true, message: '请输入密码', trigger: 'blur' });
            this.view.dialog.visible = true;
            this.view.dialog.emailVisible = false;
          },
          //显示编辑弹窗
          showEditDialog(index, id) {
            axios.get('/api/user/' + id + '/edit')
                .then(response => {
                    this.view.dialog.method = 'EDIT';
                    this.view.dialog.title = "编辑用户";
                    this.view.dialog.emailVisible = true;
                    this.dialog.model = response.data;
                    this.view.dialog.index = index;
                    if(this.$refs['modelForm'] != null) this.$refs['modelForm'].resetFields();
                    if(this.dialog.rules.password.length == 2) this.dialog.rules.password.pop();
                    this.view.dialog.visible = true;
                }).catch(error => {
                    if(error.response.status == 404) this.$message.error('欲编辑的用户不存在');
                    else this.$message.error('未知错误');
                });
          },
          //弹窗保存
          dialogSave() {
            this.$refs['modelForm'].validate((valid) => {
                if(valid){
                    //console.log('validate success');
                    //console.log(this.dialog.model);
                    if(this.view.dialog.method == 'ADD'){
                        axios.post('/api/user', this.dialog.model)
                            .then(response => {
                                //console.log(response);
                                this.list.data.unshift(response.data);
                                this.view.dialog.visible = false;
                                  this.$message({
                                    type: 'success',
                                    message: '新增成功!'
                                  });
                            })
                            .catch(error => {
                                //console.log(error);
                                if(error.response.status == 422) {
                                    this.dialog.errors = error.response.data;
                                } else if(error.response.status == 500){
                                    this.view.dialog.visible = false;
                                    this.$message.error('保存失败');
                                }else {
                                    this.$message.error('未知错误');
                                }
                            });
                    } else {
                        axios.post('/api/user/' + this.dialog.model.id + '/update', this.dialog.model)
                            .then(response => {
                                //console.log(response);
                                console.log(this.list.data[this.view.dialog.index]);
                                //this.list.data[this.view.dialog.index] = response.data;
                                Vue.set(this.list.data, this.view.dialog.index, response.data);
                                this.view.dialog.visible = false;
                                  this.$message({
                                    type: 'success',
                                    message: '修改成功!'
                                  });
                            })
                            .catch(error => {
                                //console.log(error);
                                if(error.response.status == 422) {
                                    this.dialog.errors = error.response.data;
                                } else if(error.response.status == 500){
                                    this.view.dialog.visible = false;
                                    this.$message.error('修改失败');
                                }else {
                                    this.$message.error('未知错误');
                                }
                            });
                    }
                }
            });
          }
        }
    }
</script>
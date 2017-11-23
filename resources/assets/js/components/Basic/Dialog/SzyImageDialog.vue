<template>
    <div>
        <el-dialog title="图片管理" v-model="viewVisible" size="small">
            <el-upload :action="prefix + '/image'"
                       :headers="token"
                       list-type="picture-card"
                       :file-list="fileList"
                       :on-preview="handlePreview"
                       :on-remove="handleRemove"
                       :on-success="handleSuccess">
                <i class="el-icon-plus"></i>
            </el-upload>
        </el-dialog>
        <el-dialog v-model="view.visible">
            <img width="100%" :src="view.url" alt="">
        </el-dialog>
    </div>
</template>

<script>
    export default {
        model: {
            prop: 'visible',
        },
        props: {
            visible: Boolean,
            prefix: {
                type: String,
                default: 'api'
            }
        },
        data() {
            return {
                token: {'X-CSRF-TOKEN': window.Laravel.csrfToken},
                fileList: [],
                view: {
                    visible: false,
                    url: ''
                },
                viewVisible: false,
            }
        },
        mounted() {
            //console.log('mounted');
        },
        watch: {
            visible: {
                handler: function (val, oldVal) {

                    this.viewVisible = this.visible;
                },
                deep: true
            },
            viewVisible: {
                handler: function (val, oldVal) {
                    this.visible = this.viewVisible
                },
                deep: true
            },
            prefix: {
                handler: function (val, oldVal) {
                    console.log('prefix');
                    axios.get(this.prefix + '/image')
                        .then(response => {
                            //console.log(response);
                            this.fileList = response.data;
                        });
                }
            }
        },
        methods: {
            handleRemove(file, fileList) {
                //console.log(file, fileList);
                //this.$emit('handleRemove', file.id);

                axios.delete(this.prefix + '/image/' + file.id)
                    .then(response => {
                        this.$message('删除成功');
                    }).catch(error => {
                        this.$message.error('删除失败');
                });
            },
            handlePreview(file) {
                //console.log(file);
                this.view.url = file.url;
                this.view.visible = true;
            },
            handleSuccess(file, fileList) {
                //console.log(file, fileList);
                //this.fileList.push(file);
            }
        }
    }
</script>
<template>
    <el-alert id="error-alert" title="错误" type="error">
        <ul>
            <div v-for="(error, key) in berrors">
                <li v-for="item in error">
                {{item}}
                </li>
            </div>
        </ul>
    </el-alert>
</template>
<style scope>
    #error-alert {
        margin-bottom : 22px;
    }
</style>
<script>
    export default{
    props: {
    },
        data(){
            return{
            }
        },
        props : {
            berrors : null
        },
        mounted(){
            console.log(this.errors);
        },
        components:{
        }
    }
</script>

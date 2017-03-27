<template>
  <div>
    <router-view></router-view>
  </div>
</template>

<script>

import index from './Index.vue'

    export default {
        components : {
          'index-component' : index
        }
    }
</script>

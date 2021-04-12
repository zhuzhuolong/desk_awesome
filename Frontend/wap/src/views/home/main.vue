<template>
<div>
    <router-view />
    <van-tabbar v-model="active">
        <van-tabbar-item icon="home-o">标签</van-tabbar-item>
        <van-tabbar-item icon="search">标签</van-tabbar-item>
        <van-tabbar-item icon="friends-o">标签</van-tabbar-item>
        <van-tabbar-item icon="setting-o">标签</van-tabbar-item>
    </van-tabbar>
</div>
</template>

<script>
import {
    Tabbar,
    TabbarItem
} from 'vant';
export default {
    data() {
        return {
            active: 0
        }
    }
};
</script>

<style lang="less">

</style>

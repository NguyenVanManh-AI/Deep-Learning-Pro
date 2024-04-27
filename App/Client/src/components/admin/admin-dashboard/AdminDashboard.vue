<template>
    <div>
        <div>
            <AdminSideBar></AdminSideBar>
        </div>
        <div class="rightBarAdmin">
            <CommonHeader></CommonHeader>
            <div class="innerContent">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>
<script>

import AdminSideBar from '@/components/admin/admin-components/AdminSideBar.vue'
import CommonHeader from '@/components/common/CommonHeader.vue'

export default {
    name: "AdminDashboard",
    components: {
        AdminSideBar,
        CommonHeader,
    },
}
</script>
<style scoped>
.rightBarAdmin {
    transition: width 0.5s ease;
    min-height: 100vh;
    padding: 6px;
}

.innerContent {
    border-radius: 4px;
    margin: 6px 10px;
    background-color: white;
    padding: 6px;
}

.innerContent>div {
    box-shadow: rgba(136, 165, 191, 0.48) 6px 2px 16px 0px, rgba(255, 255, 255, 0.8) -6px -2px 16px 0px;
    border-radius: 4px;
    padding: 10px 15px;
    padding-bottom: 20px;
    background-color: white;
}

@media screen and (min-width: 993px) and (max-width: 1200px) {}

@media screen and (min-width: 769px) and (max-width: 992px) {}

@media screen and (min-width: 577px) and (max-width: 768px) {}

@media screen and (min-width: 425px) and (max-width: 575px) {}

@media screen and (min-width: 375px) and (max-width: 424px) {}

</style>
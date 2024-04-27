<template>
    <div>
        <div>
            <UserSidebar></UserSidebar>
        </div>
        <div class="rightBar">
            <CommonHeader></CommonHeader>
            <div class="innerContent">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>
<script>

import UserSidebar from '@/components/user/user-components/UserSidebar.vue'
import CommonHeader from '@/components/common/CommonHeader.vue'

export default {
    name: "UserMain",
    components: {
        UserSidebar,
        CommonHeader,
    },
}
</script>
<style scoped>
.rightBar {
    transition: width 0.5s ease;
    min-height: 100vh;
    /* min-width: 375px; */
}

.innerContent {
    border-radius: 4px;
    margin: 6px 10px;
    background-color: white;
    padding: 10px;
    margin-top: 16px;
    /* min-width: 375px; */
}

.innerContent>div {
    box-shadow: rgba(136, 165, 191, 0.48) 6px 2px 16px 0px, rgba(255, 255, 255, 0.8) -6px -2px 16px 0px;
    border-radius: 4px;
    padding: 10px 15px;
    padding-bottom: 20px;
    background-color: white;
}

@media screen and (min-width: 1201px) {}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    .innerContent {
        padding: 9px;
    }
}

@media screen and (min-width: 769px) and (max-width: 992px) {
    .innerContent {
        padding: 8px;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {
    .innerContent {
        padding: 7px;
        margin-top: 13px;
    }
}

@media screen and (min-width: 425px) and (max-width: 576px) {
    .innerContent {
        padding: 5px;
        margin-top: 10px;
    }
}

@media screen and (min-width: 375px) and (max-width: 424px) {
    .innerContent {
        padding: 5px;
        margin-top: 10px;
    }
}
</style>
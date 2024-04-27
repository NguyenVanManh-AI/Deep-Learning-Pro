<template>
    <div id="main">
        <div>
            <h3 class="title-channel"><i class="fa-brands fa-line"></i> Manage Broadcast - Mutilcast </h3>
        </div>
        <div class="ml-2 mt-2">
            <div class="mt-3">
                <div class="mb-2">
                    <div class="color-title"><i class="fa-solid fa-comments"></i> Manage Broadcast of Channel</div>
                </div>
                <div class="row m-0 pb-2 d-flex justify-content-end gap-2" id="search-sort">
                    <div class="pl-0" id="page">
                        <select content="Pagination" v-tippy class="form-control form-control-sm"
                            v-model="big_search.perPage">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                    <div class="pl-0">
                        <select content="Sort by" v-tippy class="form-control form-control-sm"
                            v-model="big_search.typesort">
                            <option value="new">New</option>
                            <option value="name">Name</option>
                            <option value="status">Status</option>
                            <option value="title">Title</option>
                        </select>
                    </div>
                    <div class="pl-0">
                        <select content="In direction" v-tippy class="form-control form-control-sm"
                            v-model="big_search.sortlatest">
                            <option value="false">Ascending</option>
                            <option value="true">Decrease</option>
                        </select>
                    </div>
                    <div class="pl-0">
                        <select content="Filter by delete" v-tippy class="form-control form-control-sm"
                            v-model="big_search.is_delete">
                            <option value="all">All Broadcast</option>
                            <option value="1">Deleted Broadcast</option>
                            <option value="0">Normal Broadcast</option>
                        </select>
                    </div>
                    <div class="pl-0">
                        <select content="Filter by broadcast status" v-tippy class="form-control form-control-sm"
                            v-model="big_search.status">
                            <option value="all">All Broadcast</option>
                            <option value="draf">Draft</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="sent">Sent</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="pl-0" v-if="user.role == 'manager'">
                        <select content="Role" v-tippy class="form-control form-control-sm" v-model="big_search.role">
                            <option value="all">All</option>
                            <option value="manager">Manager</option>
                            <option value="user">Member</option>
                        </select>
                    </div>
                    <div class="pl-0">
                        <div content="Search information broadcast" v-tippy class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>
                            </div>
                            <input v-model="search" type="text" class="form-control form-control-sm"
                                id="inlineFormInputGroup" placeholder="Search...">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <div>
                            <div class="input-group ">
                                <button content="Test Send Mutilcast" v-tippy data-toggle="modal"
                                    data-target="#testSendMutilcast" type="button" class="btn btn-info"><i
                                        class="fa-solid fa-message"></i></button>
                            </div>
                        </div>
                        <div>
                            <div class="input-group ">
                                <button content="Add Broadcast" v-tippy data-toggle="modal" data-target="#addBroadcast"
                                    type="button" class="btn btn-success"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                        <div v-if="selectedBroadcasts.length > 0">
                            <div class="input-group">
                                <button @click="changedeleteManyBroadcast(1)" content="Delete Many Broadcast" v-tippy
                                    data-toggle="modal" data-target="#deleteManyBroadcast" type="button"
                                    class="btn btn-outline-danger mr-1"><i class="fa-solid fa-trash"></i></button>
                                <button @click="changedeleteManyBroadcast(0)" content="Backup Many Broadcast" v-tippy
                                    data-toggle="modal" data-target="#deleteManyBroadcast" type="button"
                                    class="btn btn-outline-success"><i class="fa-solid fa-trash-arrow-up"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="isLoading">
                    <TableLoading :cols="6" :rows="9"></TableLoading>
                </div>
                <div v-if="!isLoading" class="table-data">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col"><input ref="selectAllCheckbox" @change="selectAll()" type="checkbox"
                                        class=""></th>
                                <th scope="col">#</th>
                                <th scope="col"><i class="fa-solid fa-shapes"></i> Status</th>
                                <th scope="col"><i class="fa-solid fa-heading"></i> Title</th>
                                <th scope="col"><i class="fa-solid fa-user-clock"></i> Sender</th>
                                <th scope="col" class="text-center"><i class="fa-solid fa-clock"></i> Send At</th>
                                <th scope="col" class="text-center"><i class="fa-solid fa-calendar-day"></i> Created at
                                </th>
                                <th scope="col" class="text-center"><i class="fa-solid fa-calendar-check"></i> Updated
                                    at
                                </th>
                                <th scope="col" class="text-center"><i class="fa-solid fa-user-pen"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(broadcast, index) in broadcasts" :key="index">
                                <th class="table-cell" scope="row"><input :checked="isSelected(broadcast.id)"
                                        type="checkbox" class="" @change="handleSelect(broadcast.id)"></th>
                                <th class="table-cell" scope="row">#{{ (big_search.page - 1) * big_search.perPage +
                                index +
                                1 }}</th>
                                <td :class="{
                                'table-cell': true, 'text-uppercase': true,
                                'colorDraf': broadcast.status == 'draf',
                                'colorScheduled': broadcast.status == 'scheduled',
                                'colorSent': broadcast.status == 'sent',
                                'colorFailed': broadcast.status == 'failed',
                            }">
                                    {{ broadcast.status }}</td>
                                <td class="table-cell ">
                                    <div class="contentTextTable" v-html="broadcast.title"></div>
                                </td>
                                <td class="table-cell">
                                    <div class="name-avatar">
                                        <img :src="broadcast.sender_avatar ? broadcast.sender_avatar : require('@/assets/avatar.jpg')"
                                            alt="">
                                        <span class="nameMember">{{ broadcast.sender_name }}</span>
                                    </div>
                                </td>
                                <td class="table-cell text-center display-date">{{ broadcast.sent_at }}</td>
                                <td class="table-cell text-center display-date">{{ formatDate(broadcast.created_at) }}
                                </td>
                                <td class="table-cell text-center display-date">{{ formatDate(broadcast.updated_at) }}
                                </td>
                                <td class="table-cell text-center">
                                    <div class="action">
                                        <button data-toggle="modal" data-target="#viewDetailBroadcast"
                                            v-tippy="{ content: 'View Detail' }"
                                            class="viewDetailBroadcast text-success"
                                            @click="selectBroadcast(broadcast)">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button data-toggle="modal" data-target="#updateBroadcast"
                                            v-tippy="{ content: 'Update' }" class="updateBroadcast text-primary"
                                            @click="selectBroadcast(broadcast)">
                                            <i :class="{ 'fa-solid': true, 'fa-pen': true }"></i>
                                        </button>
                                        <button v-tippy="{ content: broadcast.is_delete == 0 ? 'Delete' : 'Backup' }"
                                            class="deleteBroadcast text-danger" @click="showAlert(broadcast)">
                                            <i
                                                :class="{ 'fa-solid': true, 'fa-trash': broadcast.is_delete == 0, 'fa-trash-arrow-up': broadcast.is_delete == 1 }"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="divpaginate" class="mt-2">
                    <paginate v-if="paginateVisible" :page-count="last_page" :page-range="3" :margin-pages="2"
                        :click-handler="clickCallback" :initial-page="big_search.page" :prev-text="'Prev'"
                        :next-text="'Next'" :container-class="'pagination'" :page-class="'page-item'">
                    </paginate>
                </div>
                <AddBroadcast :packageStickers="packageStickers" :dataContents="dataContents" :channel="channel"
                    :getBroadcasts="getBroadcasts"></AddBroadcast>
                <TestSendMulticast :packageStickers="packageStickers" :dataContents="dataContents" :channel="channel"
                    :getBroadcasts="getBroadcasts"></TestSendMulticast>
                <UpdateBroadcast :packageStickers="packageStickers" :dataContents="dataContents" :channel="channel"
                    :getBroadcasts="getBroadcasts" :broadcastSelected="broadcastSelected"></UpdateBroadcast>
                <DetailBroadcast :dataContents="dataContents" :channel="channel" :broadcastSelected="broadcastSelected"
                    :getStickerImageUrl="getStickerImageUrl"></DetailBroadcast>
                <DeleteManyBroadcast :isDeleteChangeMany="isDeleteChangeMany" :selectedBroadcasts="selectedBroadcasts"
                    :getStickerImageUrl="getStickerImageUrl"></DeleteManyBroadcast>
            </div>
        </div>
    </div>
</template>

<script>
import useEventBus from '@/composables/useEventBus'
import UserRequest from '@/restful/UserRequest';
const { emitEvent, onEvent } = useEventBus();
import Paginate from 'vuejs-paginate-next';
import config from '@/config';
import TableLoading from '@/components/common/TableLoading'
import _ from 'lodash';
import AddBroadcast from '@/components/user/manage-broadcast/AddBroadcast.vue'
import TestSendMulticast from '@/components/user/manage-broadcast/TestSendMulticast.vue'
import DetailBroadcast from '@/components/user/manage-broadcast/DetailBroadcast.vue'
import UpdateBroadcast from '@/components/user/manage-broadcast/UpdateBroadcast.vue'
import DeleteManyBroadcast from '@/components/user/manage-broadcast/DeleteManyBroadcast.vue'

export default {
    name: "ManageBroadcast",
    components: {
        paginate: Paginate,
        TableLoading,
        AddBroadcast,
        DeleteManyBroadcast,
        DetailBroadcast,
        UpdateBroadcast,
        TestSendMulticast,
    },

    data() {
        return {
            config: config,
            total: 0,
            last_page: 1,
            query: '',
            search: '',
            paginateVisible: true,
            big_search: {
                perPage: 5,
                page: 1,
                typesort: 'new',
                sortlatest: 'true',
                is_delete: '0',
                status: 'all',
                role: 'all',
            },
            broadcasts: [],
            broadcastSelected: {
                id: '',
                status: '',
                content_data: null,
                is_delete: null,
                creator_name: null,
                updater_name: null,
            },
            channel: {
                channel_id: null,
                channel_name: null,
                channel_secret: null,
                channel_access_token: null,
                picture_url: null,
            },
            dataContents: {
                images: null,
                texts: null,
            },
            selectedBroadcasts: [],
            isLoading: false,
            isDeleteChangeMany: 0,
            user: {
                id: null,
                email: null,
                role: null,
                line_user_id: null,
                channel_id: null,
                name: null,
                phone: null,
                avatar: null,
                address: null,
                gender: null,
                date_of_birth: null,
                is_block: null,
                is_delete: null,
                email_verified_at: null,
                created_at: null,
                updated_at: null,
                expires_in: null,
                token_type: null,
                access_token: null,
            },
            packageStickers: [
                {
                    packageId: "446",
                    stickerIds: {
                        start: 1988,
                        end: 2027
                    }
                },
                {
                    packageId: "789",
                    stickerIds: {
                        start: 10855,
                        end: 10894
                    }
                },
                {
                    packageId: "6136",
                    stickerIds: {
                        start: 10551376,
                        end: 10551399
                    }
                },
                {
                    packageId: "6325",
                    stickerIds: {
                        start: 10979904,
                        end: 10979927
                    }
                }
            ],
        }
    },

    setup() {
        document.title = "Manages Broadcast - Mutilcast | AI System"
    },

    mounted() {
        emitEvent('eventTitleHeader', 'Channel Manages Broadcast - Mutilcast');
        this.user = JSON.parse(localStorage.getItem('user'));
        const queryString = window.location.search;
        const searchParams = new URLSearchParams(queryString);
        this.search = searchParams.get('search') || '';
        this.big_search = {
            perPage: parseInt(searchParams.get('paginate')) || 5,
            page: searchParams.get('page') || 1,
            typesort: searchParams.get('typesort') || 'new',
            sortlatest: searchParams.get('sortlatest') || 'true',
            is_delete: searchParams.get('is_delete') || '0',
            role: searchParams.get('role') || 'all',
            status: searchParams.get('status') || 'all',
        }
        this.getInforChannel();
        this.getBroadcasts();
        this.getDataContents();

        onEvent('updateBroadcastSuccess', (contentUpdate) => {
            this.contents.forEach(content => {
                if (content.id == contentUpdate.id) {
                    content.name = contentUpdate.name;
                    content.email = contentUpdate.email;
                    content.line_user_id = contentUpdate.line_user_id;
                }
            });
        });
        onEvent('eventUpdateIsDeleteBroadcast', (id_broadcast) => {
            this.broadcasts.forEach(broadcast => {
                if (broadcast.id == id_broadcast) {
                    if (broadcast.is_delete == 0) broadcast.is_delete = 1;
                    else broadcast.is_delete = 0;
                }
            });
        });

        onEvent('eventRegetBroadcast', () => {
            this.getBroadcasts();
        });
    },

    methods: {
        showAlert(broadcast) {
            this.$swal({
                title: 'Are you sure you want change is delete broadcast with this ' + broadcast.title + ' title ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Close',
            }).then((result) => {
                if (result.value) {
                    this.deleteBroadcast.bind(this)(broadcast);
                } else if (result.dismiss === this.$swal.DismissReason.cancel) {
                    this.handleClose();
                }
            });
        },
        async deleteBroadcast(broadcast) {
            var dataSubmit = {
                is_delete: '',
            };
            try {
                if (broadcast.is_delete == 0) dataSubmit.is_delete = 1;
                else dataSubmit.is_delete = 0;
                const { messages } = await UserRequest.post('broadcast/delete-broadcast/' + broadcast.id, dataSubmit, true);
                emitEvent('eventSuccess', messages[0]);
                emitEvent('eventUpdateIsDeleteBroadcast', broadcast.id);
            }
            catch (error) {
                if (error.messages) emitEvent('eventError', error.messages[0]);
            }
        },
        handleClose() {

        },
        getStickerImageUrl(stickerId) {
            return `https://stickershop.line-scdn.net/stickershop/v1/sticker/${stickerId}/ANDROID/sticker.png`;
        },
        generateNumbers(start, end) {
            return Array.from({ length: end - start + 1 }, (_, index) => start + index);
        },
        selectedSticker: function (stickerId, packageId) {
            this.dataSticker.content_data.packageId = String(packageId);
            this.dataSticker.content_data.stickerId = String(stickerId);
        },
        getInforChannel: async function () {
            try {
                const { data } = await UserRequest.get('user/infor-channel');
                this.channel = data;
            }
            catch (error) {
                if (error.messages) emitEvent('eventError', error.messages[0]);
            }
        },
        getDataContents: async function () {
            var dataQuery = {
                search: ''
            };
            try {
                const { data } = await UserRequest.post('content/for-broadcast', dataQuery)
                this.dataContents = data;
            }
            catch (error) {
                if (error.messages) emitEvent('eventError', error.messages[0]);
            }
        },
        reRenderPaginate: function () {
            if (this.big_search.page > this.last_page) this.big_search.page = this.last_page;
            this.paginateVisible = false;
            this.$nextTick(() => { this.paginateVisible = true; });
        },
        getBroadcasts: async function () {
            this.selectedBroadcasts = [];
            this.isLoading = true;
            this.query = '?search=' + this.search + '&typesort=' + this.big_search.typesort + '&sortlatest=' + this.big_search.sortlatest
                + '&is_delete=' + this.big_search.is_delete + '&status=' + this.big_search.status + '&role=' + this.big_search.role + '&paginate=' + this.big_search.perPage + '&page=' + this.big_search.page;
            window.history.pushState({}, null, this.query);

            try {
                const { data } = await UserRequest.get('broadcast/all' + this.query)
                this.broadcasts = data.data
                this.total = data.total;
                this.last_page = data.last_page;
                this.isLoading = false;
            }
            catch (error) {
                if (error.messages) emitEvent('eventError', error.messages[0]);
                this.isLoading = false;
            }
            this.reRenderPaginate();
        },
        truncatedTitle(title) {
            const maxLength = 150;
            if (title.length > maxLength) return title.slice(0, maxLength) + '...';
            else return title;
        },
        formatDate: function (date) {
            return date.split('T')[0]
        },
        clickCallback: function (pageNum) {
            this.big_search.page = pageNum;
        },
        selectBroadcast: function (broadcastSelected) {
            emitEvent('selectSimpleBroadcast', broadcastSelected);
            this.broadcastSelected = broadcastSelected;
        },
        isSelected(contentId) {
            return this.selectedBroadcasts.includes(contentId);
        },
        handleSelect: function (contentId) {
            const index = this.selectedBroadcasts.indexOf(contentId);
            if (index === -1) this.selectedBroadcasts.push(contentId);
            else this.selectedBroadcasts.splice(index, 1);
        },
        selectAll: function () {
            const checkbox = this.$refs.selectAllCheckbox;
            if (checkbox.checked) this.selectedBroadcasts = this.broadcasts.map(broadcast => broadcast.id);
            else this.selectedBroadcasts = [];
        },
        changeIsDelete: async function (content) {
            this.broadcastSelected = content;
        },
        changedeleteManyBroadcast: function (is_delete) {
            emitEvent('selectManyBroadcast', this.broadcasts);
            this.isDeleteChangeMany = is_delete;
        },
    },
    watch: {
        big_search: {
            handler: function () {
                this.getBroadcasts();
            },
            deep: true
        },
        search: _.debounce(function () {
            this.getBroadcasts();
        }, 500),
    }
}
</script>

<style scoped>
.contentTextTable {
    max-width: 120px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.title-channel {
    font-size: 19px;
    color: var(--user-color);
}

tr th {
    color: var(--user-color);
}

.color-title {
    color: gray;
}

.table-data {
    min-height: 20vh;
    overflow-y: scroll;
}

table thead th,
table tbody th {
    vertical-align: middle;
    text-align: center;
}

.name-avatar {
    display: flex;
    align-items: center;
    align-content: center;
}

.name-avatar img {
    width: 60px;
    height: 60px;
    object-fit: cover;
}

.deleteBroadcast .fa-trash:hover {
    transition: all 0.5s ease;
    color: red;
}

.deleteBroadcast .fa-trash-arrow-up:hover {
    transition: all 0.5s ease;
    color: green;
}

.deleteBroadcast {
    transition: all 0.5s ease;
    font-size: 22px;
}

.updateBroadcast {
    transition: all 0.5s ease;
    font-size: 22px;
}

.updateBroadcast .fa-pen:hover {
    transition: all 0.5s ease;
    color: #3366FF;
}

.viewDetailBroadcast {
    transition: all 0.5s ease;
    font-size: 22px;
}

.viewDetailBroadcast i:hover {
    transition: all 0.5s ease;
    color: var(--user-color);
}

.name-avatar img {
    border-radius: 6px;
}

.nameMember {
    margin-left: 10px;
}

#main {
    padding: 10px 20px;
}

#page {
    margin-right: auto;
}

table {
    font-size: 12px;
}

table img {
    max-width: 100px;
    height: auto;
    object-fit: cover;
}

.table-cell {
    font-weight: bold;
    vertical-align: middle;
}

.imgInTable {
    display: flex;
    justify-content: center;
}

.imgInTable img {
    border-radius: 4px;
}

table button {
    padding: 1px 3px;
    margin-right: 2px;
}

.colorDraf {
    color: silver;
}

.colorScheduled {
    color: var(--blue-color)
}

.colorSent {
    color: var(--user-color)
}

.colorFailed {
    color: red;
}

.action {
    display: flex;
    align-items: center;
    justify-content: center;
}

.display-date {
    min-width: 90px;
}

#search-sort {
    padding-right: 12px;
}

@media screen and (min-width: 1201px) {
    td .fa-solid {
        font-size: 20px;
    }

    .contentTextTable {
        max-width: 150px;
    }
}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    .title-channel {
        font-size: 17px;
    }

    .color-title {
        font-size: 15px;
    }

    table {
        font-size: 12px;
    }

    .fa-solid {
        font-size: 15px;
    }

    .name-avatar img {
        min-width: 50px;
        min-height: 50px;
        max-width: 50px;
        max-height: 50px;
        object-fit: cover;
    }

    .table td,
    .table th {
        padding: 8px;
    }

    .form-control,
    .pagination {
        font-size: 13px !important;
    }

    #main {
        padding: 1% 1%;
        margin: 0;
    }

    table button {
        padding: 1px 2px;
    }

    .btn {
        padding: 2px 7px;
    }

    div:where(.swal2-container) div:where(.swal2-popup) {
        font-size: 8px !important;
    }
}

@media screen and (min-width: 769px) and (max-width: 992px) {
    .title-channel {
        font-size: 15px;
    }

    .color-title {
        font-size: 14px;
    }

    .name-avatar {
        min-width: 140px;
    }

    table {
        font-size: 11px;
    }

    .fa-solid {
        font-size: 14px;
    }

    .name-avatar img {
        min-width: 50px;
        min-height: 50px;
        max-width: 50px;
        max-height: 50px;
        object-fit: cover;
    }

    .table td,
    .table th {
        padding: 8px;
    }

    .form-control,
    .pagination {
        font-size: 12px !important;
    }

    #main {
        padding: 1% 1%;
        margin: 0;
    }

    .col-1,
    .col-2,
    .col-3 {
        padding-left: 0;
        padding-right: 3px;
    }

    .btn {
        padding: 1px 5px 0 5px;
    }

    table button {
        padding: 1px 2px;
    }

    table img {
        max-width: 100px;
    }

    .contentTextTable {
        max-width: 110px;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {

    .title-channel,
    .color-title {
        font-size: 14px;
    }

    .name-avatar {
        min-width: 120px;
    }

    table {
        font-size: 11px;
    }

    .fa-solid {
        font-size: 13px;
    }

    .name-avatar img {
        min-width: 40px;
        min-height: 40px;
        max-width: 40px;
        max-height: 40px;
        object-fit: cover;
    }

    .table td,
    .table th {
        padding: 8px;
    }

    .form-control,
    .pagination {
        font-size: 12px !important;
    }

    .form-control {
        padding: 1px 1px;
    }

    #main {
        padding: 1% 1%;
        margin: 0;
    }

    .btn {
        padding: 1px 6px 0 6px;
    }

    table button {
        padding: 1px;
    }

    table img {
        max-width: 130px;
    }

    .gap-2 {
        gap: 0.3rem;
    }

    .mt-3 {
        margin-top: 5px !important;
    }

    .contentTextTable {
        min-width: 100px;
        max-width: 150px;
    }

    .action {
        min-width: 56px;
    }
}

@media screen and (min-width: 425px) and (max-width: 576px) {

    .title-channel,
    .color-title {
        font-size: 12px;
    }

    .nameMember {
        margin-left: 8px;
    }

    .name-avatar {
        min-width: 100px;
    }

    table {
        font-size: 10px;
    }

    .fa-solid {
        font-size: 10px;
    }

    .name-avatar img {
        min-width: 40px;
        min-height: 40px;
        max-width: 40px;
        max-height: 40px;
        object-fit: cover;
    }

    .table td,
    .table th {
        padding: 6px;
    }

    .form-control,
    .pagination {
        font-size: 10px !important;
    }

    .input-group>.form-control {
        max-width: 61px;
    }

    .form-control {
        padding: 1px 1px;
        height: 25px;
    }

    #main {
        padding: 1% 1%;
        margin: 0;
    }

    .btn {
        padding: 0px 6px;
    }

    .input-group-text {
        padding: 0 1px;
    }

    .input-group-prepend {
        font-size: 11px;
    }

    .mr-3 {
        margin-left: -2% !important;
        margin-right: 0px !important
    }

    table button {
        padding: 1px;
    }

    .mt-3 {
        margin-top: 0 !important;
    }

    table img {
        max-width: 80px;
    }

    .gap-2 {
        gap: 0.2rem;
    }

    .mt-3 {
        margin-top: 5px !important;
    }

    .contentTextTable {
        min-width: 100px;
        max-width: 150px;
    }

    .display-date {
        min-width: 80px;
    }

    .action {
        min-width: 50px;
    }
}

@media screen and (min-width: 375px) and (max-width: 424px) {

    .title-channel,
    .color-title {
        font-size: 11px;
    }


    .nameMember {
        margin-left: 8px;
    }

    .name-avatar {
        min-width: 100px;
    }

    table {
        font-size: 9px;
    }

    .fa-solid {
        font-size: 10px;
    }

    .name-avatar img {
        min-width: 40px;
        min-height: 40px;
        max-width: 40px;
        max-height: 40px;
        object-fit: cover;
    }

    .table td,
    .table th {
        padding: 6px;
    }

    .form-control,
    .pagination {
        font-size: 9px !important;
    }

    .form-control {
        padding: 0.5px 0;
        height: 25px;
    }

    #main {
        padding: 1% 1%;
        margin: 0;
    }

    .btn {
        padding: 0px 6px;
    }

    .input-group-text {
        padding: 0 2px;
    }

    .input-group-prepend {
        font-size: 10px;

    }

    #main .ml-2 {
        margin-left: 3px !important;
    }

    table button {
        padding: 0.7px;
    }

    .gap-2 {
        gap: 0.2rem;
    }

    .mt-3 {
        margin-top: 0 !important;
    }

    table img {
        max-width: 70px;
    }

    .contentTextTable {
        min-width: 80px;
        max-width: 130px;
    }

    .display-date {
        min-width: 75px;
    }

    .action {
        min-width: 50px;
    }
}
</style>

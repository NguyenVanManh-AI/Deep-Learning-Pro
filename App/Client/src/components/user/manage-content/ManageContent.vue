<template>
    <div id="main">
        <div>
            <h3 class="title-channel"><i class="fa-solid fa-envelope-open-text"></i> Manage Content </h3>
        </div>
        <div class="ml-2 mt-2">
            <div class="mt-3">
                <div class="mb-2">
                    <div class="color-title"><i class="fa-solid fa-bars-staggered"></i> Manage Content of Channel</div>
                </div>
                <div class="row m-0 pb-2 d-flex justify-content-end gap-2" id="search-sort">
                    <div class="pl-0" id="page">
                        <select content="Pagination" v-tippy class="form-control " v-model="big_search.perPage">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                    <div class="pl-0 mr-3">
                        <select content="Sort by" v-tippy class="form-control " v-model="big_search.typesort">
                            <option value="new">New</option>
                            <option value="name">Name</option>
                            <option value="content_type">Content type</option>
                        </select>
                    </div>
                    <div class="pl-0 mr-3">
                        <select content="In direction" v-tippy class="form-control " v-model="big_search.sortlatest">
                            <option value="false">Ascending</option>
                            <option value="true">Decrease</option>
                        </select>
                    </div>
                    <div class="pl-0 mr-3">
                        <select content="Filter by delete" v-tippy class="form-control " v-model="big_search.is_delete">
                            <option value="all">All Content</option>
                            <option value="1">Deleted Content</option>
                            <option value="0">Normal Content</option>
                        </select>
                    </div>
                    <div class="pl-0 mr-3">
                        <select content="Filter by content type" v-tippy class="form-control "
                            v-model="big_search.content_type">
                            <option value="all">All Content</option>
                            <option value="text">Text</option>
                            <option value="image">Image</option>
                        </select>
                    </div>
                    <div class="pl-0 mr-3" v-if="user.role == 'manager'">
                        <select content="Role" v-tippy class="form-control " v-model="big_search.role">
                            <option value="all">All</option>
                            <option value="manager">Manager</option>
                        </select>
                    </div>
                    <div class="pl-0 ">
                        <div content="Search information content" v-tippy class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>
                            </div>
                            <input v-model="search" type="text" class="form-control " id="inlineFormInputGroup"
                                placeholder="Search...">
                        </div>
                    </div>
                    <div class="mr-3">
                        <div class="input-group ">
                            <button content="Add Content" v-tippy data-toggle="modal" data-target="#modal-add-content"
                                type="button" class="btn btn-success form-control"><i
                                    class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="pr-0 mr-3" v-if="selectedContents.length > 0">
                        <div class="input-group">
                            <button @click="changeDeleteManyContent(1)" content="Delete Many Content" v-tippy
                                data-toggle="modal" data-target="#modal-delete-many-content" type="button"
                                class="btn btn-outline-danger mr-1 form-control"><i
                                    class="fa-solid fa-trash"></i></button>
                            <button @click="changeDeleteManyContent(0)" content="Backup Many Content" v-tippy
                                data-toggle="modal" data-target="#modal-delete-many-content" type="button"
                                class="btn btn-outline-success form-control"><i
                                    class="fa-solid fa-trash-arrow-up"></i></button>
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
                                <th scope="col"><i class="fa-solid fa-shapes"></i> Content Type</th>
                                <th scope="col"><i class="fa-solid fa-database"></i> Content Data</th>
                                <th scope="col"><i class="fa-solid fa-user-pen"></i> Creator</th>
                                <th scope="col"><i class="fa-solid fa-user-check"></i> Updater</th>
                                <th scope="col" class="text-center"><i class="fa-solid fa-calendar-day"></i> Created at
                                </th>
                                <th scope="col" class="text-center"><i class="fa-solid fa-calendar-check"></i> Updated
                                    at
                                </th>
                                <th scope="col" class="text-center"><i class="fa-solid fa-user-pen"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(content, index) in contents" :key="index">
                                <th class="table-cell" scope="row"><input :checked="isSelected(content.id)"
                                        type="checkbox" class="" @change="handleSelect(content.id)"></th>
                                <th class="table-cell" scope="row">#{{ (big_search.page - 1) * big_search.perPage +
                                index +
                                1 }}
                                </th>
                                <td :class="{
                                'table-cell': true, 'text-uppercase': true,
                                'colorText': content.content_type == 'text',
                                'colorImage': content.content_type == 'image'
                            }">
                                    {{ content.content_type }}</td>
                                <td class="table-cell">
                                    <div class="contentTextTable " v-if="content.content_type == 'text'"
                                        v-html="content.content_data.text">
                                    </div>
                                    <div class="imgInTable" v-if="content.content_type == 'image'">
                                        <img :src="content.content_data.originalContentUrl" alt="Image" />
                                    </div>
                                </td>
                                <td class="table-cell">
                                    <div class="name-avatar">
                                        <img :src="content.creator_avatar ? content.creator_avatar : require('@/assets/avatar.jpg')"
                                            alt="">
                                        <span class="nameMember">{{ content.creator_name }}</span>
                                    </div>
                                </td>
                                <td class="table-cell">
                                    <div class="name-avatar">
                                        <img :src="content.updater_avatar ? content.updater_avatar : require('@/assets/avatar.jpg')"
                                            alt="">
                                        <span class="nameMember">{{ content.updater_name }}</span>
                                    </div>
                                </td>
                                <td class="table-cell text-center">{{ formatDate(content.created_at) }}</td>
                                <td class="table-cell text-center">{{ formatDate(content.updated_at) }}</td>
                                <td class="table-cell text-center">
                                    <div class="action">
                                        <button data-toggle="modal" data-target="#modal-view-detail-content"
                                            v-tippy="{ content: 'View Detail' }" class="view-detail-content text-success"
                                            @click="selectContent(content,'detail')">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button data-toggle="modal" data-target="#modal-update-content"
                                            v-tippy="{ content: 'Update' }" class="updateContent text-primary"
                                            @click="selectContent(content,'update')">
                                            <i :class="{ 'fa-solid': true, 'fa-pen': true }"></i>
                                        </button>
                                        <button data-toggle="modal" data-target="#modal-delete-content"
                                            v-tippy="{ content: content.is_delete == 0 ? 'Delete' : 'Backup' }"
                                            class="deleteContent text-danger" @click="selectContent(content,'delete')">
                                            <i
                                                :class="{ 'fa-solid': true, 'fa-trash': content.is_delete == 0, 'fa-trash-arrow-up': content.is_delete == 1 }"></i>
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
                <AddOrUpdateContent :getContents="getContents"></AddOrUpdateContent>
                <AddOrUpdateContent :getContents="getContents" :contentSelected="contentSelected"></AddOrUpdateContent>
                <DeleteContent :contentSelected="contentSelected"></DeleteContent>
                <DetailContent :contentSelected="contentSelected"></DetailContent>
                <DeleteManyContent :isDeleteChangeMany="isDeleteChangeMany" :selectedContents="selectedContents"></DeleteManyContent>
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
import AddOrUpdateContent from '@/components/user/manage-content/AddOrUpdateContent.vue'
import DeleteContent from '@/components/user/manage-content/DeleteContent.vue'
import DetailContent from '@/components/user/manage-content/DetailContent.vue'
import DeleteManyContent from '@/components/user/manage-content/DeleteManyContent.vue'

export default {
    name: "ManageContent",
    components: {
        paginate: Paginate,
        TableLoading,
        DeleteContent,
        DeleteManyContent,
        DetailContent,
        AddOrUpdateContent,
    },

    data() {
        return {
            config: config,
            total: 0,
            last_page: 1,
            paginateVisible: true,
            search: '',
            big_search: {
                perPage: 5,
                page: 1,
                typesort: 'new',
                sortlatest: 'true',
                is_delete: '0',
                content_type: 'all',
                role: 'all',
            },
            query: '',
            contents: [],
            contentSelected: {
                id: '',
                content_type: '',
                content_data: null,
                is_delete: null,
                creator_name: null,
                updater_name: null,
            },
            selectedContents: [],
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
        }
    },

    setup() {
        document.title = "Manages Content | AI System"
    },

    mounted() {
        emitEvent('eventTitleHeader', 'Channel Manages Content');
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
            content_type: searchParams.get('content_type') || 'all',
        }
        this.getContents();
        onEvent('updateContentSuccess', (contentUpdate) => {
            this.contents.forEach(content => {
                if (content.id == contentUpdate.id) {
                    content.name = contentUpdate.name;
                    content.email = contentUpdate.email;
                    content.line_user_id = contentUpdate.line_user_id;
                }
            });
        });
        onEvent('eventUpdateIsDeleteContent', (id_content) => {
            this.contents.forEach(content => {
                if (content.id == id_content) {
                    if (content.is_delete == 0) content.is_delete = 1;
                    else content.is_delete = 0;
                }
            });
        });

        onEvent('eventRegetcontents', () => {
            this.getContents();
        });
    },

    methods: {
        reRenderPaginate: function () {
            if (this.big_search.page > this.last_page) this.big_search.page = this.last_page;
            this.paginateVisible = false;
            this.$nextTick(() => { this.paginateVisible = true; });
        },
        getContents: async function () {
            this.selectedContents = [];
            this.isLoading = true;
            this.query = '?search=' + this.search + '&typesort=' + this.big_search.typesort + '&sortlatest=' + this.big_search.sortlatest
                + '&is_delete=' + this.big_search.is_delete + '&content_type=' + this.big_search.content_type + '&role=' + this.big_search.role + '&paginate=' + this.big_search.perPage + '&page=' + this.big_search.page;
            window.history.pushState({}, null, this.query);

            try {
                const { data } = await UserRequest.get('content/all' + this.query)
                this.contents = data.data
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
            const formattedDate = new Date(date);

            const day = formattedDate.getDate();
            const month = formattedDate.getMonth() + 1;
            const year = formattedDate.getFullYear();

            const formattedDateString = `${day}/${month}/${year}`;

            return formattedDateString;
        },

        clickCallback: function (pageNum) {
            this.big_search.page = pageNum;
        },

        selectContent: function (contentSelected, status) {
            if(status == 'detail') {
                emitEvent('selectSimpleContentDetail', contentSelected);
            }
            else if(status == 'delete') {
                emitEvent('selectSimpleContentDelete', contentSelected);
            }
            else{
                emitEvent('selectSimpleContent', contentSelected);
            }
            this.contentSelected = contentSelected;
        },

        isSelected(contentId) {
            return this.selectedContents.includes(contentId);
        },

        handleSelect: function (contentId) {
            const index = this.selectedContents.indexOf(contentId);
            if (index === -1) this.selectedContents.push(contentId);
            else this.selectedContents.splice(index, 1);
        },

        selectAll: function () {
            const checkbox = this.$refs.selectAllCheckbox;
            if (checkbox.checked) this.selectedContents = this.contents.map(content => content.id);
            else this.selectedContents = [];
        },

        changeIsDelete: async function (content) {
            this.contentSelected = content;
        },

        changeDeleteManyContent: function (is_delete) {
            emitEvent('selectManyContent', this.contents);
            this.isDeleteChangeMany = is_delete;
        }

    },
    watch: {
        big_search: {
            handler: function () {
                this.getContents();
            },
            deep: true
        },
        search: _.debounce(function () {
            this.getContents();
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

.deleteContent .fa-trash:hover {
    transition: all 0.5s ease;
    color: red;
}

.deleteContent .fa-trash-arrow-up:hover {
    transition: all 0.5s ease;
    color: green;
}

.deleteContent {
    transition: all 0.5s ease;
    font-size: 22px;
}

.updateContent {
    transition: all 0.5s ease;
    font-size: 22px;
}

.updateContent .fa-pen:hover {
    transition: all 0.5s ease;
    color: #3366FF;
}

.view-detail-content {
    transition: all 0.5s ease;
    font-size: 22px;
}

.view-detail-content i:hover {
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
    max-width: 150px;
    height: auto;
    object-fit: cover;
}

.table-cell {
    font-weight: bold;
    vertical-align: middle;
}

table thead th,
table tbody th {
    vertical-align: middle;
    text-align: center;
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

.colorImage {
    color: var(--blue-color);
}

.colorText {
    color: var(--brown-color)
}

.action {
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-control {
    height: calc(1.5em + .5rem + 2px);
    padding: .25rem .5rem;
    font-size: .875rem;
    border-radius: 0.2rem;
    line-height: 1.5;
}

.row-multi-btn {
    display: flex;
}

#search-sort {
    line-height: 1.5;
}

@media screen and (min-width: 1201px) {
    table {
        max-width: 100%;
        vertical-align: middle;
    }

    td .fa-solid {
        font-size: 20px;
    }

    .contentTextTable{
        max-width: 150px;
    }
}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    table {
        max-width: 100%;
        vertical-align: middle;
    }

    .name-avatar {
        min-width: 120px;
    }

    table {
        font-size: 11px;
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
        font-size: 12px !important;
    }

    .input-group-text{
        padding: 1px 9px;
    }

    #main {
        padding: 1% 1%;
        margin: 0;
    }

    .col-1,
    .col-2,
    .col-3 {
        padding-right: 8px;
    }

    table button {
        padding: 1px 2px;
    }

    table img {
        max-width: 110px;
    }

    .contentTextTable{
        max-width: 130px;
    }
}

@media screen and (min-width: 769px) and (max-width: 992px) {
    .title-channel {
        font-size: 15px;
    }

    .color-title {
        font-size: 14px;
    }

    table {
        max-width: 100%;
        vertical-align: middle;
    }

    .name-avatar {
        min-width: 140px;
    }

    table {
        font-size: 11px;
    }

    .fa-solid {
        font-size: 16px;
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

    .input-group-text {
        padding: 0 6px;
    }

    #main {
        padding: 1% 1%;
        margin: 0;
    }

    #page {
        min-width: 65px;
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

    .contentTextTable{
        max-width: 110px;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {

    .title-channel,
    .color-title {
        font-size: 13px;
    }

    table {
        max-width: 100%;
        vertical-align: middle;
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

    #page {
        min-width: 45px;
    }

    .form-control {
        padding: 1px 1px;
    }

    #main {
        padding: 1% 1%;
        margin: 0;
    }

    .col-1,
    .col-2,
    .col-3 {
        padding-right: 5px;
    }

    .btn {
        padding: 1px 4px 0 4px;
    }

    .input-group-text {
        padding: 0 4px;
    }

    .input-group-prepend {
        font-size: 12px;

    }

    .mr-3 {
        margin-left: -1% !important;
        margin-right: 0px !important
    }

    table button {
        padding: 1px;
    }

    table img {
        max-width: 100px;
    }

    .contentTextTable{
        max-width: 110px;
    }
}

@media screen and (min-width: 425px) and (max-width: 576px) {

    .title-channel,
    .color-title {
        font-size: 12px;
    }

    table {
        max-width: 100%;
        vertical-align: middle;
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
        padding: 5px;
    }

    .form-control,
    .pagination {
        font-size: 10px !important;
    }

    .form-control {
        padding: 1px 1px;
        height: 25px;
    }

    #page {
        min-width: 45px;
    }

    #main {
        padding: 1% 1%;
        margin: 0;
    }

    .col-1,
    .col-2,
    .col-3 {
        padding-right: 5px;
    }

    .btn {
        padding: 0px 4px;
    }

    .input-group-text {
        padding: 0 3px;
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

    .mt-3{
        margin-top: 0 !important;
    }

    table img {
        max-width: 80px;
    }

    .contentTextTable{
        max-width: 90px;
    }
}

@media screen and (min-width: 375px) and (max-width: 424px) {

    .title-channel,
    .color-title {
        font-size: 11px;
    }

    table {
        max-width: 100%;
        vertical-align: middle;
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
        padding: 4px;
    }

    .form-control,
    .pagination {
        font-size: 9px !important;
    }

    .form-control {
        padding: 0.5px 0;
        height: 25px;
    }

    #page {
        min-width: 40px;
    }

    #main {
        padding: 1% 1%;
        margin: 0;
    }

    .col-1,
    .col-2,
    .col-3 {
        padding-right: 0;
    }

    .btn {
        padding: 0px 4px;
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

    .mr-3 {
        margin-left: 0px !important;
        margin-right: 0px !important;
    }

    table button {
        padding: 0.7px;
    }

    .mt-3{
        margin-top: 0 !important;
    }

    table img {
        max-width: 70px;
    }

    .contentTextTable{
        max-width: 80px;
    }
}
</style>

<template>
    <div id="main">
        <div>
            <h3 class="titleChannel"><i class="fa-brands fa-line"></i> Channel management #{{ user.channel_id }}</h3>
        </div>
        <div class="ml-2 mt-2">
            <div>
                <div class="col-12 mt-2">
                    <div class="row">
                        <div class="colorTitle"><i class="fa-solid fa-key"></i> Information Channel</div>
                    </div>
                    <div class="row mb-3">
                        <UpdateInformationChannel></UpdateInformationChannel>
                    </div>
                </div>
            </div>
            <hr>
            <div class="mt-3">
                <div class="mb-2">
                    <div class="colorTitle"><i class="fa-solid fa-users-line"></i> Manage Account Members</div>
                </div>
                <div class="row m-0 pb-2 d-flex justify-content-end" id="search-sort">
                    <div class="col-1 pl-0" id="page">
                        <select content="Pagination" v-tippy class="form-control" v-model="big_search.perPage">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                    <div class="col-2 pl-0">
                        <select content="Sort by" v-tippy class="form-control " v-model="big_search.typesort">
                            <option value="new">New</option>
                            <option value="name">Name</option>
                            <option value="gender">Gender</option>
                            <option value="address">Address</option>
                            <option value="phone">Phone</option>
                        </select>
                    </div>
                    <div class="col-2 pl-0">
                        <select content="In direction" v-tippy class="form-control " v-model="big_search.sortlatest">
                            <option value="false">Ascending</option>
                            <option value="true">Decrease</option>
                        </select>
                    </div>
                    <div class="col-2 pl-0">
                        <select content="Filter by delete" v-tippy class="form-control " v-model="big_search.is_delete">
                            <option value="all">All Members</option>
                            <option value="1">Deleted Members</option>
                            <option value="0">Normal Members</option>
                        </select>
                    </div>
                    <div class="col-3 pl-0">
                        <div content="Search information member" v-tippy class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></div>
                            </div>
                            <div @click="startSpeechRecognition" class="div_microphone input-group-prepend">
                                <div class="input-group-text"><i class="fa-solid fa-microphone"></i></div>
                            </div>
                            <input v-model="search" type="text" class="form-control " id="inline-form-input-group"
                                placeholder="Search...">
                        </div>
                    </div>
                    <div class="pr-1">
                        <div class="input-group ">
                            <button content="Add Account Member" v-tippy data-toggle="modal" data-target="#addMember"
                                type="button" class="btn btn-success"><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="pr-0" v-if="selectedMembers.length > 0">
                        <div class="input-group">
                            <button @click="changeDeleteManyMember(1)" content="Delete Many Member" v-tippy
                                data-toggle="modal" data-target="#deleteManyMember" type="button"
                                class="btn btn-outline-danger mr-1"><i class="fa-solid fa-trash"></i></button>
                            <button @click="changeDeleteManyMember(0)" content="Backup Many Member" v-tippy
                                data-toggle="modal" data-target="#deleteManyMember" type="button"
                                class="btn btn-outline-success"><i class="fa-solid fa-trash-arrow-up"></i></button>
                        </div>
                    </div>
                </div>
                <div v-if="isLoading">
                    <TableLoading :cols="8" :rows="9"></TableLoading>
                </div>
                <div v-if="!isLoading" class="tableData">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col"><input ref="selectAllCheckbox" @change="selectAll()" type="checkbox"
                                        class=""></th>
                                <th scope="col">#</th>
                                <th scope="col"><i class="fa-solid fa-signature"></i> Full Name</th>
                                <th scope="col"><i class="fa-solid fa-envelope"></i> Email</th>
                                <th scope="col"><i class="fa-brands fa-line"></i> LINE User ID</th>
                                <th scope="col"><i class="fa-solid fa-phone"></i> Phone</th>
                                <th scope="col"><i class="fa-solid fa-location-dot"></i> Address</th>
                                <th scope="col"><i class="fa-solid fa-venus-mars"></i> Gender</th>
                                <th scope="col">Created at</th>
                                <th scope="col">Updated at</th>
                                <th scope="col"><i class="fa-solid fa-user-pen"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(member, index) in members" :key="index">
                                <th class="table-cell" scope="row"><input :checked="isSelected(member.id)" type="checkbox"
                                        class="" @change="handleSelect(member.id)"></th>
                                <th class="table-cell" scope="row">#{{ (big_search.page - 1) * big_search.perPage + index +
                                    1 }}</th>
                                <td class="table-cell">
                                    <div class="nameAvatar">
                                        <img :src="member.avatar ? member.avatar : require('@/assets/avatar.jpg')" alt="">
                                        <span class="nameMember">{{ member.name }}</span>
                                    </div>
                                </td>
                                <td class="table-cell displaytext break">{{ truncatedTitle(member.email) }}</td>
                                <td class="table-cell displaytext break">{{ truncatedTitle(member.line_user_id) }}</td>
                                <td class="table-cell text-center">{{ member.phone ? member.phone : 'N/A' }}</td>
                                <td class="table-cell displaytext">{{ member.address ? truncatedTitle(member.address) :
                                    'N/A' }}</td>
                                <td class="table-cell text-center">{{ formatGender(member.gender) }}</td>
                                <td class="table-cell text-center">{{ formatDate(member.created_at) }}</td>
                                <td class="table-cell text-center">{{ formatDate(member.updated_at) }}</td>
                                <td class="table-cell text-center">
                                    <div class="action">
                                        <button data-toggle="modal" data-target="#updateMember"
                                            v-tippy="{ content: 'Update' }" class="updateMember text-primary"
                                            @click="changeIsDelete(member)">
                                            <i :class="{ 'fa-solid': true, 'fa-pen': true }"></i>
                                        </button>
                                        <button data-toggle="modal" data-target="#deleteMember"
                                            v-tippy="{ content: member.is_delete == 0 ? 'Delete' : 'Backup' }"
                                            class="deleteMember text-danger" @click="changeIsDelete(member)">
                                            <i
                                                :class="{ 'fa-solid': true, 'fa-trash': member.is_delete == 0, 'fa-trash-arrow-up': member.is_delete == 1 }"></i>
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
                <AddOrUpdateMember></AddOrUpdateMember>
                <DeleteMember :memberSelected="memberSelected"></DeleteMember>
                <DeleteManyMember :isDeleteChangeMany="isDeleteChangeMany" :selectedMembers="selectedMembers"
                    :members="members">
                </DeleteManyMember>
                <AddOrUpdateMember :memberSelected="memberSelected"></AddOrUpdateMember>
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
import AddOrUpdateMember from '@/components/user/member-account/AddOrUpdateMember.vue'
import DeleteMember from '@/components/user/member-account/DeleteMember.vue'
import DeleteManyMember from '@/components/user/member-account/DeleteManyMember.vue'
import UpdateInformationChannel from '@/components/user/member-account/UpdateInformationChannel.vue'

export default {
    name: "ManageMember",
    components: {
        paginate: Paginate,
        TableLoading,
        AddOrUpdateMember,
        DeleteMember,
        DeleteManyMember,
        UpdateInformationChannel
    },
    setup() {
        document.title = "Member Account | AI System"
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
            },
            query: '',
            members: [],
            memberSelected: {
                id: '',
                name: '',
                email: '',
                line_user_id: '',
                is_delete: null
            },
            selectedMembers: [],
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
    mounted() {
        this.checkManager();
        emitEvent('eventTitleHeader', 'Channel manages - Member Account');
        const queryString = window.location.search;
        const searchParams = new URLSearchParams(queryString);
        this.search = searchParams.get('search') || '';
        this.big_search = {
            perPage: parseInt(searchParams.get('paginate')) || 5,
            page: searchParams.get('page') || 1,
            typesort: searchParams.get('typesort') || 'new',
            sortlatest: searchParams.get('sortlatest') || 'true',
            is_delete: searchParams.get('is_delete') || '0',
        }
        this.getMembers();
        onEvent('updateMemberSuccess', (memberUpdate) => {
            this.members.forEach(member => {
                if (member.id == memberUpdate.id) {
                    member.name = memberUpdate.name;
                    member.email = memberUpdate.email;
                    member.line_user_id = memberUpdate.line_user_id;
                }
            });
        });
        onEvent('eventUpdateIsDeleteMember', (id_member) => {
            this.members.forEach(member => {
                if (member.id == id_member) {
                    if (member.is_delete == 0) member.is_delete = 1;
                    else member.is_delete = 0;
                }
            });
        });
        onEvent('eventRegetMembers', () => {
            this.getMembers();
        });
    },

    methods: {
        checkManager: function () {
            var user = JSON.parse(localStorage.getItem('user'));
            if (user.role != 'manager') this.$router.push({ name: 'AccountSetting' });
            else this.user = user;
        },
        reRenderPaginate: function () {
            if (this.big_search.page > this.last_page) this.big_search.page = this.last_page;
            this.paginateVisible = false;
            this.$nextTick(() => { this.paginateVisible = true; });
        },
        getMembers: async function () {
            this.selectedMembers = [];
            this.isLoading = true;
            this.query = '?search=' + this.search + '&typesort=' + this.big_search.typesort + '&sortlatest=' + this.big_search.sortlatest
                + '&is_delete=' + this.big_search.is_delete + '&role=user' + '&paginate=' + this.big_search.perPage + '&page=' + this.big_search.page;
            window.history.pushState({}, null, this.query);

            try {
                const { data } = await UserRequest.get('user/members' + this.query)
                this.members = data.data
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
        startSpeechRecognition() {
            const recognition = new window.webkitSpeechRecognition() || new window.SpeechRecognition();
            recognition.lang = "vi-VN";
            recognition.onresult = event => {
                this.search = event.results[0][0].transcript;
            };
            recognition.onerror = event => {
                console.error("Speech recognition error:", event.error);
            };
            recognition.onend = () => {
                console.log("Speech recognition ended.");
            };
            recognition.start();
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

        formatGender: function (gender) {
            switch (gender) {
                case 0:
                    return 'Male';
                case 1:
                    return 'Female';
                default:
                    return 'Other';
            }
        },

        clickCallback: function (pageNum) {
            this.big_search.page = pageNum;
        },

        selectMember: function (memberSelected) {
            this.memberSelected = memberSelected;
        },

        isSelected(memberId) {
            return this.selectedMembers.includes(memberId);
        },

        handleSelect: function (memberId) {
            const index = this.selectedMembers.indexOf(memberId);
            if (index === -1) this.selectedMembers.push(memberId);
            else this.selectedMembers.splice(index, 1);
        },

        selectAll: function () {
            const checkbox = this.$refs.selectAllCheckbox;
            if (checkbox.checked) this.selectedMembers = this.members.map(member => member.id);
            else this.selectedMembers = [];
        },

        changeIsDelete: async function (member) {
            this.memberSelected = member;
        },

        changeDeleteManyMember: function (is_delete) {
            this.isDeleteChangeMany = is_delete;
        }

    },
    watch: {
        big_search: {
            handler: function () {
                this.getMembers();
            },
            deep: true
        },
        search: _.debounce(function () {
            this.getMembers();
        }, 500),
    }
}
</script>

<style scoped>
.div_microphone {
    cursor: pointer;
}

.titleChannel {
    font-size: 19px;
    color: var(--user-color);
}

tr th {
    color: var(--user-color);
}

.colorTitle {
    color: gray;
}

.tableData {
    min-height: 20vh;
    overflow-y: scroll;
}

.nameAvatar {
    display: flex;
    align-items: center;
    align-content: center;
}

.deleteMember .fa-trash:hover {
    transition: all 0.5s ease;
    color: red;
}

.deleteMember .fa-trash-arrow-up:hover {
    transition: all 0.5s ease;
    color: green;
}

.deleteMember {
    transition: all 0.5s ease;
    font-size: 20px;
}

.updateMember {
    transition: all 0.5s ease;
    font-size: 20px;
}

.updateMember .fa-pen:hover {
    transition: all 0.5s ease;
    color: #3366FF;
}

.nameAvatar img {
    border-radius: 6px;
}

.nameMember {
    margin-left: 10px;
}

#main {
    padding: 10px 20px;
    min-width: 375px !important;
}

#page {
    margin-right: auto;
    min-width: 78px;
}

table {
    font-size: 12px;
}

table img {
    width: 60px;
    height: 60px;
    object-fit: cover;
}

.table-cell {
    font-weight: bold;
    vertical-align: middle;
}

table button {
    padding: 1px 3px;
    margin-right: 2px;
}

table thead th {
    vertical-align: middle;
    text-align: center;
}

.action {
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-control{
    height: calc(1.5em + .5rem + 2px);
    padding: .25rem .5rem;
    font-size: .875rem;
    border-radius: 0.2rem;
    line-height: 1.5;
}

@media screen and (min-width: 1201px) {
    table {
        max-width: 100%;
        vertical-align: middle;
    }

    .nameAvatar {
        min-width: 150px;
    }

    .displaytext {
        min-width: 150px;
        overflow: hidden;
        -webkit-line-clamp: 3 !important;
        -webkit-box-orient: vertical;
    }

    table img {
        min-width: 60px;
        min-height: 60px;
        max-width: 60px;
        max-height: 60px;
        object-fit: cover;
    }

    td .fa-solid {
        font-size: 20px;
    }

}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    table {
        max-width: 100%;
        vertical-align: middle;
    }

    .nameAvatar {
        min-width: 120px;
    }

    .displaytext {
        min-width: 100px;
        overflow: hidden;
        -webkit-line-clamp: 3 !important;
        -webkit-box-orient: vertical;

    }

    .break {
        word-break: break-all;
    }

    table {
        font-size: 11px;
    }

    .fa-solid {
        font-size: 15px;
    }

    table img {
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
}

@media screen and (min-width: 769px) and (max-width: 992px) {
    .titleChannel {
        font-size: 15px;
    }

    .colorTitle {
        font-size: 14px;
    }

    table {
        max-width: 100%;
        vertical-align: middle;
    }

    .nameAvatar {
        min-width: 140px;
    }

    .displaytext {
        min-width: 110px;
        overflow: hidden;
        -webkit-line-clamp: 3 !important;
        -webkit-box-orient: vertical;

    }

    .break {
        word-break: break-all;
    }

    table {
        font-size: 11px;
    }

    .fa-solid {
        font-size: 16px;
    }

    table img {
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
        padding-right: 10px;
    }

    .btn {
        padding: 0px 4px;
        margin-top: 3px;
    }

    .input-group-text {
        padding: 0 8px;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {

    .titleChannel,
    .colorTitle {
        font-size: 13px;
    }

    table {
        max-width: 100%;
        vertical-align: middle;
    }

    .nameAvatar {
        min-width: 100px;
    }

    .displaytext {
        min-width: 90px;
        overflow: hidden;
        -webkit-line-clamp: 3 !important;
        -webkit-box-orient: vertical;

    }

    .break {
        word-break: break-all;
    }

    table {
        font-size: 11px;
    }

    .fa-solid {
        font-size: 13px;
    }

    table img {
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
        padding: 0px 4px;
        margin-top: 3px;
    }

    .input-group-text {
        padding: 0 4px;
    }

    .input-group-prepend {
        font-size: 12px;

    }
}

@media screen and (min-width: 425px) and (max-width: 576px) {

    .titleChannel,
    .colorTitle {
        font-size: 12px;
    }

    table {
        max-width: 100%;
        vertical-align: middle;
    }

    .nameMember {
        margin-left: 8px;
    }

    .nameAvatar {
        min-width: 100px;
    }

    .displaytext {
        min-width: 70px;
        overflow: hidden;
        -webkit-line-clamp: 3 !important;
        -webkit-box-orient: vertical;

    }

    .break {
        word-break: break-all;
    }

    table {
        font-size: 10px;
    }

    .fa-solid {
        font-size: 10px;
    }

    table img {
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
        padding: 0 0.5px;
    }

    .input-group-prepend {
        font-size: 11px;

    }
}

@media screen and (min-width: 375px) and (max-width: 424px) {
    .titleChannel,
    .colorTitle {
        font-size: 11px;
    }

    table {
        max-width: 100%;
        vertical-align: middle;
    }

    .nameMember {
        margin-left: 8px;
    }

    .nameAvatar {
        min-width: 80px;
    }

    .displaytext {
        min-width: 70px;
        overflow: hidden;
        -webkit-line-clamp: 3 !important;
        -webkit-box-orient: vertical;

    }

    .break {
        word-break: break-all;
    }

    table {
        font-size: 9px;
    }

    .fa-solid {
        font-size: 10px;
    }

    table img {
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
        padding: 0 0.5px;
    }

    .input-group-prepend {
        font-size: 10px;

    }

    #main .ml-2{
        margin-left: 3px !important;
    }
}
</style>

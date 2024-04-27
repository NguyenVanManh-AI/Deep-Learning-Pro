<template>
    <div>
        <div class="modal fade" id="deleteManyMember" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-triangle-exclamation"></i>
                            Warning</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <p>Warning: These people will be moved to <strong>{{ isDeleteChangeMany == 1 ? 'Deleted' :
                                'Normal' }}</strong> status in the system !</p>
                            <div v-for="(member, index) in members" :key="index">
                                <li class="mb-2" v-if="selectedMembers.includes(member.id)">
                                    <p>{{ index + 1 }}. Name : <strong>{{ member.name }}</strong></p>
                                    <div class="pl-8">
                                        <p>Email : <strong>{{ member.email }}</strong></p>
                                        <p>LINE User ID : <strong>{{ member.line_user_id }}</strong></p>
                                    </div>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" ref="closeButton"
                            id="close">Close</button>
                        <button type="button"
                            :class="{ 'btn': true, 'btn-outline-danger': isDeleteChangeMany == 1, 'btn-outline-success': isDeleteChangeMany == 0 }"
                            @click="changeIsDeleteMany">
                            <i
                                :class="{ 'fa-solid': true, 'fa-trash': isDeleteChangeMany == 1, 'fa-trash-arrow-up': isDeleteChangeMany == 0 }"></i>
                            {{ isDeleteChangeMany == 1 ? 'Delete' : 'Backup' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import UserRequest from '@/restful/UserRequest';
import useEventBus from '@/composables/useEventBus';

const { emitEvent } = useEventBus();

export default {
    name: "DeleteManyMember",
    props: {
        selectedMembers: Array,
        members: Object,
        isDeleteChangeMany: Number
    },
    methods: {
        changeIsDeleteMany: async function () {
            const selectedManagarsArray = Object.values(this.selectedMembers);
            var data = {
                ids_member: selectedManagarsArray,
                is_delete: this.isDeleteChangeMany
            }
            try {
                const { messages } = await UserRequest.post('user/delete-many-member', data, true);
                emitEvent('eventSuccess', messages[0]);
                emitEvent('eventRegetMembers', '');
                const closeButton = this.$refs.closeButton;
                closeButton.click();
            }
            catch (error) {
                if (error.messages) emitEvent('eventError', error.messages[0]);
            }
        },
    }
}
</script>

<style scoped>
.modal-header .close {
    outline: none;
}

.modal-dialog {
    max-width: 650px;
}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    .modal-dialog {
        max-width: 400px;
        margin: 10px auto;
        font-size: 12px;
    }

    .modal-header {
        padding: auto;
    }

    .modal-header .close {
        font-size: 20px;
    }

    .btn {
        font-size: 13px;
    }
}

@media screen and (min-width: 769px) and (max-width: 992px) {
    .modal-dialog {
        max-width: 350px;
        margin: 10px auto;
        font-size: 11px;
        ;
    }

    .modal-header {
        padding: auto;
    }

    .modal-header .close {
        font-size: 18px;
    }

    .btn {
        font-size: 12px;
    }

    .modal-body {
        padding: 14px 14px 0 14px;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {
    .modal-dialog {
        max-width: 310px;
        margin: 10px auto;
        font-size: 10px;
    }

    .modal-header {
        padding: auto;
    }

    .modal-header .close {
        font-size: 11px;
    }

    .btn {
        font-size: 10px;
    }

    .modal-body {
        padding: 14px 14px 0 14px;
    }

    .alert{
        padding: 8px;
    }
}

@media screen and (min-width: 425px) and (max-width: 575px) {
    .modal-dialog {
        max-width: 260px;
        margin: 10px auto;
        font-size: 8px;
        ;
    }

    .modal-header,
    .modal-footer {
        padding: 5px 5px;
    }

    .modal-header .close {
        font-size: 11px;
    }

    .btn {
        font-size: 8px;
    }

    .modal-body {
        padding: 12px 12px 0 12px;
    }

    .alert{
        padding: 8px;
    }

    .pl-8{
        padding-left: 8px;
    }
}

@media screen and (min-width: 375px) and (max-width: 424px) {
    .modal-dialog {
        max-width: 240px;
        margin: 10px auto;
        font-size: 9px;
        ;
    }

    .modal-header,
    .modal-footer {
        padding: 5px 5px;
    }

    .modal-header .close {
        font-size: 9px;
    }

    .btn {
        font-size: 7px;
    }

    .modal-body {
        padding: 11px 11px 0 11px;
    }

    .alert {
        padding: 6px 10px;
    }

    .pl-8{
        padding-left: 6px;
    }
}
</style>
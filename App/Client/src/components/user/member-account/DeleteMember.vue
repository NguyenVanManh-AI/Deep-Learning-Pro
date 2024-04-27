<template>
    <div>
        <div class="modal fade" id="deleteMember" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-triangle-exclamation"></i>
                            Warning</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="text-danger"><i class="fa-regular fa-circle-xmark"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <p>Warning: These people will be moved to <strong>{{ memberSelected.is_delete == 0 ? 'Deleted' :
                                'Normal' }}</strong> status in the system !</p>
                            <p>Name : <strong>{{ memberSelected.name }}</strong> </p>
                            <p>Email : <strong>{{ memberSelected.email }}</strong> </p>
                            <p>LINE User ID : <strong>{{ memberSelected.line_user_id }}</strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" ref="closeButton"
                            id="close">Close</button>
                        <button type="button"
                            :class="{ 'btn': true, 'btn-outline-danger': memberSelected.is_delete == 0, 'btn-outline-success': memberSelected.is_delete == 1 }"
                            @click="deleteBook">
                            <i
                                :class="{ 'fa-solid': true, 'fa-trash': memberSelected.is_delete == 0, 'fa-trash-arrow-up': memberSelected.is_delete == 1 }"></i>
                            {{ memberSelected.is_delete == 0 ? 'Delete' : 'Backup' }}
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
    name: "DeleteMember",

    props: {
        memberSelected: Object
    },

    data() {
        return {
            dataSubmit: {
                is_delete: '',
            }
        }
    },

    methods: {
        deleteBook: async function () {
            try {
                if (this.memberSelected.is_delete == 0) this.dataSubmit.is_delete = 1;
                else this.dataSubmit.is_delete = 0;
                const { messages } = await UserRequest.post('user/delete-member/' + this.memberSelected.id, this.dataSubmit, true);
                emitEvent('eventSuccess', messages[0]);
                const closeButton = this.$refs.closeButton;
                closeButton.click();
                emitEvent('eventUpdateIsDeleteMember', this.memberSelected.id);
            }
            catch (error) {
                if (error.errors) this.errors = error.errors;
                else for (let key in this.errors) this.errors[key] = null;
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

@media screen and (min-width: 1201px) {}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    .modal-dialog {
        max-width: 400px;
        margin: 10px auto;
        font-size: 13px;;
    }

    .modal-header{
        padding: auto;
    }

    .modal-header .close {
        font-size: 20px;
    }

    .btn{
        font-size: 13px;
    }
}

@media screen and (min-width: 769px) and (max-width: 992px) {
    .modal-dialog {
        max-width: 350px;
        margin: 10px auto;
        font-size: 11px;;
    }

    .modal-header{
        padding: auto;
    }

    .modal-header .close {
        font-size: 18px;
    }

    .btn{
        font-size: 12px;
    }

    .modal-body{
        padding: 14px 14px 0 14px;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {
    .modal-dialog {
        max-width: 320px;
        margin: 10px auto;
        font-size: 9px;;
    }

    .modal-header{
        padding: auto;
    }

    .modal-header .close {
        font-size: 11px;
    }

    .btn{
        font-size: 10px;
    }

    .modal-body{
        padding: 14px 14px 0 14px;
    }
    .alert{
        padding: 8px;
    }
}

@media screen and (min-width: 425px) and (max-width: 575px) {
    .modal-dialog {
        max-width: 275px;
        margin: 10px auto;
        font-size: 9px;;
    }

    .modal-header, .modal-footer{
        padding: 5px 5px;
    }

    .modal-header .close {
        font-size: 11px;
    }

    .btn{
        font-size: 8px;
    }

    .modal-body{
        padding: 12px 12px 0 12px;
    }

    .alert{
        padding: 8px;
    }
}

@media screen and (min-width: 375px) and (max-width: 424px) {
    .modal-dialog {
        max-width: 180px;
        margin: 10px auto;
        font-size: 7px;;
    }

    .modal-header, .modal-footer{
        padding: 5px 5px;
    }

    .modal-header .close {
        font-size: 9px;
    }

    .btn{
        font-size: 7px;
    }

    .modal-body{
        padding: 11px 11px 0 11px;
    }
    
    .alert{
        padding: 6px 10px;
    }
}
</style>
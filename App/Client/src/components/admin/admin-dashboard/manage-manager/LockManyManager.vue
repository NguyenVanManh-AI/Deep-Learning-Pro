<template>
    <div>
        <div class="modal fade" id="lockManyManager" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                            <p>Warning: These people will be moved to <strong>{{ isLockChangeMany == 1 ? 'Locked' : 'Normal'
                            }}</strong> status in the system !</p>
                            <div v-for="(manager, index) in managers" :key="index">
                                <li class="mb-2" v-if="selectedManagers.includes(manager.id)">
                                    <p>{{ index + 1 }}. Name : <strong>{{ manager.name }}</strong></p>
                                    <div class="pl-6">
                                        <p>Email : <strong>{{ manager.email }}</strong></p>
                                        <p>LINE User ID : <strong>{{ manager.line_user_id }}</strong></p>
                                    </div>
                                </li>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" ref="closeButton"
                            id="close">Close</button>
                        <button type="button"
                            :class="{ 'btn': true, 'btn-outline-danger': isLockChangeMany == 1, 'btn-outline-success': isLockChangeMany == 0 }"
                            @click="changeIsLockMany">
                            <i
                                :class="{ 'fa-solid': true, 'fa-lock': isLockChangeMany == 1, 'fa-lock-open': isLockChangeMany == 0 }"></i>
                            {{ isLockChangeMany == 1 ? 'Lock' : 'UnLock' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import AdminRequest from '@/restful/AdminRequest';
import useEventBus from '@/composables/useEventBus';

const { emitEvent } = useEventBus();

export default {
    name: "LockManyManager",
    props: {
        selectedManagers: Array,
        managers: Object,
        isLockChangeMany: Number
    },
    components: {
    },
    methods: {
        changeIsLockMany: async function () {
            const selectedManagarsArray = Object.values(this.selectedManagers);
            var data = {
                ids_manager: selectedManagarsArray,
                is_block: this.isLockChangeMany
            }
            try {
                const { messages } = await AdminRequest.post('admin/block-many-manager', data, true);
                emitEvent('eventSuccess', messages[0]);
                emitEvent('eventRegetManagers', '');
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
        font-size: 13px;
        ;
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
        max-width: 300px;
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
        font-size: 9px;
        ;
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
}

@media screen and (min-width: 425px) and (max-width: 575px) {
    .modal-dialog {
        max-width: 220px;
        margin: 10px auto;
        font-size: 7px;
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
}

@media screen and (min-width: 375px) and (max-width: 424px) {
    .modal-dialog {
        max-width: 210px;
        margin: 10px auto;
        font-size: 7px;
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
}</style>
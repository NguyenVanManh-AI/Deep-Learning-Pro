<template>
    <div>
        <div class="modal fade" id="deleteManyBroadcast" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                            <p class="mb-2">Warning: These broadcasts will be moved to <strong>{{ isDeleteChangeMany ==
                                1 ? 'Deleted' : 'Normal' }}</strong> status in the system !</p>
                            <div v-for="(broadcast, index) in broadcasts" :key="index">
                                <li class="mb-3 mt-3" v-if="selectedBroadcasts.includes(broadcast.id)">
                                    <div class="row font-weight-bold">
                                        <div class="col-4 text-info">{{ index + 1 }}. <i
                                                class="fa-solid fa-user-clock"></i> Sender</div>
                                        <div class="col-8 text-success"> : {{ broadcast.sender_name }}</div>
                                    </div>
                                    <div class="row font-weight-bold pl-5">
                                        <div class="col-4 text-info"><i class="fa-solid fa-heading"></i> Title
                                        </div>
                                        <div class="col-8 text-success"> : {{ broadcast.title }}</div>
                                    </div>
                                    <div class="row font-weight-bold pl-5">
                                        <div class="col-4 text-info"><i class="fa-solid fa-shapes"></i> Status</div>
                                        <div :class="{
                                'col-8': true, 'text-uppercase': true,
                                'colorDraf': broadcast.status == 'draf',
                                'colorScheduled': broadcast.status == 'scheduled',
                                'colorSent': broadcast.status == 'sent',
                                'colorFailed': broadcast.status == 'failed',
                            }"> : {{ broadcast.status }}</div>
                                    </div>
                                    <div class="row mb-2 font-weight-bold pl-5">
                                        <div class="col-4 text-info"><i class="fa-solid fa-clock"></i> Sent At</div>
                                        <div class="col-8 text-success"> : {{ broadcast.sent_at }}</div>
                                    </div>
                                </li>
                                <hr>
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

const { emitEvent, onEvent } = useEventBus();

export default {
    name: "DeleteManyBroadcast",
    props: {
        selectedBroadcasts: Array,
        isDeleteChangeMany: Number,
        getStickerImageUrl: Function
    },
    components: {

    },
    data() {
        return {
            broadcasts: null,
        }
    },
    mounted() {
        onEvent('selectManyBroadcast', (broadcasts) => {
            this.broadcasts = broadcasts;
            console.log(this.broadcasts);
        });
    },
    methods: {
        changeIsDeleteMany: async function () {
            const selectedBroadcastsArray = Object.values(this.selectedBroadcasts);
            var data = {
                ids_broadcast: selectedBroadcastsArray,
                is_delete: this.isDeleteChangeMany
            }
            try {
                const { messages } = await UserRequest.post('broadcast/delete-many-broadcast', data, true);
                emitEvent('eventSuccess', messages[0]);
                emitEvent('eventRegetBroadcast', '');
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

.imgInTable img {
    max-width: 100px;
}

.innerData {
    display: flex;
    align-items: center;
    align-content: center;
    margin-top: 10px;
}

.innerData img {
    margin-left: 3px;
    border-radius: 6px;
}

.modal-dialog {
    max-width: 620px;
}

.contentText {
    color: var(--user-color);
}

.contentType {
    color: var(--blue-color);
}

.colorImage {
    color: var(--blue-color);
}

.colorText {
    color: var(--brown-color)
}

.colorSticker {
    color: var(--yellow-color)
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

.alert {
    margin-bottom: 0;
}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    .modal-dialog {
        max-width: 400px;
        margin: 10px auto;
        font-size: 12px;
    }

    .modal-header .close {
        font-size: 20px;
    }

    .btn {
        font-size: 13px;
    }

    .col-4,
    .col-8 {
        padding-right: 0;
    }

    .pl-5 {
        padding-left: 35px !important;
    }
}

@media screen and (min-width: 769px) and (max-width: 992px) {
    .modal-dialog {
        max-width: 350px;
        margin: 10px auto;
        font-size: 12px;
    }

    .modal-header .close {
        font-size: 20px;
    }

    .btn {
        font-size: 12px;
    }

    .modal-body {
        padding: 14px;
    }

    .col-4,
    .col-8 {
        padding-right: 0;
    }

    .pl-5 {
        padding-left: 30px !important;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {
    .modal-dialog {
        max-width: 320px;
        margin: 10px auto;
        font-size: 11px;
    }

    .modal-header {
        padding: 6px 9px;
    }

    .modal-header .close {
        font-size: 18px;
    }

    .btn {
        font-size: 10px;
    }

    .modal-body {
        padding: 14px;
    }

    .col-4,
    .col-8 {
        padding-right: 0;
    }

    .pl-5 {
        padding-left: 25px !important;
    }

    .mb-3 {
        margin-bottom: 5px;
    }
}

@media screen and (min-width: 425px) and (max-width: 575px) {
    .modal-dialog {
        max-width: 275px;
        margin: 10px auto;
        font-size: 10px;
    }

    .modal-header,
    .modal-footer {
        padding: 5px 8px;
    }

    .modal-header .close {
        font-size: 15px;
    }

    .btn {
        font-size: 8px;
    }

    .modal-body {
        padding: 12px;
    }

    .col-4,
    .col-8 {
        padding-right: 0;
    }

    .pl-5 {
        padding-left: 20px !important;
    }

    .mb-3 {
        margin-bottom: 3px;
    }

    .alert {
        padding: 8px;
    }
}

@media screen and (min-width: 375px) and (max-width: 424px) {
    .modal-dialog {
        max-width: 260px;
        margin: 10px auto;
        font-size: 10px;
    }

    .modal-header,
    .modal-footer {
        padding: 5px 8x;
    }

    .modal-header .close {
        font-size: 15px;
    }

    .btn {
        font-size: 7px;
    }

    .modal-body {
        padding: 11px;
    }

    .col-4,
    .col-8 {
        padding-right: 0;
    }

    .pl-5 {
        padding-left: 25px !important;
    }

    .mb-3 {
        margin-bottom: 5px;
    }

    .alert {
        padding: 6px 10px;
    }
}
</style>

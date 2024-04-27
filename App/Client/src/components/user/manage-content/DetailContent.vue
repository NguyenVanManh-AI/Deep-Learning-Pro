<template>
    <div>
        <div class="modal fade" id="modal-view-detail-content" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-envelope-open-text"></i>
                            Detail
                            Content</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            <div class="ml-3">
                                <p> Creator : <strong>{{ contentSelected.creator_name }}</strong> </p>
                                <p> Updater : <strong>{{ contentSelected.updater_name }}</strong> </p>
                            </div>
                            <div v-if="contentSelected.content_type == 'text'">
                                <div class="ml-3">
                                    Content Type : <strong class="text-uppercase colorText"> {{ contentSelected.content_type }} </strong><br>
                                    Content Data : <strong class="contentText">{{ contentSelected.content_data.text
                                        }}</strong>
                                </div>
                            </div>
                            <div class="imgInTable" v-if="contentSelected.content_type == 'image'">
                                <div class="ml-3">
                                    Content Type : <strong class="text-uppercase colorImage"> {{ contentSelected.content_type }} </strong><br>
                                    <div class="innerData">
                                        Content Data : <img :src="contentSelected.content_data.originalContentUrl"
                                            alt="Image" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" ref="closeButton"
                            id="close">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import useEventBus from '@/composables/useEventBus';
const { onEvent } = useEventBus();

export default {
    name: "viewDetailContent",

    props: {
    },
    data() {
        return {
            contentSelected: {
                id: '',
                content_type: '',
                content_data: null,
                is_delete: null,
                creator_name: null,
                updater_name: null,
            },
        }
    },
    mounted() {
        onEvent('selectSimpleContentDetail', (contentSelected) => {
            this.contentSelected = contentSelected; 
        });
    },
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
    max-width: 600px;
}

.contentText {
    color: var(--user-color);
}

.colorImage {
    color: var(--blue-color);
}

.colorText {
    color: var(--brown-color)
}

.alert {
    margin-bottom: 0;
}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    .modal-dialog {
        max-width: 400px;
        margin: 10px auto;
        font-size: 12px;
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
        max-width: 350px;
        margin: 10px auto;
        font-size: 12px;
    }

    .modal-header {
        font-size: 13px;
        padding-bottom: 10px;
        padding-top: 10px;
    }

    .modal-header .close {
        font-size: 18px;
    }

    .btn {
        font-size: 12px;
    }

    .ml-3 {
        margin-left: 0 !important;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {
    .modal-dialog {
        max-width: 320px;
        margin: 10px auto;
        font-size: 11px;
    }

    .modal-header {
        font-size: 12px;
        padding-bottom: 9px;
        padding-top: 9px;
    }

    .modal-header .close {
        font-size: 11px;
    }

    .btn {
        font-size: 10px;
    }

    .modal-body {
        padding: 12px;
    }

    .ml-3 {
        margin-left: 0 !important;
    }
}

@media screen and (min-width: 425px) and (max-width: 575px) {
    .modal-dialog {
        max-width: 275px;
        margin: 10px auto;
        font-size: 9px;
    }

    .modal-header,
    .modal-footer {
        font-size: 10px;
        padding-bottom: 8px;
        padding-top: 8px;
    }

    .modal-header .close {
        font-size: 11px;
    }

    .btn {
        font-size: 9px;
        padding: 3px 8px;
    }

    .modal-body {
        padding: 12px;
    }

    .ml-3 {
        margin-left: 0 !important;
    }
}

@media screen and (min-width: 375px) and (max-width: 424px) {
    .modal-dialog {
        max-width: 180px;
        margin: 10px auto;
        font-size: 8px;
    }

    .modal-header,
    .modal-footer {
        font-size: 10px;
        padding-bottom: 5px;
        padding-top: 5px;
    }

    .modal-header .close {
        font-size: 11px;
    }

    .btn {
        font-size: 9px;
        padding: 2px 5px;
    }

    .modal-body {
        padding: 12px;
    }

    .ml-3 {
        margin-left: 0 !important;
    }

    .alert {
        padding: 5px 10px;
    }
}
</style>

<template>
    <div>
        <div id="big">
            <div class="bigContainer">
                <div class="modal fade" id="updateBroadcast" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><strong><i
                                            class="fa-solid fa-business-time"></i>
                                        Update Broadcast</strong></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class="text-danger"><i
                                            class="fa-regular fa-circle-xmark"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="broadcast-time">
                                    <div class="col-12">
                                        <form action="">
                                            <div class="row">
                                                <div class="col-2">
                                                    <strong>Broadcast time</strong>
                                                </div>
                                                <div class="col-10">
                                                    <div class="row">
                                                        <input v-model="optionSendnow" class="form-check-input"
                                                            type="radio" name="sent_at" id="exampleRadios1"
                                                            value="send_now"> Send Now
                                                    </div>
                                                    <div class="row">
                                                        <input v-model="optionSendnow" class="form-check-input"
                                                            type="radio" name="sent_at" id="exampleRadios1"
                                                            value="select_time">
                                                        <VueDatePicker class="col-6 ml-0 pl-0" v-model="dateTime"
                                                            required>
                                                        </VueDatePicker>
                                                    </div>
                                                    <span v-if="errors.sent_at" class="text-danger">{{
                                                            errors.sent_at[0] }}<br></span>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <hr class="my-3">
                                <form>
                                    <div class="form">
                                        <div class="">
                                            <div class="row pr-2 mb-2 ">
                                                <input class="form-control form-control-sm col-12" required
                                                    v-model="dataBroadcastSubmit.title" type="text" placeholder="Title">
                                                <span v-if="errors.title" class="text-danger">{{
                                                            errors.title[0] }}<br></span>
                                            </div>
                                            <div class="row pr-2">
                                                <ul class="nav nav-tabs mainTab">
                                                    <li @click="isTab = 'text'" class="nav-item  font-weight-bold">
                                                        <a :class="{ 'nav-link': true, 'colorText': true, 'active': isTab == 'text' }"
                                                            aria-current="page" href="#"><i
                                                                class="fa-solid fa-quote-left"></i>
                                                            Text</a>
                                                    </li>
                                                    <li @click="isTab = 'sticker'" class="nav-item font-weight-bold">
                                                        <a :class="{ 'nav-link': true, 'colorSticker': true, 'active': isTab == 'sticker' }"
                                                            href="#"><i class="fa-solid fa-icons"></i> Sticker</a>
                                                    </li>
                                                    <li @click="isTab = 'image'" class="nav-item font-weight-bold">
                                                        <a :class="{ 'nav-link': true, 'colorImage': true, 'active': isTab == 'image' }"
                                                            href="#"><i class="fa-solid fa-image"></i> Image</a>
                                                    </li>
                                                </ul>
                                                <div class="loadContent col-12 mainText" v-if="isTab == 'text'">
                                                    <li v-for="text, index in dataContents.texts" :key="index"
                                                        class="mt-2 mb-2">
                                                        <p><input @click="selectedIdContent(text, text.id)"
                                                                :checked="checkChecked(text.id)" class="mr-2"
                                                                type="checkbox" name="exampleRadios" id="exampleRadios1"
                                                                value="option1">{{
                                                            text.content_data.text }}</p>
                                                        <hr>
                                                    </li>
                                                </div>
                                                <div class="loadContent col-12 mainSticker" v-if="isTab == 'sticker'">
                                                    <div v-for="(packageSticker, indexPackageSticker) in packageStickers"
                                                        :key="indexPackageSticker">
                                                        <li class="itemSticker"
                                                            v-for="stickerId in generateNumbers(packageSticker.stickerIds.start, packageSticker.stickerIds.end)"
                                                            :key="stickerId">
                                                            <input
                                                                :checked="checkChecked(handleIdSticker(String(stickerId), packageSticker.packageId))"
                                                                @click="selectedSticker(String(stickerId), packageSticker.packageId)"
                                                                class="form-check-input" type="checkbox"
                                                                name="exampleRadios" id="exampleRadios1"
                                                                value="option1">
                                                            <img :src="getStickerImageUrl(stickerId)" alt="Sticker" />
                                                        </li>
                                                    </div>
                                                </div>
                                                <div class="loadContent col-12 mainImage" v-if="isTab == 'image'">
                                                    <li v-for="image, index in dataContents.images" :key="index"
                                                        class="itemImage">
                                                        <input @click="selectedIdContent(image, image.id)"
                                                            :checked="checkChecked(image.id)" class="form-check-input"
                                                            type="checkbox" name="exampleRadios" id="exampleRadios1"
                                                            value="option1">
                                                        <img :src="image.content_data.originalContentUrl"
                                                            alt="Sticker" />
                                                    </li>
                                                </div>
                                                <span v-if="errors.content_ids" class="text-danger">{{
                                                            errors.content_ids[0] }}<br></span>
                                            </div>
                                            <div class="button">
                                                <div class="mt-4">
                                                    <div>
                                                        <button @click="updateBroadcast('draf')" type="button"
                                                            class="mt-4 btn-pers" id="login_button"><i
                                                                class="fa-solid fa-plus"></i> Draf</button>
                                                        <button @click="updateBroadcast('scheduled')" type="button"
                                                            class="mt-4 ml-6 btn-pers" id="login_button"><i
                                                                class="fa-solid fa-floppy-disk"></i> Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-message p-0">
                                            <div class="title_preview"> <i class="fa-solid fa-caret-down"></i>
                                                <span>Preview</span> <i class="fa-solid fa-circle-question"></i>
                                            </div>
                                            <ul class="inner_preview_update">
                                                <li v-for="content, index in previewContents" :key="index"
                                                    :data-id_message="JSON.stringify(content.id)">
                                                    <div class="rowContent" v-if="content.content_type == 'text'">
                                                        <div class="avatar_chat">
                                                            <img :src="channel.picture_url ? channel.picture_url : require('@/assets/line_logo.jpg')"
                                                                alt="">
                                                        </div>
                                                        <div class="content_chat">
                                                            <div class="nameChannel">{{ channel.channel_name }}</div>
                                                            <div class="contentText">
                                                                {{ content.content_data.text }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="rowContent imgInTable"
                                                        v-if="content.content_type == 'sticker'">
                                                        <div class="avatar_chat">
                                                            <img :src="channel.picture_url ? channel.picture_url : require('@/assets/line_logo.jpg')"
                                                                alt="">
                                                        </div>
                                                        <div class="content_chat">
                                                            <div class="nameChannel">{{ channel.channel_name }}</div>
                                                            <div class="innerData">
                                                                <img :src="getStickerImageUrl(content.content_data.stickerId)"
                                                                    alt="Sticker" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="rowContent imgInTable"
                                                        v-if="content.content_type == 'image'">
                                                        <div class="avatar_chat">
                                                            <img :src="channel.picture_url ? channel.picture_url : require('@/assets/line_logo.jpg')"
                                                                alt="">
                                                        </div>
                                                        <div class="content_chat">
                                                            <div class="nameChannel">{{ channel.channel_name }}</div>
                                                            <div class="innerData">
                                                                <img :src="content.content_data.originalContentUrl"
                                                                    alt="Image" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import useEventBus from '@/composables/useEventBus'
import UserRequest from '@/restful/UserRequest';
const { emitEvent, onEvent } = useEventBus();
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { formatDate } from '@/helper.js';
import $ from 'jquery';
import 'jquery-ui-dist/jquery-ui';

export default {
    name: "UpdateBroadcast",
    props: {
        channel: Object,
        dataContents: Object,
        packageStickers: Array
    },
    components: {
        VueDatePicker
    },
    data() {
        return {
            dateTime: new Date().toISOString(),
            optionSendnow: 'select_time',
            dataBroadcastSubmit: {
                id: null,
                title: null,
                content_ids: [],
                sent_at: null,
                status: null,
            },
            previewContents: [],

            isTab: 'text',
            previewImageSrc: null,
            errors: {
                title: null,
                content_ids: null,
                sent_at: null,
                status: null
            },
        }
    },
    mounted() {
        $(this.$el).find(".inner_preview_update").sortable();
        onEvent('selectSimpleBroadcast', (broadcast) => {
            this.dataBroadcastSubmit = Object.assign({}, broadcast);
            this.dataBroadcastSubmit.content_ids = this.dataBroadcastSubmit.content_ids.map((content_id) => JSON.stringify(content_id));
            console.log(this.dataBroadcastSubmit.content_ids);
            this.dateTime = broadcast.sent_at;
            this.previewContents = broadcast.contents;
            console.log(this.previewContents);
        });

    },
    methods: {
        getStickerImageUrl(stickerId) {
            return `https://stickershop.line-scdn.net/stickershop/v1/sticker/${stickerId}/ANDROID/sticker.png`;
        },
        generateNumbers(start, end) {
            return Array.from({ length: end - start + 1 }, (_, index) => start + index);
        },
        selectedIdContent: function (content, contentId) {
            if (event.target.checked == true) {
                if (this.dataBroadcastSubmit.content_ids.length == 5) {
                    this.dataBroadcastSubmit.content_ids.shift();
                    this.previewContents.shift();
                }
                this.dataBroadcastSubmit.content_ids.push(JSON.stringify(contentId));
                this.previewContents.push(content);
            }
            else {
                let indexId = this.dataBroadcastSubmit.content_ids.indexOf(JSON.stringify(contentId));
                if (indexId !== -1) this.dataBroadcastSubmit.content_ids.splice(indexId, 1);

                let indexToRemove = this.previewContents.findIndex(content => JSON.stringify(content.id) === JSON.stringify(contentId));
                if (indexToRemove !== -1) this.previewContents.splice(indexToRemove, 1);
            }
        },
        selectedSticker: function (stickerId, packageId) {
            const contentId = {
                stickerId: stickerId,
                packageId: packageId
            }
            const content = {
                content_type: 'sticker',
                id: contentId,
                content_data: contentId
            }
            this.selectedIdContent(content, contentId);
        },
        handleIdSticker: function (stickerId, packageId) {
            return {
                stickerId: stickerId,
                packageId: packageId
            };
        },
        updateNewArrayId() {
            const listItems = document.querySelectorAll('.inner_preview_update li');
            const idMessages = [];
            listItems.forEach((item) => {
                const idMessage = item.getAttribute('data-id_message');
                if (idMessage) {
                    idMessages.push(JSON.parse(idMessage));
                }
            });
            return idMessages;
        },
        updateBroadcast: async function (status) {
            this.dataBroadcastSubmit.content_ids = this.updateNewArrayId();
            this.dataBroadcastSubmit.status = status;
            if (this.optionSendnow == 'send_now') {
                const now = new Date();
                const formattedDate = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')} ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;
                this.dataBroadcastSubmit.sent_at = formattedDate;
            }
            try {
                const { messages } = await UserRequest.post('broadcast/update/' + this.dataBroadcastSubmit.id, this.dataBroadcastSubmit, true);
                emitEvent('eventSuccess', messages[0]);
                for (let key in this.errors) this.errors[key] = null;
                var closePW = window.document.getElementById('updateBroadcast');
                closePW.click();
                this.dataBroadcastSubmit = {
                    title: null,
                    content_ids: [],
                    sent_at: null,
                    status: null,
                },
                    this.previewContents = [];
                emitEvent('eventRegetBroadcast', '');
            }
            catch (error) {
                if (error.errors) this.errors = error.errors;
                else for (let key in this.errors) this.errors[key] = null;
                if (error.messages) emitEvent('eventError', error.messages[0]);
            }
        },
        checkChecked: function (contentId) {
            contentId = JSON.stringify(contentId);
            return this.dataBroadcastSubmit.content_ids.includes(contentId);
        }
    },
    watch: {
        dateTime: function (newDateTime) {
            if (typeof newDateTime === 'string') {
                this.dataBroadcastSubmit.sent_at = newDateTime;
            }
            else {
                if (newDateTime) {
                    const formattedDate = formatDate(newDateTime);
                    this.dataBroadcastSubmit.sent_at = formattedDate;
                    this.optionSendnow = 'select_time';
                    console.log(this.dataBroadcastSubmit.sent_at);
                }
            }
        },
    },
}
</script>

<style scoped>
.itemSticker {
    width: 90px;
    display: flex;
    justify-content: center;
    border: 1px solid silver;
    position: relative;
    margin: 1%;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.5s ease;
}

.itemSticker:hover {
    transition: all 0.5s ease;
    box-shadow: rgba(136, 165, 191, 0.48) 6px 2px 16px 0px, rgba(255, 255, 255, 0.8) -6px -2px 16px 0px;
}

.itemSticker img {
    object-fit: contain;
    transition: all 0.5s ease;
}

.itemSticker input {
    z-index: 2;
    position: absolute;
    top: -2px;
    right: 2px;
}

.itemSticker:hover img {
    transform: scale(1.3);
    transition: all 0.5s ease;
}

.itemImage {
    width: 90px;
    display: flex;
    justify-content: center;
    position: relative;
    margin: 1%;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.5s ease;
}

.itemImage:hover {
    transition: all 0.5s ease;
    box-shadow: rgba(136, 165, 191, 0.48) 6px 2px 16px 0px, rgba(255, 255, 255, 0.8) -6px -2px 16px 0px;
}

.itemImage img {
    object-fit: contain;
    transition: all 0.5s ease;
    object-fit: cover;
    transition: all 0.5s ease;
    width: 90px;
    height: 90px;
    border-radius: 6px;
}

.itemImage:hover img {
    transform: scale(1.2);
    transition: all 0.5s ease;
}

.itemImage input {
    z-index: 2;
    position: absolute;
    top: -2px;
    right: 2px;
}


.loadContent {
    border: 1px solid;
    border-color: #dee2e6;
    margin-top: -1px;
    padding: 10px;
    border-bottom-left-radius: 6px;
    border-bottom-right-radius: 6px;
}

.col-12 {
    max-width: 90%;
}

.mainImage {
    display: flex;
    flex-wrap: wrap;
    max-height: 400px;
    overflow-y: scroll;
}

.mainSticker,
.mainText {
    max-height: 400px;
    overflow-y: scroll;
}

.mainSticker>div {
    display: flex;
    flex-wrap: wrap;
}

.colorImage {
    color: var(--blue-color);
}

.colorText {
    color: var(--brown-color)
}

.mainTab {
    z-index: 1;
}

.mainTab>li:hover {
    transition: all 0.5s;
}

.mainTab>li {
    transition: all 0.5s;
}

.colorSticker {
    color: var(--yellow-color)
}

.modal.fade.show {
    padding-left: 0px;
}

.modal-content {
    height: 90vh;
    margin-top: 10px;
    padding-left: 10px;
    padding-right: 10px;
    border-radius: 10px;
}

.title_preview {
    background-color: black;
    color: white;
    padding: 10px;
    font-size: 20px;
    background-color: #353A40;
}

.title_preview span {
    margin: 0px 6px;
}

.inner_preview_update {
    padding: 10px;
    height: 60vh;
    background-color: #8CABD9;
    overflow-y: scroll;
    min-width: 350px;
}

.inner_preview_update::-webkit-scrollbar-track {
    background: #8CABD9;
}

.rowContent {
    display: flex;
    margin-bottom: 10px;
}

.avatar_chat {
    width: 50px;
    height: 50px;
    background-color: white;
    border-radius: 200px;
    margin-right: 10px;
}

.content_chat {
    width: 80%;
}

.content_chat img {
    max-width: 200px;
    max-height: 120px;
    border-radius: 10px;
}

.avatar_chat img {
    width: 50px;
    height: 50px;
    border-radius: 200px;
}

.nameChannel {
    color: white;
}

.contentText {
    background-color: white;
    border-radius: 10px;
    width: fit-content;
    padding: 10px;
    max-width: 270px;
}

@import url('https://fonts.googleapis.com/css2?family=Reem+Kufi+Ink');

#big {
    display: flex;
    position: relative;
}

.btn-pers {
    position: relative;
    left: 50%;
    padding: 1em 2.5em;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 2.5px;
    font-weight: 700;
    color: #000;
    background-color: #fff;
    border: none;
    border-radius: 45px;
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease 0s;
    cursor: pointer;
    outline: none;
    transform: translateX(-50%);
}

.btn-pers:hover {
    background-color: var(--user-color);
    box-shadow: 0px 15px 20px #80ffb5;
    color: #fff;
    transform: translate(-50%, -7px);
}

.btn-pers:active {
    transform: translate(-50%, -1px);
}

#inputPassword {
    padding-right: 26px;
}

.form {
    display: flex;
    flex-wrap: nowrap;
    justify-content: space-around;
}

.row {
    margin-right: -70px;
    margin-left: 0;
}

.preview-message {
    min-width: 350px;
}

.modal-dialog {
    max-width: 90vw;
    width: 90vw;
    margin: 1.75rem auto;
}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    .modal-dialog {
        max-width: 970px;
        margin: 13px auto;
        font-size: 14px;
    }

    .modal-header {
        padding: auto;
    }

    .modal-header .close {
        font-size: 20px;
    }

    .modal-content {
        height: 670px;
    }

    .mainImage,
    .mainSticker,
    .mainText {
        max-height: 300px;
    }

    .itemImage,
    .itemSticker {
        width: 85px;
        height: 85px;
    }

    .btn,
    .title_preview,
    .form-control,
    .dp__input {
        font-size: 14px !important;
    }

    .dp__menu_inner {
        font-size: 14px !important;
        padding: 3px !important;
    }

    .row {
        margin-right: -60px;
        margin-left: 0;
    }

    .preview-message,
    .inner_preview_update {
        min-width: 300px;
        height: 400px;
        font-size: 14px;
    }

    .content_chat img {
        max-width: 180px;
        max-height: 100px;
    }

    .contentText {
        max-width: 200px;
    }

    .avatar_chat img,
    .avatar_chat {
        width: 45px;
        height: 45px;
    }
}

@media screen and (min-width: 769px) and (max-width: 992px) {
    .modal-dialog {
        max-width: 900px;
        margin: 13px auto;
        font-size: 13px;
    }

    .modal-header {
        padding: auto;
    }

    .modal-header .close {
        font-size: 20px;
    }

    .modal-content {
        height: 700px;
    }

    .mainImage,
    .mainSticker,
    .mainText {
        max-height: 350px;
    }

    .itemImage,
    .itemSticker,
    .itemImage img {
        width: 72px;
        height: 72px;
    }

    .btn,
    .title_preview,
    .form-control,
    .dp__input {
        font-size: 13px !important;
    }

    .dp__menu_inner {
        font-size: 13px !important;
        padding: 3px !important;
    }

    .row {
        margin-right: -40px;
        margin-left: 0;
    }

    .preview-message,
    .inner_preview_update {
        min-width: 270px;
        height: 400px;
        font-size: 13px;
    }

    .content_chat img {
        max-width: 155px;
        max-height: 90px;
    }

    .contentText {
        max-width: 185px;
        padding: 9px;
    }

    .avatar_chat img,
    .avatar_chat {
        width: 40px;
        height: 40px;
    }

    button .mt-4 {
        margin-top: 2px;
    }

    .btn-pers {
        font-size: 11px;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {
    .modal-dialog {
        max-width: 900px;
        margin: 13px auto;
        font-size: 12px;
    }

    .modal-body {
        padding: 1rem 0;
    }

    .modal-header {
        padding: auto;
    }

    .modal-header .close {
        font-size: 18px;
    }

    .modal-content {
        height: 675px;
    }

    .mainImage,
    .mainSticker,
    .mainText {
        max-height: 350px;
    }

    .itemImage,
    .itemSticker,
    .itemImage img {
        width: 70px;
        height: 70px;
    }

    .btn,
    .title_preview,
    .form-control,
    .dp__input {
        font-size: 12px !important;
    }

    .dp__menu_inner {
        font-size: 12px !important;
        padding: 3px !important;
    }

    .row {
        margin-right: -30px;
        margin-left: 0;
    }

    .preview-message,
    .inner_preview_update {
        min-width: 220px;
        height: 400px;
        font-size: 12px;
    }

    .content_chat img {
        max-width: 150px;
        max-height: 65px;
    }

    .contentText {
        max-width: 185px;
        padding: 9px;
    }

    .avatar_chat img,
    .avatar_chat {
        width: 30px;
        height: 30px;
        margin-bottom: 5px;
    }

    .rowContent {
        margin-bottom: 2px;
    }

    .mt-4 {
        margin-top: 2px;
    }

    .btn-pers {
        font-size: 9px;
    }

    .broadcast-time .row {
        margin-left: -15px;
        margin-right: -101px;
        display: flex;
        gap: 38px;
        flex-wrap: nowrap;
    }
}

@media screen and (min-width: 425px) and (max-width: 575px) {
    .modal-dialog {
        max-width: 800px;
        margin: 13px auto;
        font-size: 11px;
    }

    .modal-body {
        padding: 1rem 0;
    }

    .modal-header {
        padding: 5px;
        padding-top: 10px;
    }

    .modal-header .close {
        font-size: 18px;
    }

    .modal-content {
        height: 610px;
    }

    .mainImage,
    .mainSticker,
    .mainText {
        max-height: 340px;
    }

    .itemImage,
    .itemSticker,
    .itemImage img {
        width: 47px;
        height: 47px;
    }

    .btn,
    .title_preview,
    .form-control,
    .dp__input {
        font-size: 11px !important;
    }

    .dp__menu_inner {
        font-size: 11px !important;
        padding: 3px !important;
    }

    .row {
        margin-right: -20px;
        margin-left: 0;
    }

    .preview-message,
    .inner_preview_update {
        min-width: 160px;
        height: 360px;
        font-size: 11px;
    }

    .content_chat img {
        max-width: 95px;
        max-height: 60px;
        border-radius: 5px;
    }

    .contentText {
        max-width: 100px;
        padding: 9px;
    }

    .avatar_chat img,
    .avatar_chat {
        width: 20px;
        height: 20px;
        margin-bottom: 5px;
    }

    .rowContent {
        margin-bottom: 2px;
    }

    .mt-4 {
        margin-top: 10px !important;
    }

    .btn-pers {
        font-size: 9px;
        padding: 6px 9px;
    }

    .nav-link {
        padding: 2px 4px;
    }

    .broadcast-time .row {
        margin-left: -15px;
        margin-right: -101px;
        display: flex;
        gap: 38px;
        flex-wrap: nowrap;
    }

    .form .pr-2 {
        padding-right: 0;
    }

    .button .row {
        padding-right: 0;
    }
}

@media screen and (min-width: 375px) and (max-width: 424px) {
    .modal-dialog {
        max-width: 800px;
        margin: 13px auto;
        font-size: 10px;
    }

    .modal-body {
        padding: 1rem 0;
    }

    .modal-header {
        padding: 5px;
        padding-top: 10px;
    }

    .modal-header .close {
        font-size: 18px;
    }

    .modal-content {
        height: 475px;
    }

    .mainImage,
    .mainSticker,
    .mainText {
        max-height: 231px;
    }

    .itemImage,
    .itemSticker,
    .itemImage img {
        width: 40px;
        height: 40px;
    }

    .btn,
    .title_preview,
    .form-control,
    .dp__input {
        font-size: 10px !important;
    }

    .dp__menu_inner {
        font-size: 10px !important;
        padding: 3px !important;
    }

    .row {
        margin-right: -20px;
        margin-left: 0;
    }

    .preview-message,
    .inner_preview_update {
        min-width: 145px;
        height: 250px;
        font-size: 10px;
    }

    .inner_preview_update {
        padding: 8px;
    }

    .content_chat img {
        max-width: 85px;
        max-height: 45px;
        border-radius: 5px;
    }

    .contentText {
        max-width: 100px;
        padding: 9px;
    }

    .avatar_chat img,
    .avatar_chat {
        width: 20px;
        height: 20px;
        margin-bottom: 5px;
    }

    .rowContent {
        margin-bottom: 2px;
    }

    .mt-4 {
        margin-top: 10px !important;
    }

    .btn-pers {
        font-size: 9px;
        padding: 6px 9px;
    }

    .nav-link {
        padding: 2px 4px;
    }

    .broadcast-time .row {
        margin-left: -15px;
        margin-right: -101px;
        display: flex;
        gap: 38px;
        flex-wrap: nowrap;
    }

    .form .pr-2 {
        padding-right: 0;
    }

    .button .row {
        padding-right: 0;
    }
}
</style>

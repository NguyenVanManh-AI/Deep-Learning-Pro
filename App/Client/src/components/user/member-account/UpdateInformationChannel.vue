<template>
    <div class="col-10 infor-channel">
        <div class="row inputInforChannel">
            <div class="col-2">
                <div class="row mt-3">
                    <div class="col-12 " id="input-text">
                        <label><i @click="isShow1i = !isShow1i"
                                :class="{ 'fa-solid': true, 'fa-eye': !isShow1i, 'fa-eye-slash': isShow1i }"></i><span
                                class="isHideTitle1">Channel</span> ID</label>
                        <input v-if="!isShow1i" readonly class="form-control" v-model="channel.channel_id"
                            placeholder="Channel ID" :type="isShow1i ? 'text' : 'password'">
                        <input v-if="isShow1i" :content="channel.channel_id" v-tippy="{ maxWidth: '100vw' }" readonly
                            class="form-control" v-model="channel.channel_id" placeholder="Channel ID"
                            :type="isShow1i ? 'text' : 'password'">
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="row mt-3">
                    <div class="col-12" id="input-text">
                        <label><i @click="isShow2i = !isShow2i"
                                :class="{ 'fa-solid': true, 'fa-eye': !isShow2i, 'fa-eye-slash': isShow2i }"></i> <span
                                class="isHideTitle1">Channel</span> Name</label>
                        <input v-if="!isShow2i" class="form-control" readonly v-model="channel.channel_name"
                            placeholder="Channel Name" :type="isShow2i ? 'text' : 'password'">
                        <input v-if="isShow2i" :content="channel.channel_name" v-tippy="{ maxWidth: '100vw' }"
                            class="form-control" readonly v-model="channel.channel_name" placeholder="Channel Name"
                            :type="isShow2i ? 'text' : 'password'">
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="row mt-3">
                    <div class="col-12" id="input-text">
                        <label><i @click="isShow3i = !isShow3i"
                                :class="{ 'fa-solid': true, 'fa-eye': !isShow3i, 'fa-eye-slash': isShow3i }"></i> <span
                                class="isHideTitle1">Channel</span> Secret</label>
                        <input v-if="!isShow3i" class="form-control" readonly v-model="channel.channel_secret"
                            placeholder="Channel Secret" :type="isShow3i ? 'text' : 'password'">
                        <input v-if="isShow3i" :content="channel.channel_secret" v-tippy="{ maxWidth: '100vw' }"
                            class="form-control" readonly v-model="channel.channel_secret" placeholder="Channel Secret"
                            :type="isShow3i ? 'text' : 'password'">
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row mt-3">
                    <div class="col-12" id="input-text">
                        <label><i @click="isShow4i = !isShow4i"
                                :class="{ 'fa-solid': true, 'fa-eye': !isShow4i, 'fa-eye-slash': isShow4i }"></i> <span
                                class="isHideTitle1">Channel</span> <span class="isHideTitle1"> Access</span> Token</label>
                        <input v-if="!isShow4i" class="form-control" readonly v-model="channel.channel_access_token"
                            placeholder="Channel Access Token" :type="isShow4i ? 'text' : 'password'">
                        <input v-if="isShow4i" :content="channel.channel_access_token" v-tippy="{ maxWidth: '100vw' }"
                            class="form-control" readonly v-model="channel.channel_access_token"
                            placeholder="Channel Access Token" :type="isShow4i ? 'text' : 'password'">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex">
        <div>
            <div>
                <div class="big-container">
                    <button @click="openUpdate()" data-toggle="modal" data-target="#example-modal-change-password"
                        id="open-change-password" type="button" class="mt-4 btn-pers">
                        <div class="text-change">
                            <i class="fa-solid fa-gear"></i>
                            <span class="isHide">change</span>
                        </div>
                    </button>
                    <div class="modal fade" id="example-modal-change-password" tabindex="-1" role="dialog"
                        aria-labelledby="example-modal-label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="example-modal-label"><strong><i class="fa-brands fa-line"></i>
                                            Form Update Information Channel</strong></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" class="text-danger"><i
                                                class="fa-regular fa-circle-xmark"></i></span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form @submit.prevent="updateInforChannel()">
                                        <div class="input-form">
                                            <input required id="input-password" :type="isShow1 ? 'text' : 'password'"
                                                v-model="newChannel.channel_id">
                                            <strong id="icon-eye"><i @click="isShow1 = !isShow1"
                                                    :class="{ 'fa-solid': true, 'fa-eye': !isShow1, 'fa-eye-slash': isShow1 }"></i></strong>
                                            <div class="underline"></div><label><i class="fa-brands fa-line"></i> Channel
                                                ID</label>
                                        </div>
                                        <span v-if="errors.channel_id" class="text-danger">{{ errors.channel_id[0]
                                        }}<br></span>
                                        <br>
                                        <div class="input-form">
                                            <input required id="input-password" :type="isShow2 ? 'text' : 'password'"
                                                v-model="newChannel.channel_name">
                                            <strong id="icon-eye"><i @click="isShow2 = !isShow2"
                                                    :class="{ 'fa-solid': true, 'fa-eye': !isShow2, 'fa-eye-slash': isShow2 }"></i></strong>
                                            <div class="underline"></div><label><i class="fa-solid fa-signature"></i>
                                                Channel Name</label>
                                        </div>
                                        <span v-if="errors.channel_name" class="text-danger">{{ errors.channel_name[0]
                                        }}<br></span>
                                        <br>
                                        <div class="input-form">
                                            <input required id="input-password" :type="isShow3 ? 'text' : 'password'"
                                                v-model="newChannel.channel_secret">
                                            <strong id="icon-eye"><i @click="isShow3 = !isShow3"
                                                    :class="{ 'fa-solid': true, 'fa-eye': !isShow3, 'fa-eye-slash': isShow3 }"></i></strong>
                                            <div class="underline"></div><label><i class="fa-solid fa-key"></i> Channel
                                                Secret</label>
                                        </div>
                                        <span v-if="errors.channel_secret" class="text-danger">{{
                                            errors.channel_secret[0] }}<br></span>
                                        <br>
                                        <div class="input-form">
                                            <input required id="input-password" :type="isShow4 ? 'text' : 'password'"
                                                v-model="newChannel.channel_access_token">
                                            <strong id="icon-eye"><i @click="isShow4 = !isShow4"
                                                    :class="{ 'fa-solid': true, 'fa-eye': !isShow4, 'fa-eye-slash': isShow4 }"></i></strong>
                                            <div class="underline"></div><label><i class="fa-brands fa-keycdn"></i> Channel
                                                Access Token</label>
                                        </div>
                                        <span v-if="errors.channel_access_token" class="text-danger">{{
                                            errors.channel_access_token[0] }}<br></span>
                                        <br>
                                        <button type="submit" class="btn-pers" id="login_button"><i
                                                class="fa-solid fa-floppy-disk"></i> Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import UserRequest from '@/restful/UserRequest'
import useEventBus from '@/composables/useEventBus'
const { emitEvent } = useEventBus();

export default {
    name: "UpdateInformationChannel",
    data() {
        return {
            channel: {
                channel_id: null,
                channel_name: null,
                channel_secret: null,
                channel_access_token: null,
            },
            newChannel: {
                channel_id: null,
                channel_name: null,
                channel_secret: null,
                channel_access_token: null,
            },
            isShow1: false,
            isShow2: false,
            isShow3: false,
            isShow4: false,

            isShow1i: false,
            isShow2i: false,
            isShow3i: false,
            isShow4i: false,
            errors: {
                channel_id: null,
                channel_name: null,
                channel_secret: null,
                channel_access_token: null,
            }
        }
    },
    mounted() {
        this.getInforChannel();
    },
    methods: {
        getInforChannel: async function () {
            try {
                const { data } = await UserRequest.get('user/infor-channel');
                this.channel = data;
                for (let key in this.errors) this.errors[key] = null;
            }
            catch (error) {
                if (error.errors) this.errors = error.errors;
                else for (let key in this.errors) this.errors[key] = null;
                if (error.messages) emitEvent('eventError', error.messages[0]);
            }
        },
        openUpdate: function () {
            this.newChannel = Object.assign({}, this.channel);
        },
        updateInforChannel: async function () {
            try {
                const { data, messages } = await UserRequest.post('user/update-channel', this.newChannel, true);
                emitEvent('eventSuccess', messages[0]);
                this.channel = data;
                this.isShow1 = this.isShow2 = this.isShow3 = this.isShow4 = false;
                for (let key in this.errors) this.errors[key] = null;
                var closePW = window.document.getElementById('example-modal-change-password');
                closePW.click();
            }
            catch (error) {
                if (error.errors) this.errors = error.errors;
                else for (let key in this.errors) this.errors[key] = null;
                if (error.messages) emitEvent('eventError', error.messages[0]);
            }
        }
    },
}
</script>

<style scoped>
.modal-dialog {
    max-width: 600px;
}

.inputInforChannel i {
    cursor: pointer;
    transition: all 0.5s ease;
}

.inputInforChannel i:hover {
    transition: all 0.5s ease;
    color: var(--user-color);
}

#open-change-password {
    margin-top: 10px;
    text-align: start;
}

body.modal-open {
    padding-right: 0px !important;
}

.modal.fade.show {
    padding-left: 0px;
}

.modal-content {
    margin-top: 100px;
}

.big-container .input-form {
    height: 40px;
    width: 100%;
    position: relative;
}

.big-container .input-form input {
    height: 100%;
    width: 100%;
    border: none;
    font-size: 17px;
    outline: none !important;
}


.input-form input:focus~label,
.input-form input:valid~label {
    transform: translateY(-20px);
    font-size: 15px;
    color: var(--user-color);
}

.input-form .underline.fix2:before {
    position: absolute;
    content: "";
    height: 100%;
    width: 100%;
    background: var(--user-color);
    transform: scaleX(0);
    transform-origin: center;
    transition: transform 0.3s ease;
}

.big-container .input-form label {
    position: absolute;
    bottom: 0px;
    left: 0;
    color: grey;
    pointer-events: none;
    transition: all 0.3s ease;
}

.input-form .underline {
    position: absolute;
    height: 2px;
    width: 100%;
    bottom: 0;
}

.input-form .underline:before {
    position: absolute;
    content: "";
    height: 100%;
    width: 100%;
    background: var(--user-color);
    transform: scaleX(0);
    transform-origin: center;
    transition: transform 0.3s ease;
}

.input-form input:focus~.underline:before,
.input-form input:valid~.underline:before {
    transform: scaleX(1);
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
    margin-top: 15px;
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

.under {
    position: relative;
    padding: 0px 0px;
}

.under::after {
    content: ' ';
    position: absolute;
    left: 0;
    bottom: -4px;
    width: 0;
    height: 2px;
    background: var(--user-color);
    transition: width 0.3s;
}

.under:hover::after {
    width: 100%;
    transition: width 0.3s;
}

#icon-eye {
    position: absolute;
    top: 10px;
    right: 0px;
    padding-left: 5px;
    cursor: pointer;
}

#input-password {
    padding-right: 26px;
}

.text-change {
    display: flex;
    flex-wrap: nowrap;
    align-items: center;
}

.col-12 {
    padding-left: 10px;
    padding-right: 10px;
}

@media screen and (min-width: 1201px) {
    .infor-channel {
        font-size: 15px;
    }

    .big-container {
        margin-top: 10px;
    }

    .text-change {
        padding: 5px 0px;
    }
}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    .infor-channel {
        font-size: 15px;
    }

    .big-container {
        margin-top: 10px;
    }

    .text-change {
        padding: 3px 0px;
    }
}

@media screen and (min-width: 769px) and (max-width: 992px) {
    .infor-channel {
        font-size: 13px;
    }

    .big-container {
        margin-top: 25px;
    }

    .text-change {
        padding: 3px 0px;
    }

    .col-12 {
        padding-left: 3px;
        padding-right: 3px;
    }

    .form-control {
        font-size: 12px !important;
    }

    .isHide {
        display: none;
    }

    .btn-pers {
        padding: 0.5em 1.5em;
    }

    .inputInforChannel label {
        max-width: 100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #open-change-password{
        margin-left: 40%;
        padding: 7px 25px
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {
    .infor-channel {
        font-size: 13px;
    }

    .big-container {
        margin-top: 25px;
    }

    .text-change {
        padding: 3px 0px;
    }

    .col-12 {
        padding-left: 3px;
        padding-right: 3px;
    }

    .form-control {
        font-size: 12px !important;
    }

    .isHide,
    .isHideTitle1 {
        display: none;
    }

    .btn-pers {
        padding: 0.5em 1.5em;
        font-size: 12px;
        margin-top: 5px;
    }

    .modal-content {
        width: 55%;
    }

    .modal-dialog {
        margin: auto 120px;
        margin-right: -200px;
    }

    .modal-header {
        font-size: 14px;
        padding: 6px;
    }

    .big-container .fa-regular {
        font-size: 20px;
    }

    .big-container .fa-solid {
        font-size: 13px;
    }

    .big-container .input-form input,
    .input-form input:focus~label,
    .input-form input:valid~label {
        font-size: 13px;
    }

    #open-change-password{
        margin-left: 30%;
        margin-top: 18px !important;
    }
}

@media screen and (min-width: 425px) and (max-width: 576px) {
    .infor-channel {
        font-size: 11px;
    }

    .big-container {
        margin-top: 15px;
    }

    .text-change {
        padding: 3px 0px;
    }

    .form-control {
        font-size: 11px !important;
        padding: 2px 5px !important;
        height: 25px !important;
    }

    .isHide,
    .isHideTitle1,
    .isHideTitle2 {
        display: none;
    }

    .btn-pers {
        padding: 0.5em 1.5em;
        font-size: 10px;
        margin-top: -2px;
    }

    .modal-content {
        width: 40%;
    }

    .modal-dialog {
        margin: auto 100px;
        margin-right: -200px;
    }

    .modal-header {
        font-size: 11px;
        padding: 5px;
    }

    .big-container .fa-regular {
        font-size: 15px;
    }

    .big-container .fa-solid {
        font-size: 10px;
    }

    .big-container .input-form input,
    .input-form input:focus~label,
    .input-form input:valid~label {
        font-size: 10px;
    }

    .big-container .input-form {
        height: 35x;
    }

    #input-text{
        padding-right: 12px;
    }
}

@media screen and (min-width: 375px) and (max-width: 424px) {
    .infor-channel {
        font-size: 10px;
    }

    .big-container {
        margin-top: 15px;
    }

    .text-change {
        padding: 3px 0px;
    }

    .form-control {
        font-size: 10px !important;
        padding: 2px 5px !important;
        height: 25px !important;
    }

    .isHide,
    .isHideTitle1,
    .isHideTitle2 {
        display: none;
    }

    .btn-pers {
        padding: 0.5em 1em;
        font-size: 10px;
        margin-top: -2px;
        margin-right: 1px;
    }

    .modal-content {
        width: 40%;
    }

    .modal-dialog {
        margin: auto 70px;
        margin-right: -200px;
    }

    .modal-header {
        font-size: 10px;
        padding: 5px;
    }

    .big-container .fa-regular {
        font-size: 13px;
    }

    .big-container .fa-solid {
        font-size: 10px;
    }

    .big-container .input-form input,
    .input-form input:focus~label,
    .input-form input:valid~label {
        font-size: 10px;
    }

    .big-container .input-form {
        height: 35x;
    }

    .modal-header .close{
        padding: 12px;
        margin: -12px;
    }

    #input-text{
        padding-right: 12px;
    }
}
</style>


<template>
    <div>
        <div id="big">
            <div class="bigContainer">
                <div class="modal fade" :id="modalId" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><strong><i class="fa-solid fa-user-plus"></i>
                                        {{ modalTitle }}</strong></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class="text-danger"><i
                                            class="fa-regular fa-circle-xmark"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="addOrUpdateMember()">
                                    <div class="input-form">
                                        <input required id="input-password" type="text" v-model="member.name">
                                        <div class="underline"></div><label><i class="fa-solid fa-signature"></i> Full
                                            Name</label>
                                    </div>
                                    <span v-if="errors.name" class="text-danger">{{ errors.name[0] }}<br></span>
                                    <br>
                                    <div class="input-form">
                                        <input required id="input-password" type="text" v-model="member.email">
                                        <div class="underline"></div><label><i class="fa-solid fa-envelope"></i>
                                            Email</label>
                                    </div>
                                    <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}<br></span>
                                    <br>
                                    <div class="input-form">
                                        <input required id="input-password" type="text" v-model="member.line_user_id">
                                        <div class="underline"></div><label><i class="fa-brands fa-line"></i> LINE User
                                            ID</label>
                                    </div>
                                    <span v-if="errors.line_user_id" class="text-danger">{{
                                        errors.line_user_id[0] }}<br></span>
                                    <br>
                                    <button type="submit" class="mt-4 btn-pers" id="login_button"><i class="fa-solid"
                                            :class="submitIcon"></i> {{ submitText }}</button>

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
const { emitEvent } = useEventBus();

export default {
    name: "AddOrUpdateMember",
    props: {
        memberSelected: Object
    },
    data() {
        return {
            member: {
                name: null,
                email: null,
                line_user_id: null,
            },
            errors: {
                name: null,
                email: null,
                line_user_id: null,
            }
        }
    },
    computed: {
        modalId() {
            return this.memberSelected ? "updateMember" : "addMember";
        },
        modalTitle() {
            return this.memberSelected ? "Update Account Member" : "Add Account Member";
        },
        submitIcon() {
            return this.memberSelected ? "fa-floppy-disk" : "fa-user-plus";
        },
        submitText() {
            return this.memberSelected ? "Update" : "Add";
        },
    },
    mounted() {
        if (this.memberSelected) {
            this.member.name = this.memberSelected.name;
            this.member.email = this.memberSelected.email;
            this.member.line_user_id = this.memberSelected.line_user_id;
        }
    },
    methods: {
        async addOrUpdateMember() {
            try {
                let closePW;
                if (this.memberSelected) {
                    const { messages } = await UserRequest.post('user/update-member/' + this.memberSelected.id, this.member, true);
                    emitEvent('eventSuccess', messages[0]);
                    for (let key in this.errors) this.errors[key] = null;
                    closePW = window.document.getElementById('updateMember');
                    closePW.click();
                    emitEvent('updateMemberSuccess', this.member);
                } else {
                    const { messages } = await UserRequest.post('user/add-member', this.member, true);
                    emitEvent('eventSuccess', messages[0]);
                    for (let key in this.errors) this.errors[key] = null;
                    closePW = window.document.getElementById('addMember');
                    closePW.click();
                    this.member = {
                        name: null,
                        email: null,
                        line_user_id: null,
                    };
                    emitEvent('eventRegetMembers', '');
                }
            } catch (error) {
                if (error.errors) this.errors = error.errors;
                else for (let key in this.errors) this.errors[key] = null;
                if (error.messages) emitEvent("eventError", error.messages[0]);
            }
        },
    },
    watch: {
        memberSelected: {
            immediate: true,
            handler(newVal) {
                if (newVal) {
                    this.member = Object.assign({}, newVal);
                }
            },
        },
    },
}
</script>

<style scoped>
.modal.fade.show {
    padding-left: 0px;
}

.modal-content {
    margin-top: 100px;
    border-radius: 10px;
}

.bigContainer .input-form {
    height: 40px;
    width: 100%;
    position: relative;
}

.bigContainer .input-form input {
    height: 100%;
    width: 100%;
    border: none;
    font-size: 17px;
    border-bottom: 2px solid silver;
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

.bigContainer .input-form label {
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

#input-password {
    padding-right: 26px;
}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    .modal-dialog {
        max-width: 400px;
    }

    .modal-content,
    .bigContainer .input-form input,
    .input-form input:focus~label,
    .input-form input:valid~label {
        font-size: 12px;
    }

    .modal-header{
        padding: 13px 15px 10px 15px;
    }
    .fa-regular {
        font-size: 20px;
    }
}

@media screen and (min-width: 769px) and (max-width: 992px) {
    .modal-dialog {
        max-width: 300px;
    }

    .modal-content,
    .bigContainer .input-form input,
    .input-form input:focus~label,
    .input-form input:valid~label{
        font-size: 11px;
    }

    .modal-header{
        padding: 13px 15px 10px 15px;
    }

    .fa-regular {
        font-size: 18px;
    }

    .btn-pers {
        font-size: 10px;
        margin-bottom: 10px !important;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {
    .modal-dialog {
        max-width: 250px;
    }

    .modal-content,
    .modal-header,
    .bigContainer .input-form input,
    .input-form input:focus~label,
    .input-form input:valid~label,
    .btn-pers {
        font-size: 10px;
    }

    .modal-header{
        padding: 8px 10px 5px 10px;
    }

    .fa-regular {
        font-size: 13px;
    }
}

@media screen and (min-width: 425px) and (max-width: 575px) {
    .modal-dialog {
        max-width: 200px;
        margin: 0 auto;
    }

    .modal-content,
    .modal-header,
    .bigContainer .input-form input,
    .input-form input:focus~label,
    .input-form input:valid~label,
    .btn-pers {
        font-size: 9px;
    }

    .modal-header{
        padding: 8px 10px 5px 10px;
    }

    .fa-regular {
        font-size: 13px;
    }
}


@media screen and (min-width: 375px) and (max-width: 424px) {
    .modal-dialog {
        max-width: 200px;
        margin: 0 auto;
    }

    .modal-content,
    .modal-header,
    .bigContainer .input-form input,
    .input-form input:focus~label,
    .input-form input:valid~label,
    .btn-pers {
        font-size: 9px;
    }

    .modal-header{
        padding: 8px 10px 5px 10px;
    }

    .fa-regular {
        font-size: 13px;
    }
}
</style>


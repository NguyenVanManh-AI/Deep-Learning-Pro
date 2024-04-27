<template>
    <div id="main">
        <div id="big">
            <div class="bigContainer">
                <button data-toggle="modal" data-target="#exampleModalChangePassword" id="openChangePassword" type="button"
                    class="mt-4 btn-pers"><i class="fa-solid fa-gear"></i> Change</button>
                <div class="modal fade" id="exampleModalChangePassword" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    <i class="fa-solid fa-key"></i> Form Change Password
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="changePassword()">
                                    <div class="input-form">
                                        <input required id="input-password" :type="isShow1 ? 'text' : 'password'"
                                            v-model="newPassword.current_password">
                                        <strong id="icon-eye"><i @click="isShow1 = !isShow1"
                                                :class="{ 'fa-solid': true, 'fa-eye': !isShow1, 'fa-eye-slash': isShow1 }"></i></strong>
                                        <div class="underline"></div><label><i class="fa-solid fa-lock"></i> Current
                                            Password</label>
                                    </div>
                                    <span v-if="errors.current_password" class="text-danger">{{ errors.current_password[0]
                                    }}<br></span>
                                    <br>
                                    <div class="input-form">
                                        <input required id="input-password" :type="isShow2 ? 'text' : 'password'"
                                            v-model="newPassword.new_password">
                                        <strong id="icon-eye"><i @click="isShow2 = !isShow2"
                                                :class="{ 'fa-solid': true, 'fa-eye': !isShow2, 'fa-eye-slash': isShow2 }"></i></strong>
                                        <div class="underline"></div><label><i class="fa-solid fa-lock"></i> New
                                            Password</label>
                                    </div>
                                    <span v-if="errors.new_password" class="text-danger">{{ errors.new_password[0]
                                    }}<br></span>
                                    <br>
                                    <div class="input-form">
                                        <input required id="input-password" :type="isShow3 ? 'text' : 'password'"
                                            v-model="newPassword.new_password_confirmation">
                                        <strong id="icon-eye"><i @click="isShow3 = !isShow3"
                                                :class="{ 'fa-solid': true, 'fa-eye': !isShow3, 'fa-eye-slash': isShow3 }"></i></strong>
                                        <div class="underline"></div><label><i class="fa-solid fa-lock"></i> New Password
                                            Confirmation </label>
                                    </div>
                                    <span v-if="errors.new_password_confirmation" class="text-danger">{{
                                        errors.new_password_confirmation[0] }}<br></span>
                                    <br>
                                    <button type="submit" class="mt-4 btn-pers" id="login_button"><i
                                            class="fa-solid fa-paper-plane"></i> Submit</button>
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
import UserRequest from '@/restful/UserRequest'
import useEventBus from '@/composables/useEventBus'
const { emitEvent } = useEventBus();

export default {
    name: "ChangePassword",
    data() {
        return {
            newPassword: {
                current_password: null,
                new_password: null,
                new_password_confirmation: null,
            },
            isShow1: false,
            isShow2: false,
            isShow3: false,
            errors: {
                current_password: null,
                new_password: null,
                new_password_confirmation: null,
            }
        }
    },
    methods: {
        changePassword: async function () {
            try {
                const { messages } = await UserRequest.post('user/change-password', this.newPassword, true);
                emitEvent('eventSuccess', messages[0]);
                for (let key in this.errors) this.errors[key] = null;
                var closePW = window.document.getElementById('exampleModalChangePassword');
                closePW.click();
                this.newPassword = {
                    current_password: null,
                    new_password: null,
                    new_password_confirmation: null,
                };
            }
            catch (error) {
                if (error.errors) this.errors = error.errors;
                else for (let key in this.errors) this.errors[key] = null;
                if (error.messages) emitEvent('eventError', error.messages[0]);
            }

        },
    },
}
</script>

<style scoped>
#openChangePassword {
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


@media screen and (min-width: 993px) and (max-width: 1200px) {
    .modal-dialog {
        max-width: 400px;
    }

    .modal-content,
    .bigContainer .input-form input,
    .input-form input:focus~label,
    .input-form input:valid~label {
        font-size: 14px;
    }

    .modal-header {
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
    .input-form input:valid~label {
        font-size: 12px;
    }

    .modal-header {
        padding: 13px 15px 10px 15px;
    }

    .fa-regular {
        font-size: 18px;
    }

    .btn-pers {
        font-size: 11px;
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
    .input-form input:valid~label {
        font-size: 12px;
    }

    .modal-header {
        padding: 8px 10px 5px 10px;
    }

    .fa-regular {
        font-size: 13px;
    }

    .btn-pers {
        font-size: 11px;
    }
}

@media screen and (min-width: 375px) and (max-width: 576px) {
    .modal-dialog {
        max-width: 220px;
        margin: 0 auto;
    }

    .modal-content,
    .modal-header,
    .bigContainer .input-form input,
    .input-form input:focus~label,
    .input-form input:valid~label {
        font-size: 11px;
    }

    .modal-header {
        padding: 8px 10px 5px 10px;
    }

    .fa-regular {
        font-size: 13px;
    }

    .btn-pers {
        font-size: 11px;
    }
}
</style>


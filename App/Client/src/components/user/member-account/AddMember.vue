<template>
    <div>
        <div id="big">
            <div class="bigContainer">
                <div class="modal fade" id="addMember" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><strong><i class="fa-solid fa-user-plus"></i>
                                        Add Account Member</strong></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class="text-danger"><i
                                            class="fa-regular fa-circle-xmark"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="addMember()">
                                    <div class="input-form">
                                        <input required id="inputPassword" type="text" v-model="member.name">
                                        <div class="underline"></div><label><i class="fa-solid fa-signature"></i> Full
                                            Name</label>
                                    </div>
                                    <span v-if="errors.name" class="text-danger">{{ errors.name[0] }}<br></span>
                                    <br>
                                    <div class="input-form">
                                        <input required id="inputPassword" type="text" v-model="member.email">
                                        <div class="underline"></div><label><i class="fa-solid fa-envelope"></i>
                                            Email</label>
                                    </div>
                                    <span v-if="errors.email" class="text-danger">{{ errors.email[0] }}<br></span>
                                    <br>
                                    <div class="input-form">
                                        <input required id="inputPassword" type="text" v-model="member.line_user_id">
                                        <div class="underline"></div><label><i class="fa-brands fa-line"></i> LINE User
                                            ID</label>
                                    </div>
                                    <span v-if="errors.line_user_id" class="text-danger">{{
                                        errors.line_user_id[0] }}<br></span>
                                    <br>
                                    <button type="submit" class="mt-4 btn-pers" id="login_button"><i
                                            class="fa-solid fa-user-plus"></i> Add</button>
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
    name: "AddMember",
    props: {

    },
    setup() {

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
    mounted() {
    },
    components: {
    },
    computed: {
    },
    methods: {
        addMember: async function () {
            try {
                const { messages } = await UserRequest.post('user/add-member', this.member, true);
                emitEvent('eventSuccess', messages[0]);
                for (let key in this.errors) this.errors[key] = null;
                var closePW = window.document.getElementById('addMember');
                closePW.click();
                this.member = {
                    name: null,
                    email: null,
                    line_user_id: null,
                };
                emitEvent('eventRegetMembers', '');
            }
            catch (error) {
                if (error.errors) this.errors = error.errors;
                else for (let key in this.errors) this.errors[key] = null;
                if (error.messages) emitEvent('eventError', error.messages[0]);
            }

        },
    },
    watch: {

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

#inputPassword {
    padding-right: 26px;
}
</style>


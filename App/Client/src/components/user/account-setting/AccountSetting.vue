<template>
    <div class="account_setting">
        <form class="col-12" @submit.prevent="updateProfile">
            <div class="row">
                <div class="colorTitle"><i class="fa-solid fa-pen-to-square"></i> Account Setting</div>
            </div>
            <div class="contact-info">
                <div class="col-5">
                    <div class="row colorTitle bigTitle"><span><i class="fa-solid fa-mobile-screen-button"></i> CONTACT
                            INFORMATION</span></div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><i class="fa-solid fa-signature"></i> Full Name</label><input required
                                v-model="user.name" placeholder="Full Name" type="text" class="form-control">
                            <span v-if="errors.name" class="text-danger">{{ errors.name[0] }}</span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><i class="fa-solid fa-cake-candles"></i> Date of birth</label><input
                                v-model="user.date_of_birth" type="date" required format="YYYY MM DD" class="form-control">
                            <span v-if="errors.date_of_birth" class="text-danger">{{ errors.date_of_birth[0] }}</span>

                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><i class="fa-solid fa-location-dot"></i> Address</label><input v-model="user.address"
                                placeholder="Address" type="text" required class="form-control">
                            <span v-if="errors.address" class="text-danger">{{ errors.address[0] }}</span>


                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><i class="fa-solid fa-venus-mars"></i> Gender</label>
                            <div class="groupCheckbox">
                                <div class="form-check form-check-inline">
                                    <input required v-model="user.gender" class="form-check-input" type="radio"
                                        name="inlineRadioOptions" id="male" value="1">
                                    <label style="color: #0085FF;" class="form-check-label" for="inlineRadio1">Men</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input required v-model="user.gender" class="form-check-input" type="radio"
                                        name="inlineRadioOptions" id="female" value="0">
                                    <label style="color: #0085FF;" class="form-check-label" for="inlineRadio2">Women</label>
                                </div>
                            </div>
                            <span v-if="errors.gender" class="text-danger">{{ errors.gender[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="row colorTitle bigTitle"><span><i class="fa-solid fa-mobile-screen-button"></i> CONTACT
                            INFORMATION</span></div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label for="formGroupExampleInput"><i class="fa-solid fa-phone"></i> Phone</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">+84</div>
                                </div>
                                <input type="text" required v-model="user.phone" class="form-control"
                                    placeholder="Number Phone">
                            </div>
                            <span v-if="errors.phone" class="text-danger">{{ errors.phone[0] }}</span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><i class="fa-solid fa-envelope"></i> Email</label><input v-model="user.email"
                                placeholder="Email" disabled type="email" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><i class="fa-brands fa-line"></i> LINE User ID</label>
                            <input class="form-control" disabled v-model="user.line_user_id" placeholder="Username"
                                type="text">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <label><i class="fa-solid fa-user-check"></i> Role</label>
                            <input class="form-control" disabled v-model="user.role" placeholder="Role" type="text">
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="avatarUser">
                        <div class="innerAvatar">
                            <label><i class="fa-solid fa-wand-magic-sparkles"></i> Avatar</label>
                            <div class="minAvatar">
                                <input class="input-file" type="file" @change="previewImage" accept="image/*"
                                    ref="fileInput" />
                                <span class="iconClound" v-if="previewImageSrc == null"><i
                                        class="fa-solid fa-cloud-arrow-up"></i></span>
                                <div v-if="previewImageSrc" class="box-preview">
                                    <img class="preview" :src="previewImageSrc" alt="Preview" />
                                    <img src="@/assets/error.png" @click="removeFile" class="close" alt="Remove">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <button type="submit" class="mt-4 btn-pers" id="login_button"><i class="fa-solid fa-floppy-disk"></i>
                    Save</button>
            </div>
        </form>
        <br>
        <hr>
        <div class="col-12 mt-3">
            <div class="row">
                <div class="colorTitle"><i class="fa-solid fa-key"></i> Change Password</div>
            </div>
            <div class="row">
                <ChangePassword></ChangePassword>
            </div>
        </div>
        <br>
    </div>
</template>

<script>
import UserRequest from '@/restful/UserRequest';
import useEventBus from '@/composables/useEventBus'
import ChangePassword from '@/components/user/account-setting/ChangePassword'
const { emitEvent } = useEventBus();

export default {
    name: "AccountSetting",
    data() {
        return {
            user: {
                id: null,
                email: null,
                role: null,
                line_user_id: null,
                channel_id: null,
                name: null,
                phone: null,
                avatar: null,
                address: null,
                gender: null,
                date_of_birth: null,
                is_block: null,
                is_delete: null,
                email_verified_at: null,
                created_at: null,
                updated_at: null,
                expires_in: null,
                token_type: null,
                access_token: null,
            },
            previewImageSrc: null,
            updateImage: false,
            errors: {
                name: null,
                address: null,
                date_of_birth: null,
                phone: null,
                gender: null,
            }
        }
    },
    setup() {
        document.title = "Account Setting | AI System";
    },
    async mounted() {
        this.user = JSON.parse(localStorage.getItem('user'));
        this.previewImageSrc = this.user.avatar;
        emitEvent('eventTitleHeader', 'Account Setting');
    },
    components: {
        ChangePassword,
    },
    methods: {
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewImageSrc = e.target.result;
                    this.user.avatar = file;
                    this.updateImage = true;
                };
                reader.readAsDataURL(file);
            } else this.removeFile();
        },
        removeFile: function () {
            this.previewImageSrc = null;
            this.user.avatar = null;
            this.$refs.fileInput.value = '';
            this.updateImage = false;
        },
        updateProfile: async function () { // (1)
            try {
                const formData = new FormData();
                var fields = ['name', 'address', 'date_of_birth', 'phone', 'gender'];
                if (this.updateImage) formData.append('avatar', this.user.avatar);
                for (var field of fields) formData.append(field, this.user[field]);
                const { data, messages } = await UserRequest.post('user/update', formData, true);
                this.user.name = data.name;
                this.user.address = data.address;
                this.user.date_of_birth = data.date_of_birth;
                this.user.phone = data.phone;
                this.user.gender = data.gender;
                this.user.avatar = data.avatar;
                this.previewImageSrc = this.user.avatar;
                this.updateImage = false;

                localStorage.setItem('user', JSON.stringify(this.user));
                emitEvent('eventSuccess', messages[0]);
                emitEvent('updateProfileUser', JSON.stringify(this.user));
            } catch (error) {
                if (error.errors) {
                    this.errors = error.errors;
                } else {
                    for (let key in this.errors) {
                        this.errors[key] = null;
                    }
                }
                if (error.messages) error.messages.forEach(message => { emitEvent('eventError', message); });

            }
        }

    },
}

</script>
<style scoped>
.account_setting {
    font-weight: bold;
}

.account_setting input {
    color: #0085FF;
    font-weight: bold;
}

.account_setting label {
    color: var(--user-color);
    margin-bottom: 1px;
}

.colorTitle {
    color: gray;
}

.contact-info {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
    padding: 0 15px
}

.bigTitle {
    margin-top: 10px;
    margin-bottom: 10px;
}

.groupCheckbox {
    border: 1px solid #ced4da;
    padding: 4px;
    padding-left: 10px;
    border-radius: 0.25rem;
    display: flex;
    align-content: center;
    align-items: center;
    height: 36px;
    background-color: rgba(255, 255, 255, 0.605);
}

.avatarUser {
    display: flex;
    align-items: center;
    align-content: center;
    height: 100%;
}

.innerAvatar {
    height: 50%;
}

.minAvatar {
    background-color: #e9ecef;
    position: relative;
    text-align: center;
    width: 170px;
    height: 170px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.5s ease;
}

.minAvatar .preview {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 6px;
    cursor: default;
}

.minAvatar:hover {
    transition: all 0.5s ease;
    background: #E8F5E9;
}

.input-file {
    opacity: 0;
    top: 0px;
    left: 0px;
    position: absolute;
    cursor: pointer;
    width: 150px;
    height: 150px;
}

.box-preview {
    position: relative;
}

.iconClound {
    cursor: pointer;
    font-size: 60px;
    color: var(--user-color);
}

.close {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 16px;
}

@media screen and (min-width: 1201px) {

    .col-5,
    .col-2 {
        padding-right: 30px;
    }
}

@media screen and (min-width: 993px) and (max-width: 1200px) {
    .minAvatar {
        width: 150px;
        height: 150px;
    }

    .minAvatar .preview {
        width: 130px;
        height: 130px;
    }

    .col-5,
    .col-2 {
        padding-right: 2%;
    }
}

@media screen and (min-width: 769px) and (max-width: 992px) {
    .innerContent>div {
        padding: 8px 11px;
        font-size: 15px;
    }

    .minAvatar {
        width: 110px;
        height: 110px;
    }

    .minAvatar .preview {
        width: 100px;
        height: 100px;
    }

    .contact-info .col-12 {
        padding-right: 20px;
        padding-left: 3px;
    }

    .col-5,
    .col-2 {
        padding-right: 2%;
        padding-left: 1%;
        font-size: 14px;
    }

    .form-control,
    .groupCheckbox,
    .input-group-text {
        font-size: 13px !important;
    }
    .btn-pers {
        font-size: 11px;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {
    .contact-info{
        flex-direction: column;
    }

    .innerContent>div {
        padding: 8px 11px;
        font-size: 13px;
    }

    .minAvatar {
        width: 150px;
        height: 150px;
    }

    .minAvatar .preview {
        width: 130px;
        height: 130px;
    }

    .contact-info .col-12 {
        padding-right: 20px;
        padding-left: 3px;
    }

    .col-5,
    .col-2 {
        padding: 0 5%;
        font-size: 13px;
        max-width: 100% !important;
    }

    .col-2{
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }

    .form-control,
    .groupCheckbox,
    .input-group-text {
        font-size: 13px !important;
    }

    .btn-pers {
        font-size: 11px;
    }
}

@media screen and (min-width: 375px) and (max-width: 576px) {
    .contact-info{
        flex-direction: column;
    }

    .innerContent>div {
        padding: 8px 11px;
        font-size: 12px;
    }

    .minAvatar {
        width: 150px;
        height: 150px;
    }

    .minAvatar .preview {
        width: 130px;
        height: 130px;
    }

    .contact-info .col-12 {
        padding-right: 20px;
        padding-left: 3px;
    }

    .col-5,
    .col-2 {
        padding: 0 5%;
        font-size: 13px;
        max-width: 100% !important;
    }

    .col-2{
        display: flex;
        justify-content: center;
        margin-top: 25px;
    }

    .form-control,
    .groupCheckbox,
    .input-group-text {
        font-size: 12px !important;
    }

    .btn-pers{
        font-size: 11px;
    }
}
</style>
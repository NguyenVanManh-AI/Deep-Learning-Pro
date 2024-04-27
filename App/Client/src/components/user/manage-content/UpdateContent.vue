<template>
    <div>
        <div id="big">
            <div class="bigContainer">
                <div class="modal fade" id="updateContent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><strong><i
                                            class="fa-solid fa-envelope-open-text"></i>
                                        Update Content Channel</strong></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class="text-danger"><i
                                            class="fa-regular fa-circle-xmark"></i></span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="updateContent()">
                                    <ul class="nav nav-tabs mainTab">
                                        <li @click="isTab = 'text'" class="nav-item  font-weight-bold">
                                            <a :class="{ 'nav-link': true, 'colorText': true, 'active': isTab == 'text' }"
                                                aria-current="page" href="#"><i class="fa-solid fa-quote-left"></i> Text</a>
                                        </li>
                                        <li @click="isTab = 'image'" class="nav-item font-weight-bold">
                                            <a :class="{ 'nav-link': true, 'colorImage': true, 'active': isTab == 'image' }"
                                                href="#"><i class="fa-solid fa-image"></i> Image</a>
                                        </li>
                                    </ul>
                                    <div class="loadContent" v-if="isTab == 'text'">
                                        <div class="col-12 mx-auto">
                                            <div class="input-form">
                                                <textarea v-model="dataText.content_data.text" type="text" required
                                                    class="form-control" id="exampleInputEmail1"
                                                    aria-describedby="emailHelp" placeholder="Content Text">
                                            </textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="loadContent" v-if="isTab == 'image'">
                                        <div class="minAvatar">
                                            <input required class="input-file" type="file" @change="previewImage"
                                                accept="image/*" ref="fileInput" />
                                            <span class="iconClound" v-if="previewImageSrc == null"><i
                                                    class="fa-solid fa-cloud-arrow-up"></i></span>
                                            <div v-if="previewImageSrc" class="box-preview">
                                                <img class="preview" :src="previewImageSrc" alt="Preview" />
                                                <img src="@/assets/error.png" @click="removeFile" class="close"
                                                    alt="Remove">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="mt-4 btn-pers" id="login_button"><i
                                            class="fa-solid fa-floppy-disk"></i> Update</button>
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
const { onEvent, emitEvent } = useEventBus();

export default {
    name: "UpdateContent",
    data() {
        return {
            isTab: 'text',
            previewImageSrc: null,
            contentSelected: {
                id: '',
                content_type: '',
                content_data: null,
                is_delete: null,
                creator_name: null,
                updater_name: null,
            },
            member: {
                name: null,
                email: null,
                line_user_id: null,
            },
            errors: {
                content_type: null,
                content_data_type: null,
                content_data_text: null,
                image_content: null,
            },
            dataText: {
                content_type: 'text',
                content_data: {
                    type: 'text',
                    text: null,
                },
            },
            dataImage: {
                content_type: 'image',
                image_content: null,
            },
        }
    },
    mounted() {
        onEvent('selectSimpleContent', (contentSelected) => {
            this.contentSelected = contentSelected;
            this.isTab = contentSelected.content_type;
            switch (contentSelected.content_type) {
                case 'text':
                    this.dataText.content_data.text = contentSelected.content_data.text;
                    break;
                case 'image':
                    this.previewImageSrc = contentSelected.content_data.originalContentUrl;
            }
        });
    },
    methods: {
        updateContent: async function () {
            try {
                var dataSubmit = null;
                if (this.isTab == 'text') dataSubmit = this.dataText;
                if (this.isTab == 'image') {
                    dataSubmit = new FormData();
                    dataSubmit.append('content_type', 'image');
                    dataSubmit.append('image_content', this.dataImage.image_content);
                }
                const { messages } = await UserRequest.post('content/update/' + this.contentSelected.id, dataSubmit, true);
                emitEvent('eventSuccess', messages[0]);
                for (let key in this.errors) this.errors[key] = null;
                var closePW = window.document.getElementById('updateContent');
                closePW.click();
                this.resetData();
                emitEvent('eventRegetcontents', '');
            }
            catch (error) {
                if (error.errors) this.errors = error.errors;
                else for (let key in this.errors) this.errors[key] = null;
                if (error.messages) emitEvent('eventError', error.messages[0]);
            }
        },
        resetData: function () {
            this.dataText = {
                content_type: 'text',
                content_data: {
                    type: 'text',
                    text: null,
                },
            };
            this.dataImage = {
                content_type: 'image',
                image_content: null,
            };
        },
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewImageSrc = e.target.result;
                    this.dataImage.image_content = file;
                };
                reader.readAsDataURL(file);
            } else this.removeFile();
        },
        removeFile: function () {
            this.previewImageSrc = null;
            this.dataImage.image_content = null;
            this.$refs.fileInput.value = '';
        },
    },
}
</script>

<style scoped>
.loadContent {
    border: 1px solid;
    border-color: #dee2e6;
    margin-top: -1px;
    padding: 10px;
    border-bottom-left-radius: 6px;
    border-bottom-right-radius: 6px;
}

.colorImage {
    color: var(--blue-color);
}

.colorText {
    color: var(--brown-color)
}

.mainTab>li:hover {
    transition: all 0.5s;
}

.mainTab>li {
    transition: all 0.5s;
}

.modal.fade.show {
    padding-left: 0px;
}

.modal-content {
    margin-top: 100px;
    border-radius: 10px;
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


.modal-dialog {
    max-width: 1000px;
}

.minAvatar {
    background-color: #e9ecef;
    position: relative;
    text-align: center;
    width: 100%;
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
    width: 100%;
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
img.close {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 16px;
}
</style>


<template>
    <div class="flower_setting">
        <div class="colorTitle"><i class="fa-solid fa-clover"></i> Multiple Flower Recognition</div>
        <div class="col-12">
            <div class="list-btn">
                <button @click="openFileInput" class="btn-pers-primary"><i class="fa-solid fa-upload"></i> Choose
                    Images</button>
                <input type="file" ref="fileInput" multiple style="display: none;" accept="image/*" @change="previewImages">
                <button @click="cancelSelection" class="btn-pers-cancel"><i class="fa-solid fa-rectangle-xmark"></i>
                    Cancel</button>
                <button @click="uploadImages" type="submit" class="btn-pers"><i class="fa-solid fa-paper-plane"></i>
                    Recognition</button>
                <span class="text-success font-weight-bold h5">{{ (timeProcessing/1000).toFixed(5) }} seconds</span>
                <select class="d-inline float-right form-control col-4">
                    <option>Vision Transformer (ViT)</option>
                    <option>VGG16</option>
                    <option>SeNet</option>
                    <option>AlexNet</option>
                    <option>GGNet</option>
                    <option>LeNet50</option>
                </select>
            </div>
            <div class="preview-container">
                <div v-for="(image, index) in imagePreviews" :key="index" class="preview-item">
                    <img :src="image.url" class="preview-image">
                    <!-- <div  class="d-flex justify-content-center"> -->
                    <div v-if="image.name == null" class="d-flex justify-content-center">
                        <flower-spinner class="loading-component" :animation-duration="2000" :size="30"
                            color="#06C755" />
                        <!-- <div class="spinner-border text-success " role="status">
                            <span class="sr-only">Loading...</span>
                        </div> -->
                    </div>
                    <p v-if="image.name != null" class="image-name">{{ truncatedImageName(image.name) }}</p>
                    <button @click="removeImage(index)" class="remove-button"><i
                            class="fa-solid fa-circle-xmark"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
const { emitEvent } = useEventBus();
import ModelRequest from '@/restful/ModelRequest';
import useEventBus from '@/composables/useEventBus';
import { FlowerSpinner } from 'epic-spinners';

export default {
    name: "MultipleFlowerRecognition",
    components: {
        FlowerSpinner
    },
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
            imagePreviews: [],
            timeProcessing: 0,
        }
    },
    setup() {
        document.title = "Multiple Flowers Recognition | AI System";
    },
    async mounted() {
        this.user = JSON.parse(localStorage.getItem('user'));
        emitEvent('eventTitleHeader', 'Multiple Flowers Recognition');
    },
    methods: {
        openFileInput() {
            this.$refs.fileInput.click();
        },
        previewImages() {
            const files = this.$refs.fileInput.files;
            if (files) {
                this.imagePreviews = [];
                for (let i = 0; i < files.length; i++) {
                    const fileReader = new FileReader();
                    fileReader.readAsDataURL(files[i]);
                    fileReader.onload = (e) => {
                        this.imagePreviews.push({ url: e.target.result, name: files[i].name, file: files[i] });
                    };
                }
            }
        },
        removeImage(index) {
            this.imagePreviews.splice(index, 1);
            this.clearFileInput();
        },
        cancelSelection() {
            this.imagePreviews = [];
            this.clearFileInput();
        },
        clearFileInput() {
            this.$refs.fileInput.value = '';
        },
        async uploadImages() {
            const startTime = performance.now();
            for (let i = 0; i < this.imagePreviews.length; i++) {
                this.imagePreviews[i].name = null;
            }
            for (let i = 0; i < this.imagePreviews.length; i++) {
                try {
                    const formData = new FormData();
                    formData.append('image_input', this.imagePreviews[i].file);
                    const { data, messages } = await ModelRequest.post('flower', formData, false);
                    console.log(data.flowers_name);
                    this.imagePreviews[i].name = data.flowers_name;
                    emitEvent('eventSuccess', messages[0]);
                } catch (error) {
                    console.error('Error uploading image:', error);
                }
            }
            const endTime = performance.now();
            this.timeProcessing = endTime - startTime; 
        },
        truncatedImageName(name) {
            if (name && name.length > 20) {
                return name.substring(0, 20) + '...';
            }
            return name;
        }
    },
}

</script>
<style scoped>
/* upload */
.loading-component {
    margin-top: 0px !important;
}

.list-btn {
    padding-top: 20px;
    padding-left: 100px;
    margin-bottom: 10px;
}

.list-btn button {
    position: initial !important;
}

.btn-pers {
    margin-left: 60px;
}

.preview-container {
    display: flex;
    flex-wrap: wrap;
}

.preview-item {
    padding: 6px;
    margin: auto;
    margin-top: 10px;
    border: 1px dashed black;
    position: relative;
}

.preview-image {
    margin: 10px;
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 6px;
}

.image-name {
    padding: 3px;
    text-align: center;
}

.remove-button {
    position: absolute;
    top: 15px;
    right: 20px;
    background-color: transparent;
    color: white;
    font-size: 26px;
    cursor: pointer;
    padding: 0;
    border-radius: 100px;
    transition: all 0.5s ease;
}

.remove-button:hover {
    color: red;
    transition: all 0.5s ease;
}

.cancel-button {
    margin-top: 10px;
}

/* upload */

.flower_setting {
    min-height: 80vh;
    font-weight: bold;
}

.colorTitle {
    color: gray;
}

@media screen and (min-width: 1201px) {

    .col-5,
    .col-2 {
        padding-right: 30px;
    }
}

@media screen and (min-width: 993px) and (max-width: 1200px) {

    .col-5,
    .col-2 {
        padding-right: 2%;
    }
}

@media screen and (min-width: 769px) and (max-width: 992px) {

    .col-5,
    .col-2 {
        padding-right: 2%;
        padding-left: 1%;
        font-size: 14px;
    }

    .btn-pers {
        font-size: 11px;
    }
}

@media screen and (min-width: 577px) and (max-width: 768px) {

    .col-5,
    .col-2 {
        padding: 0 5%;
        font-size: 13px;
        max-width: 100% !important;
    }

    .col-2 {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }

    .btn-pers {
        font-size: 11px;
    }
}

@media screen and (min-width: 375px) and (max-width: 576px) {

    .col-5,
    .col-2 {
        padding: 0 5%;
        font-size: 13px;
        max-width: 100% !important;
    }

    .col-2 {
        display: flex;
        justify-content: center;
        margin-top: 25px;
    }

    .btn-pers {
        font-size: 11px;
    }
}
</style>
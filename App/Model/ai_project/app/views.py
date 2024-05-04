from django.shortcuts import render
from rest_framework import viewsets, generics, status
from django.core.mail import send_mail
from rest_framework.pagination import PageNumberPagination
from rest_framework.viewsets import ModelViewSet
from django.views.decorators.csrf import csrf_exempt
from datetime import date, time, timedelta
import hashlib
from rest_framework.views import APIView
from rest_framework.response import Response
# import face_recognition
import datetime
from django.conf import settings
# import cv2
# from keras_vggface import utils
# import tensorflow as tf
# from tensorflow.keras.models import load_model
import io
from django.http import JsonResponse
import numpy as np
import shutil
import re
import string
import random
from django.db.models import Count
from rest_framework import generics
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
import calendar

# flower classification
import keras as keras
from rest_framework.parsers import MultiPartParser
from keras.models import load_model
import joblib
from keras.preprocessing.image import img_to_array, load_img
import cv2
from PIL import Image
from tensorflow.keras.models import Model

import efficientnet.tfkeras as efn
import efficientnet.tfkeras
import tensorflow.keras.models as M
import tensorflow.keras.layers as L
import tensorflow.keras.backend as K
import tensorflow.keras.callbacks as C

class FlowerClassification(APIView):
    parser_classes = [MultiPartParser]
    loaded_best_model = joblib.load('./models/best_logistic_regression_model.pkl')
    VGG16_base_model = load_model('./models/VGG16_base_model.h5')
    label_names = ['Bluebell', 'Buttercup', 'ColtsFoot', 'Cowslip', 'Crocus', 'Daffodil', 'Daisy', 'Dandelion', 'Fritillary', 'Iris', 'LilyValley', 'Pansy', 'Snowdrop', 'Sunflower', 'Tigerlily', 'Tulip', 'Windflower']

    def get(self, request):
        data = {'message': 'GET request received!'}
        return Response(data, status=status.HTTP_200_OK)
    
    def post(self, request):
        if 'image_input' not in request.data:
            return Response({'error': 'No image file found'}, status=status.HTTP_400_BAD_REQUEST)
        
        # Đọc hình ảnh từ request và chuyển đổi thành mảng numpy
        image_data = request.data['image_input']
        image_pil = Image.open(image_data)
        image_np = np.array(image_pil)

        # Resize hình ảnh sử dụng OpenCV
        image_resized = cv2.resize(image_np, (224, 224))

        # Tiếp tục xử lý hình ảnh như bình thường
        image = img_to_array(image_resized) 
        image = np.expand_dims(image, 0) 
        feature = self.VGG16_base_model.predict(image) 
        feature = feature.reshape((feature.shape[0], 512*7*7)) 
        pred = self.loaded_best_model.predict(feature) 
        response_data = {
            "data": {
                "flowers_name":self.label_names[pred[0]]
            },
            "messages": [
                "Successful flower identification !"
            ],
            "status": 200
        }
        return Response(response_data, status=status.HTTP_201_CREATED)

class Alzheimer_s(APIView):
    parser_classes = [MultiPartParser]
    loaded_model = joblib.load('models/best_model_TransferLearning_LogisticRegression_Alzheimer.pkl')
    classes = ['MildDemented', 'ModerateDemented', 'NonDemented', 'VeryMildDemented']
    best_model_ConvNeXt = load_model('models/ConvNeXt_model-022.keras')
    base_model = Model(inputs=best_model_ConvNeXt.input, outputs=best_model_ConvNeXt.layers[-3].output)

    def get(self, request):
        data = {'message': 'GET request received!'}
        return Response(data, status=status.HTTP_200_OK)
    
    def post(self, request):
        if 'image_input' not in request.data:
            return Response({'error': 'No image file found'}, status=status.HTTP_400_BAD_REQUEST)
        
        list_image = []
        image_data = request.data['image_input']
        image_pil = Image.open(image_data).convert("RGB") # Đọc ảnh theo kiểu RGB 
        image_np = np.array(image_pil)
        image_resized = cv2.resize(image_np, (32, 32))
        image_arr = img_to_array(image_resized) 
        image = np.expand_dims(image_arr, 0) 
        list_image.append(image)
        list_image = np.vstack(list_image)
        feature = self.base_model.predict(list_image) 
        feature = feature.reshape((feature.shape[0], 16*4*4)) 
        pred = self.loaded_model.predict(feature) 
        response_data = {
            "data": {
                "alzheimers_name":self.classes[pred[0]]
            },
            "messages": [
                "Successful Alzheimer identification !"
            ],
            "status": 200
        }
        return Response(response_data, status=status.HTTP_201_CREATED)

class WebMining(APIView):
    def post(self, request):
        id_user = request.data['id_user'] # POST 
        # id_user = request.data.get('id_user') # GET 
        recommend_products = [1,2,3,4,5,9,9,9,9,54,55,56,57]
        data = {
            'message': 'Get list product recommend success !',
            'recommend_products': recommend_products,
            'id_user': id_user,
        }
        return Response(data, status=status.HTTP_200_OK)
    
class ImageProcessingFlower(APIView):
    parser_classes = [MultiPartParser]
    GoogLeNet = load_model('./models/xla/final_best_gglenet_model.h5')
    # flower_index_dict = {1: 'pink primrose', 2: 'hard-leaved pocket orchid', 3: 'canterbury bells', 4: 'sweet pea', 5: 'english marigold', 6: 'tiger lily', 7: 'moon orchid', 8: 'bird of paradise', 9: 'monkshood', 10: 'globe thistle', 11: 'snapdragon', 12: "colt's foot", 13: 'king protea', 14: 'spear thistle', 15: 'yellow iris', 16: 'globe-flower', 17: 'purple coneflower', 18: 'peruvian lily', 19: 'balloon flower', 20: 'giant white arum lily', 21: 'fire lily', 22: 'pincushion flower', 23: 'fritillary', 24: 'red ginger', 25: 'grape hyacinth', 26: 'corn poppy', 27: 'prince of wales feathers', 28: 'stemless gentian', 29: 'artichoke', 30: 'sweet william', 31: 'carnation', 32: 'garden phlox', 33: 'love in the mist', 34: 'mexican aster', 35: 'alpine sea holly', 36: 'ruby-lipped cattleya', 37: 'cape flower', 38: 'great masterwort', 39: 'siam tulip', 40: 'lenten rose', 41: 'barbeton daisy', 42: 'daffodil', 43: 'sword lily', 44: 'poinsettia', 45: 'bolero deep blue', 46: 'wallflower', 47: 'marigold', 48: 'buttercup', 49: 'oxeye daisy', 50: 'common dandelion', 51: 'petunia', 52: 'wild pansy', 53: 'primula', 54: 'sunflower', 55: 'pelargonium', 56: 'bishop of llandaff', 57: 'gaura', 58: 'geranium', 59: 'orange dahlia', 60: 'pink-yellow dahlia', 61: 'cautleya spicata', 62: 'japanese anemone', 63: 'black-eyed susan', 64: 'silverbush', 65: 'californian poppy', 66: 'osteospermum', 67: 'spring crocus', 68: 'bearded iris', 69: 'windflower', 70: 'tree poppy', 71: 'gazania', 72: 'azalea', 73: 'water lily', 74: 'rose', 75: 'thorn apple', 76: 'morning glory', 77: 'passion flower', 78: 'lotus lotus', 79: 'toad lily', 80: 'anthurium', 81: 'frangipani', 82: 'clematis', 83: 'hibiscus', 84: 'columbine', 85: 'desert-rose', 86: 'tree mallow', 87: 'magnolia', 88: 'cyclamen', 89: 'watercress', 90: 'canna lily', 91: 'hippeastrum', 92: 'bee balm', 93: 'ball moss', 94: 'foxglove', 95: 'bougainvillea', 96: 'camellia', 97: 'mallow', 98: 'mexican petunia', 99: 'bromelia', 100: 'blanket flower', 101: 'trumpet creeper', 102: 'blackberry lily'}
    # flower_index_dict = {'37': 'great_masterwort', '39': 'lenten_rose', '42': 'sword_lily', '48': 'oxeye_daisy', '72': 'water_lily', '73': 'rose', '74': 'thorn_apple', '76': 'passion_flower', '77': 'lotus_lotus', '81': 'clematis', '83': 'columbine', '88': 'watercress', '89': 'canna_lily', '95': 'camellia', '96': 'mallow'}
    # {'37': 0, '39': 1, '42': 2, '48': 3, '72': 4, '73': 5, '74': 6, '76': 7, '77': 8, '81': 9, '83': 10, '88': 11, '89': 12, '95': 13, '96': 14}
    flower_names = ['Great masterwort', 'Lenten rose', 'Sword lily', 'Oxeye daisy', 'Water lily', 'Rose', 'Thorn apple', 'Passion flower', 'Lotus lotus', 'Clematis', 'Columbine', 'Watercress', 'Canna lily', 'Camellia', 'Mallow']

    def get(self, request):
        data = {'message': 'GET request received!'}
        return Response(data, status=status.HTTP_200_OK)

    def post(self, request):
        if 'image_input' not in request.data:
            return Response({'error': 'No image file found'}, status=status.HTTP_400_BAD_REQUEST)

        image_data = request.FILES['image_input']

        list_image = []
        nparr = np.fromstring(image_data.read(), np.uint8)
        img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        img = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        img = cv2.resize(img, (224, 224))
        img = img / 255.0
        list_image.append(img)
        list_image = np.array(list_image)

        predicts_label = self.GoogLeNet.predict(list_image) 
        predicts_label = np.argmax(predicts_label, axis=1)
        response_data = {
            "data": {
                "flowers_name":self.flower_names[predicts_label[0]]
            },
            "messages": [
                "Successful flower identification !"
            ],
            "status": 200
        }
        return Response(response_data, status=status.HTTP_201_CREATED)
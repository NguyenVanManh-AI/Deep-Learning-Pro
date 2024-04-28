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
    
    
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
    
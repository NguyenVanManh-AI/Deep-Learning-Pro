from django.urls import include, path
from rest_framework import routers
from .views import FlowerClassification, WebMining, Alzheimer_s, ImageProcessingFlower

router = routers.DefaultRouter()

urlpatterns = [
    path('', include(router.urls)),
    path('flower', FlowerClassification.as_view(), name='flower'), # GET, POST, PATCH, PUT,...
    path('image-processing-flower', ImageProcessingFlower.as_view(), name='image_processing_flower'), # GET, POST, PATCH, PUT,...
    path('alzheimers', Alzheimer_s.as_view(), name='alzheimers'), # GET, POST, PATCH, PUT,...
    path('recommend', WebMining.as_view(), name='recommend'),
    
]

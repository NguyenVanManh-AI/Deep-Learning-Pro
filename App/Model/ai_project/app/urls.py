from django.urls import include, path
from rest_framework import routers
from .views import FlowerClassification

router = routers.DefaultRouter()

urlpatterns = [
    path('', include(router.urls)),
    path('flower', FlowerClassification.as_view(), name='flower'), # GET, POST, PATCH, PUT,...
]
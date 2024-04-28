# Advanced artificial intelligence - Step by step .

AI Advanced - Bài tập lớn  
Hướng dẫn chạy ứng dụng
 
-	Mã nguồn ứng dụng : https://github.com/NguyenVanManh-AI/AI-Advanced  Mã nguồn gồm có :  
    + AI-Advanced/tree/main/Client : Chứa code Vuejs để xây dựng giao diện  
    + AI-Advanced/tree/main/Model : Chứa code để xây dựng API Model Django  
    + AI-Advanced/tree/main/Server : Chứa code Laravel để xây dựng API Service (refrences)  
 
-   Hướng dẫn chi tiết cách chạy ứng dụng  
    Clone source code : git clone git@github.com:NguyenVanManh-AI/AI-Advanced.git 

+ Model Django :  
    + cd into folder AI-Advanced\Model  
    + run : virtualenv myenv (Tạo môi trường ảo) 
    + run : pip install -r requirements.txt (sau đó ở môi trường ảo và cd ra lại folder ai_project)
    + cd into folder AI-Advanced\Model\ai_project 
    + run : python manage.py migrate 
    + run : py manage.py runserver 
    + Project API Django sẽ chạy với cổng : http://localhost:8000/  
 
+ Client Vuejs :  
    + cd into folder AI-Advanced\Client  
    + run : npm install  
    + run : npm serve 
    + Project Client Vuejs sẽ chạy với cổng : http://localhost:8080/  
 
+ Server Laravel (Refrences) :  
    + cd into folder AI-Advanced\Server 
    + run : composer install   
    + run : composer optimize:clear  
    + copy .env.example to .env  
    + run : php artisan migrate:refresh –seed  
    + run : php artisan serve  
    + Project Client Server sẽ chạy với cổng : http://localhost:8089/  
 



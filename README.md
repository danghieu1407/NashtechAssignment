<h1 align="center"> Bookworm </h1>

MỤC LỤC

### [1. Clone the repository ](#I)  

### [2. Configure .env ](#II)  

### [3. Generate app & migrate](#III)  

### [4. Run app](#IV)

<a name="I"></a>
### 1. Clone the repository:
```php

git clone https://github.com/danghieu1407/NashtechAssignment.git
    
```
<a name="II"></a>

### 2. Configure .env:

![configgithub](https://user-images.githubusercontent.com/74227813/178265148-fa5f6273-6a2f-4488-93b1-aca7c4794858.png)


<a name="III"></a>

### 3. Generate app & migrate 
```php
    php artisan key:generate
    php artisan migrate:fresh --seed
    
```
<a name="IV"></a>

### 4. Run app 
```php
    php artisan serve
    npm run watch
    
```

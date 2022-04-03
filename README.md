# 出欠管理システム
環境  
os: windows  
xmapp環境  
db: mysql  
git  
composer  
---  
---
システムインストール方法  
xamppのapatchとmysqlを起動しておく  
  
リポジトリーをダウンロード  
```git clone```  
Laravelをインストール  
```composer install```  
  
app_keyを生成する  
```php artisan key:generate```  
  
.envをコピーする  
```copy .env.example .env```  
  
xamppにlaravelというデータベースを作成する  
.envのDB_DATABASEをlaravelに変更する  
  
データベースに出退席管理システムのテーブルを作成する  
```php artisan migrate```  
  
テーブルにデータを作成する  
```php artisan db:seed```  
  
laravelを起動する  
```php artisan serve```  
  
ブラウザから出退席管理システムにアクセス  
```http://localhost:8000/```

管理者情報  
``name``: ``admin`` ``password``: ``1q1q``
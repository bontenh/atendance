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
```git clone https://github.com/bontenh/atendance.git```  
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
---
# 出欠管理システム概要
実際の支援所の学校名を使っています
利用者の出席と退席を管理するシステムです  
本校と本町二校という学校に通っています  
利用者は、出席(in)と退席のボタン(out)を押して、出欠情報を記録します  
管理者は、利用者の登録や削除、管理と利用者の出席情報の管理を行えます
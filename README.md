# coachtech　勤怠管理アプリ【仮】

## プロジェクト概要
COACHTECH の課題として作成した勤怠管理アプリです。
メール認証機能を実装しており、認証後のみ各種機能を利用できます。

---

## 使用技術（実行環境）
- PHP 8.1.33
- Laravel 8.83.8
- MySQL 8.0.x
- Docker / Docker Compose
- MailHog

---

## 環境構築

### Dockerビルド
1. `git clone git@github.com:o-emi/coachtech-flea-market-app.git`
2. `cd coachtech-flea-market-app`
3. Dockerアプリを立ち上げる
4. `docker-compose up -d --build`

---

### Laravel環境構築
1. `docker-compose exec php bash`
2. `composer install`
3. `.env` ファイルを作成します。

```bash
cp .env.example .env
```
または、新しく .env ファイルを作成し、
以下の環境変数を設定してください。


``` text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

4. アプリケーションキーの作成
``` bash
php artisan key:generate
```

5. マイグレーションの実行
``` bash
php artisan migrate
```

6. シーディングの実行
初期データを投入します。
``` bash
php artisan db:seed
```

7. シンボリックリンク作成
画像ファイルを公開するため、シンボリックリンクを作成します。
``` bash
php artisan storage:link
```

## ログイン情報

### 一般ユーザー（UserSeeder により作成）
- メールアドレス：test@example.com
- パスワード：password

---

## メール認証について

本アプリでは、新規登録後にメール認証を行わないとログインできない仕様となっています。

### 開発環境でのメール確認設定

開発環境では MailHog を使用しています
.env に以下を設定してください（MailHog 用）:

```
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="Flea Market App"
```

- 新規登録後、認証メールは MailHog に届きます
- メール内の「認証はこちら」リンクをクリックすると認証が完了します
- メール認証完了後、認証完了画面が表示されます。画面内の「商品一覧へ」ボタンを押すと、商品一覧画面へ遷移します。

---

## 認証が必要な機能

以下の機能はログインおよびメール認証後に利用可能です。

- 商品の出品
- マイページの閲覧・プロフィール編集
- 商品へのいいね
- コメント投稿
- 商品購入

---

## 初期データについて

本プロジェクトでは、商品データを Seeder により投入しています。

- ItemSeeder により商品一覧表示用のダミーデータが作成されます
- `is_sold` カラムにより Sold 表示の切り替えを確認できます

### 注意
`php artisan migrate:fresh --seed` を実行すると、
登録済みのユーザー情報・商品データはすべて削除されます。

---

## URL一覧
本アプリケーションで使用する各種 URL は以下の通りです。
### 開発環境
- アプリケーションURL
    http://localhost/
### データベース管理
- phpMyAdmin
    http://localhost:8080/
### メール確認
- MailHog
    http://localhost:8025/

---

## ER図
![ER図](docs/er.png)


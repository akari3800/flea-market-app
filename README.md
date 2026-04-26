# フリマアプリ（Laravel）

本アプリは、ユーザーが商品を出品・購入できるフリマアプリです。

# 環境構築

1. 下記コマンドでプロジェクトをクローンする
    ```
    git clone https://github.com/akari3800/flea-market-app.git
    ```
1. 下記コマンドでDockerをビルドする
    ```
    cd flea-market-app
    docker-compose up -d --build
    ```

# Laravel環境構築

```
- 以下の手順で環境構築を行う
  docker-compose exec php bash
  composer install
  cp .env.example .env　♯環境変数を適宜変更
  php artisan key:generate
  php artisan migrate
  php artisan db:seed
  php artisan storage:link
```

補足：Permission deniedエラーで画面が表示されない場合、コンテナ内で以下のコマンドを実行してディレクトリの権限を更新してください  
chmod -R 777 storage bootstrap/cache

# 環境変数

- Stripe決済機能を利用するため、.envに以下を設定してください

    STRIPE_KEY=ご自身のStripe公開キー  
    STRIPE_SECRET=ご自身のStripeシークレットキー

# 開発環境

- 商品一覧: http://localhost:8080/
- phpMyAdmin: http://localhost:8081

# 使用技術（実行環境）

- PHP 8.1.x
- Laravel　8.83.29
- Laravel Fortify
- jQuery
- MySQL 8.0.26
- nginx 1.21.1
- Stripe
- Mailhog

# ER図

![ER図](ER.drawio.png)

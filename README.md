# attendance-app

(勤怠アプリ)

## 環境構築

### リポジトリをクローン

1. git clone リポジトリURL
2. cd attendance-app

### DockerDesktopアプリを立ち上げる

```bash
docker-compose up -d --build
```

### Laravel環境構築

1.  `PHPコンテナへ入る` 
```bash 
`docker-compose exec php bash`
```
2. `srcディレクトリへ移動`
```bash
cd src
```
3. `composerをインストール`
```bash
composer install
```
4. .envファイルを作成
```bash
cp .env.example .env
```
5. .envファイルのDB設定を変更

#### DBの設定

.envファイルに以下を設定してください

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

6.アプリケーションキーの作成（srcディレクトリ内で実行）

```bash
php artisan key:generate
```

7.マイグレーション、シーダーの実行（srcディレクトリ内で実行）
```bash
php artisan migrate --seed
```
## テスト用アカウント

### 一般ユーザー1
email: test@test.com  
password: 12345678

### 一般ユーザー2
email: test@example.org  
password: aaa11111

### 管理者ユーザー

email: admin@test.com  
password: 12345678

## メール設定（メール認証機能）

本アプリではメール認証機能を実装しています。
メール送信には Mailtrap を使用しています。

動作確認する場合は .env に以下の設定を追加してください。
```bash
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=各自のMailtrapのユーザー名
MAIL_PASSWORD=各自のMailtrapのパスワード
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 使用技術

- PHP 8.1.34
- Laravel 8.83.29
- MariaDB 11.8.3
- Docker 28.3.2
- Nginx 1.21.1
- HTML
- CSS

## アプリ概要

勤怠管理を行うためのアプリです。
ユーザーは出勤・退勤・休憩打刻、勤怠修正申請を行うことができます。
管理者は勤怠一覧確認、修正承認、勤怠編集を行うことができます。

## 主な機能

### 一般ユーザー

- 会員登録
- ログイン
- メール認証
- 出勤打刻
- 退勤打刻
- 休憩開始 / 終了
- 勤怠一覧表示
- 勤怠詳細表示
- 勤怠修正申請

### 管理者

- ログイン
- スタッフ一覧表示
- 勤怠一覧表示
- 勤怠詳細編集
- 修正申請承認

## テスト

php artisan test

RegisterTest  
LoginTest

## ER図

![ER図](docs/attendance-system_ER.png)


## テーブル仕様書

[テーブル仕様書（Googleスプレッドシート)](https://docs.google.com/spreadsheets/d/16YzNu5Um7aeK7cP1eIuOQjQVzxr0YUX3OtXKSJSBuxE/edit?gid=1188247583#gid=1188247583)

## URL

- 開発環境：http://localhost
- phpMyAdmin：http://localhost:8080/

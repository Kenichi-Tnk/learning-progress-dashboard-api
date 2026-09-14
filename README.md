# learning-progress-dashboard-api

learning-progress-dashboard の API 側プロジェクトです。

## 技術スタック

- Laravel 10.50.2
- Laravel Sail
- MySQL 8.4 (Docker)

## 必要環境

- Docker
- Docker Compose
- PHP
- Composer

### インストール方法

このプロジェクトはDocker(Laravel Sail)上で動作するため、ローカルにPHPやMySQLを個別にインストールする必要はありません。ただし、`composer install`をローカルで実行するために、最低限のPHPとComposerが必要です。

- Docker DeskTop: https://www.docker.com/products/docker-desttop/の手順に従ってインストールしてください。
- PHP: [Homebrew](http://brew.sh/)を利用する場合

```bash
brew install php
```

- Composer: https://getcomposer.org/download/の手順に従うか、Homebrewを利用する場合

```bash
brew install composer
```

本プロジェクトは教材準拠のため、最新のPHP/Laravelバージョンとは異なります。実行環境(Dockerコンテナ内)はPHP 8.5系、コードの互換性としては`composer.json`にある`php: ^8.1`以上が前提です。ローカルのPHPバージョンが古くても、`composer install`と`sail`コマンドの実行にはそこまで大きな影響はありませんが、極端に古い場合(PHP 7系など)は別途PHPのアップデートを検討してください。

## セットアップ

1. 依存関係をインストール

```bash
composer install
```

2. 環境変数ファイルを作成

```bash
cp .env.example .env
```

3. Sail コンテナを起動

```bash
./vendor/bin/sail up -d
```

4. アプリケーションキーを生成

```bash
./vendor/bin/sail artisan key:generate
```

5. マイグレーションを実行

```bash
./vendor/bin/sail artisan migrate
```

## テスト

```bash
./vendor/bin/sail artisan test
```

## 環境変数

- `CORS_ALLOWED_ORIGINS`: フロントエンドのOriginをカンマ区切りで指定
- `CORS_SUPPORTS_CREDENTIALS`: 現在の Bearer token 方式では `false` で問題ありません
- `.env` はGit管理対象外です。値は各自の環境に合わせて設定してください

## 開発用コマンド

- コンテナ起動: ./vendor/bin/sail up -d
- コンテナ停止: ./vendor/bin/sail down
- ログ確認: ./vendor/bin/sail logs -f
- Artisan 実行: ./vendor/bin/sail artisan <command>

## API エンドポイント

### 認証不要

- GET /api/health
- POST /api/register
- POST /api/login

### 認証必須 (Bearer token)

- GET /api/user
- POST /api/logout
- GET /api/learning-progresses
- POST /api/learning-progresses
- GET /api/learning-progresses/{learning_progress}
- PUT /api/learning-progresses/{learning_progress}
- DELETE /api/learning-progresses/{learning_progress}

### 認証方式

Laravel Sanctum の Personal Access Token（Bearer token）方式です。
ログイン・登録に成功すると `token` が返却されるため、以降のリクエストには

```
Authorization: Bearer {token}
```

を付与してください。学習記録は `user_id` によってユーザーごとに分離されます。

### POST /api/register body example

上記の形式でリクエストを送ると、次の情報でユーザーが新規登録されます（実在のアカウントではなくサンプルです）。

```json
{
    "name": "テスト太郎",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### POST /api/login body example

```json
{
    "email": "test@example.com",
    "password": "password123"
}
```

### POST / PUT /api/learning-progresses body example

```json
{
    "title": "Laravel API学習",
    "category": "backend",
    "status": "in_progress",
    "memo": "Sailで開発開始",
    "started_at": "2026-07-22 10:00:00",
    "completed_at": null
}
```

## 補足

- 本リポジトリは教材準拠として Laravel 10 系で作成しています。
- API は http://localhost で確認できます。

## API 契約

フロントとバックエンドの最小契約は次を参照してください。

- [API_CONTRACT.md](API_CONTRACT.md)

## 開発チェックリスト

API 実装前、フロント結合前、障害切り分け前に必ず以下を確認してください。

- [BACKEND_API_READY_CHECKLIST.md](BACKEND_API_READY_CHECKLIST.md)

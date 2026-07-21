# learning-progress-dashboard-api

learning-progress-dashboard の API 側プロジェクトです。

## Tech Stack

- Laravel 10.50.2
- Laravel Sail
- MySQL 8.4 (Docker)

## Requirements

- Docker
- Docker Compose
- PHP
- Composer

## Setup

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

## Development Commands

- コンテナ起動: ./vendor/bin/sail up -d
- コンテナ停止: ./vendor/bin/sail down
- ログ確認: ./vendor/bin/sail logs -f
- Artisan 実行: ./vendor/bin/sail artisan <command>

## Notes

- 本リポジトリは教材準拠として Laravel 10 系で作成しています。
- API は http://localhost で確認できます。

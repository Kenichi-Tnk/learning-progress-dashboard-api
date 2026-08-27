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

## API Endpoints

- GET /api/health
- GET /api/learning-progresses
- POST /api/learning-progresses
- GET /api/learning-progresses/{learning_progress}
- PUT /api/learning-progresses/{learning_progress}
- DELETE /api/learning-progresses/{learning_progress}

### POST / PUT body example

{
	"title": "Laravel API学習",
	"category": "backend",
	"status": "in_progress",
	"memo": "Sailで開発開始",
	"started_at": "2026-07-22 10:00:00",
	"completed_at": null
}

## Notes

- 本リポジトリは教材準拠として Laravel 10 系で作成しています。
- API は http://localhost で確認できます。

## API Contract

フロントとバックエンドの最小契約は次を参照してください。

- [API_CONTRACT.md](API_CONTRACT.md)

## Development Checklist

API 実装前、フロント結合前、障害切り分け前に必ず以下を確認してください。

- [BACKEND_API_READY_CHECKLIST.md](BACKEND_API_READY_CHECKLIST.md)

# API Contract (1 Page)

この文書は、フロント側の実装とバックエンドの最小契約をまとめたものです。過剰な仕様は省き、実装・確認に必要な項目だけを記載します。

## 1. 前提

- Base URL: `/api/learning-progresses`
- Health check: `/api/health`
- Content-Type: `application/json`
- CORS: `http://localhost:3000` を許可
- 既定状態: `status = "in_progress"`

## 2. フロント側の利用形

```ts
{
  id: '1',
  createdAt: '2026-07-06T10:00:00.000000Z',
  date: '2026-07-06',
  title: 'React学習',
  minutes: 45,
  category: 'frontend',
  note: 'hooksを学習'
}
```

- `date` = `started_at.slice(0, 10)`
- `minutes` = `memo` の `[minutes:N]` から抽出
- `note` = `[minutes:N]` を除いた本文
- `id` = `String(id)`
- `createdAt` = `created_at`
- `category` = そのまま使用

## 3. API

### GET /api/health

- 200 OK
- Response:

```json
{ "status": "ok" }
```

### GET /api/learning-progresses

- 200 OK
- Response: 学習記録配列

```json
[
    {
        "id": 1,
        "title": "React学習",
        "category": "frontend",
        "status": "in_progress",
        "memo": "[minutes:45]hooksを学習",
        "started_at": "2026-07-06 00:00:00",
        "completed_at": null,
        "created_at": "2026-07-06T10:00:00.000000Z",
        "updated_at": "2026-07-06T10:00:00.000000Z"
    }
]
```

### POST /api/learning-progresses

- 201 Created
- Request:

```json
{
    "title": "React学習",
    "category": "frontend",
    "status": "in_progress",
    "memo": "[minutes:45]hooksを学習",
    "started_at": "2026-07-06 00:00:00"
}
```

### PUT /api/learning-progresses/{id}

- 200 OK
- Request: POST と同じ

### DELETE /api/learning-progresses/{id}

- 204 No Content

## 4. ルール

- `category` は `frontend | backend | algorithm | infra | other`
- `memo` は `[minutes:N]` 形式で保存
- `started_at` は `YYYY-MM-DD HH:mm:ss`
- `created_at` / `updated_at` は ISO 8601
- `status` は現時点では `in_progress` を固定

## 5. バリデーション

- `title`: 2文字以上
- `category`: 上記 enum の値
- `memo`: `[minutes:N]` 形式を前提
- `started_at`: 文字列で必須

## 6. エラー

- 422: バリデーションエラー
- 404: 対象IDが存在しない
- 500: サーバー内部エラー

例:

```json
{
    "message": "Validation failed",
    "errors": {
        "title": ["タイトルは 2 文字以上で入力してください。"]
    }
}
```

## 7. 実装メモ

- フロント側は `response.ok` で成功判定する
- 表示文言はフロント側で固定し、API はデータ返却に集中する
- これで `memory` モードと `http` モードの差分を最小限にできる

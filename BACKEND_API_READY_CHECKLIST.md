# Backend API Ready Checklist

このチェックリストは、learning-progress-dashboard-api で
「API 実装前」「フロント結合前」「不具合切り分け前」に毎回確認するためのものです。

目的は 2 つです。

- API 側の不備を、フロント結合前に潰すこと
- 不具合発生時に、フロント起因か API 起因かを早く切り分けること

## 使い方

- 上から順に確認する
- 1 つでも未完了なら、次の工程に進まない
- 新しい障害が出たら、このファイルに項目を追加する
- このファイルは「下書き兼運用ルール」として育てる

## まず一緒に埋める項目

このプロジェクト用に、最初にここを更新する。

- Frontend Origin: `http://localhost:3000`
- API Origin: `http://localhost`
- API Health URL: `GET /api/health`
- Main Resource URL: `GET /api/learning-progresses`
- DB Service Name: `mysql`
- App Service Name: `laravel.test`

## 0. 実行ディレクトリ確認

- [ ] `pwd` が API プロジェクトのルートになっている
- [ ] `artisan`, `compose.yaml`, `composer.json` が見える
- [ ] 別プロジェクトで `sail` や `artisan` を実行していない

確認コマンド例:

```bash
pwd
ls
```

## 1. コンテナ・プロセス起動確認

- [ ] Docker Desktop が起動している
- [ ] `docker info` が通る
- [ ] Sail コンテナが起動している
- [ ] `laravel.test` コンテナが `Up` になっている
- [ ] `mysql` コンテナが `healthy` になっている
- [ ] ポート公開が期待どおりである

確認コマンド例:

```bash
docker info
./vendor/bin/sail ps
docker ps --format 'table {{.Names}}\t{{.Status}}\t{{.Ports}}'
```

## 2. 環境変数と接続先確認

- [ ] `.env` が存在する
- [ ] `APP_URL` が想定値である
- [ ] `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` が正しい
- [ ] `DB_HOST` が Docker / Sail 構成に合っている
- [ ] フロントからアクセスさせる Origin を把握している

特に確認すること:

- [ ] `DB_HOST=mysql` のように、コンテナ名ベースで接続する前提を理解している
- [ ] ホスト OS からの接続設定と、コンテナ内からの接続設定を混同していない

## 3. データベース準備

- [ ] migration が実行済み
- [ ] 対象テーブルが存在する
- [ ] 必要なら seed データを投入済み
- [ ] 開発確認に使う最低 1 件以上のデータがある

確認コマンド例:

```bash
./vendor/bin/sail artisan migrate:status
./vendor/bin/sail artisan tinker
```

Tinker で確認したいこと:

- [ ] `LearningProgress::count()` が想定件数を返す
- [ ] 登録済みレコードの主要カラムが正しい

## 4. API 契約確認

- [ ] 一覧取得のレスポンス形式を決めている
- [ ] 作成成功時のレスポンス形式を決めている
- [ ] 更新成功時のレスポンス形式を決めている
- [ ] 削除成功時のレスポンス形式を決めている
- [ ] バリデーション失敗時のレスポンス形式を決めている
- [ ] HTTP ステータスコードを固定している

最低限の契約:

- [ ] `GET /api/health` -> `200`
- [ ] `GET /api/learning-progresses` -> `200`
- [ ] `POST /api/learning-progresses` -> `201`
- [ ] `PUT /api/learning-progresses/{id}` -> `200`
- [ ] `DELETE /api/learning-progresses/{id}` -> `204`
- [ ] バリデーション失敗 -> `422`

## 5. フロント結合を意識したレスポンス確認

- [ ] JSON のキー名がフロント期待値と一致している
- [ ] `null` を返す項目がフロントで扱える形になっている
- [ ] 日付フォーマットがフロント変換ロジックと合っている
- [ ] `memo` や `category` の内容がフロント仕様と矛盾していない
- [ ] ソート順がフロント期待値と合っている

今回のような切り分けで重要な観点:

- [ ] API 単体で `curl` するとデータが返る
- [ ] 返る JSON を見て、フロント表示不能な形になっていない

## 6. CORS 確認

- [ ] `http://localhost:3000` を許可している
- [ ] 必要なヘッダーが返る
- [ ] ブラウザからの GET が失敗しない
- [ ] 認証や Cookie を使うなら credentials 方針を決めている

確認コマンド例:

```bash
curl -i -H 'Origin: http://localhost:3000' http://localhost/api/health
curl -i -H 'Origin: http://localhost:3000' http://localhost/api/learning-progresses
```

## 7. ルーティングと実装接続確認

- [ ] `routes/api.php` に対象ルートがある
- [ ] コントローラが正しいリソースを返している
- [ ] モデルの `$fillable` が不足していない
- [ ] キャスト設定が必要なら定義している
- [ ] Request クラスのバリデーションが正しい

切り分け時の確認:

- [ ] ルートはあるが中身が未実装、という状態で止まっていない
- [ ] DB に保存されたがレスポンスへ反映されていない、という状態を見逃していない

## 8. API テスト確認

- [ ] Feature テストで主要エンドポイントを確認している
- [ ] バリデーション失敗ケースを確認している
- [ ] 一覧取得が実データを返すことを確認している
- [ ] 作成後に DB へ保存されることを確認している
- [ ] 更新後に DB が更新されることを確認している
- [ ] 削除後に DB から消えることを確認している

確認コマンド例:

```bash
./vendor/bin/sail artisan test
```

## 9. フロントと結合する前の最終ゲート

以下がすべて完了していれば、フロントとつなぐ準備ができている。

- [ ] コンテナ起動 OK
- [ ] DB 接続 OK
- [ ] migration OK
- [ ] `curl` で API 取得 OK
- [ ] CORS OK
- [ ] API テスト OK
- [ ] フロントが期待する JSON と一致

## 10. 障害発生時の切り分け順

不具合が出たら、次の順番で見る。

1. Docker / Sail は起動しているか
2. MySQL は healthy か
3. migration は終わっているか
4. `curl` で API は返るか
5. CORS は通っているか
6. JSON 形式は想定どおりか
7. ここまで OK なら、フロント側を疑う

## 今後このファイルに追加したいこと

- [ ] このプロジェクト専用の `curl` 実例
- [ ] バリデーションレスポンスの正本サンプル
- [ ] seed 手順
- [ ] よくある失敗例と対処

README.md（サンプル）
# 簡易ECサイト（学習用ポートフォリオ）

## 📌 プロジェクト概要
PHPとMySQLを用いて作成した学習用の簡易ECサイトです。  
Webアプリ開発の基礎として、データベース設計・ユーザー管理・商品管理・カート機能・注文処理などを実装しました。  

## 🎯 制作の目的
- Webアプリケーション開発の流れを学習するため  
- データベースを利用したCRUD処理の理解を深めるため  
- フロントエンドとバックエンドを連携させた基本的な開発経験を積むため  

## 🛠 使用技術
- **言語**: PHP, HTML, CSS, JavaScript  
- **データベース**: MySQL  
- **その他**: Apache（XAMPP環境で動作確認）  

## ✨ 実装機能
- ユーザー登録 / ログイン機能  
- 商品一覧表示  
- カートに追加 / 削除  
- 注文処理（合計金額計算）  
- 管理者用の商品追加 / 更新 / 削除機能  

## 🗂 データベース設計
- `users`（ユーザー情報）  
- `products`（商品情報）  
- `orders`（注文情報）  
- `order_items`（注文ごとの商品明細）  

※ER図は `docs/er-diagram.png` に掲載（任意）  

## 🚀 セットアップ方法
1. このリポジトリをクローン  
   ```bash
   git clone https://github.com/yourname/simple-ecsite.git


MySQLにデータベースを作成し、db/schema.sql をインポート

config.sample.php をコピーして config.php を作成し、DB接続情報を入力

// config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'ecsite_db');


Apache + PHP 環境（XAMPPなど）で public/ ディレクトリをルートに設定

ブラウザでアクセスして動作確認

📷 スクリーンショット（任意）

商品一覧ページ

カートページ

注文完了画面

📌 備考

学習目的で制作したため、セキュリティやエラーハンドリングは簡略化しています。

実務ではCSRF対策・バリデーション強化などが必要です。

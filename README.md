<h1>模擬案件_勤怠登録アプリ</h1>
<h2>環境構築</h2>
<h3>Dockerビルド</h3>
<ol>
  <li>git clone git@github.com:s-kiso/time-clock-system.git</li>
  <li>docker-compose up -d --build</li>
</ol>

<h3>Laravel環境構築</h3>
<ol>
  <li>docker-compose exec php bash</li>
  <li>composer install</li>
  <li>cp .env.example .env</li>
  <li>作成したenvファイル内を下記の通り修正
    <ul>
      <li>DB_HOST=mysql</li>
      <li>DB_DATABASE=laravel_db</li>
      <li>DB_USERNAME=laravel_user</li>
      <li>DB_PASSWORD=laravel_pass</li>
    </ul>
  </li>
  <li>php artisan key:generate</li>
  <li>php artisan migrate</li>
  <li>php artisan db:seed</li>
  <li>php artisan storage:link</li>
</ol>

<h3>メール認証</h3>
<p>mailtrapというツールを使用しています。
以下のリンクから会員登録をしてください。　
https://mailtrap.io/

メールボックスのIntegrationsから 「laravel 7.x and 8.x」を選択し、　
.envファイルのMAIL_MAILERからMAIL_ENCRYPTIONまでの項目をコピー＆ペーストしてください。　
MAIL_FROM_ADDRESSは任意のメールアドレスを入力してください。　</p>

<h3>テストアカウント</h3>
<h4>管理者ユーザ</h4>
<h4>一般ユーザ</h4>

<h3>使用技術</h3>
<ul>
  <li>Laravel Framework 8.83.8</li>
  <li>MySQL 8.0.26</li>
</ul>
<h3>ER図</h3>

<img width="1051" height="621" alt="Image" src="https://github.com/user-attachments/assets/9a7c2cd7-e71f-442f-8394-6f5e0849dd9b" />

<h3>URL</h3>
<ul>
  <li>開発環境: http://localhost/</li>
  <li>phpMyAdmin: http://localhost:8080/</li>
</ul>

<h3>備考</h3>
<ul>
  <li>会員登録・ログイン機能以外はほぼできていないです</li>
</ul>

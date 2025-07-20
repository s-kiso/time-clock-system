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
  <li>作成した.envファイル内を下記の通り修正
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
<p>mailhogを使用しています。.envファイルを以下の通り修正してください。</p>
<ul>
  <li>MAIL_HOSt=mailhog</li>
  <li>MAIL_PORT=1025</li>
  <li>MAIL_FROM_ADDRESSに任意のアドレスを入力</li>
</ul>
  
<h3>テストアカウント</h3>
<p>いずれのユーザーもメール認証実施済み</p>
<h4>管理者ユーザ</h4>
<ul>
  <li>name: 管理者ユーザー1</li>
  <li>email: admin1@example.com</li>
  <li>password: password</li>
</ul>

<h4>一般ユーザ</h4>
<ol>
  <li>管理者ユーザー1</li>
  <ul>
    <li>name: 管理者ユーザー1</li>
    <li>email: admin1@example.com</li>
    <li>password: password</li>
  </ul>
  <li>一般ユーザー1</li>
  <ul>
    <li>name: 一般ユーザー1</li>
    <li>email: general1@example.com</li>
    <li>password: password</li>
    <li>10件の出退勤データ（6月2件、7月8件）、9件の休憩データあり</li>
  </ul>
  <li>一般ユーザー2</li>
  <ul>
    <li>name: 一般ユーザー2</li>
    <li>email: general2@example.com</li>
    <li>password: password</li>
    <li>出退勤・休憩データなし</li>
  </ul>
</ol>


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
  <li>mailhog: http://localhost:8025/</li>
</ul>

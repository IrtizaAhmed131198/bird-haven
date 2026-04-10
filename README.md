<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Bird Haven — Avian Ecommerce</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 40px;">

  <h1>🐦 Bird Haven — Avian Ecommerce</h1>

  <p>
    A full-featured <strong>Birds Ecommerce Web Application</strong> built with
    <strong>Laravel</strong>, offering a smooth buying experience for exotic and domestic birds.
  </p>

  <h2>🧰 Tech Stack</h2>
  <ul>
    <li><strong>Backend:</strong> Laravel 10, PHP 8.2</li>
    <li><strong>Frontend:</strong> Blade, Tailwind CSS v4, Alpine.js</li>
    <li><strong>Database:</strong> MySQL</li>
    <li><strong>Assets:</strong> Vite</li>
  </ul>

  <h2>✨ Features</h2>
  <ul>
    <li>Browse & filter birds by category, color, price, wingspan</li>
    <li>Detailed product pages with care guide PDF download</li>
    <li>Shopping cart & checkout flow</li>
    <li>Order management & shipment tracking</li>
    <li>Wishlist & user profiles</li>
    <li>Two-factor authentication</li>
    <li>Newsletter subscription</li>
    <li>Admin panel (Manage birds, categories, orders, users, reviews, settings)</li>
    <li>CMS pages (About, Policies)</li>
  </ul>

  <h2>🚀 Local Setup</h2>

  <h3>1️⃣ Clone Repository</h3>
  <pre><code>git clone https://github.com/your-username/bird-haven.git
cd bird-haven</code></pre>

  <h3>2️⃣ Install Backend Dependencies</h3>
  <pre><code>cp .env.example .env
composer install
php artisan key:generate</code></pre>

  <h3>3️⃣ Configure Database</h3>
  <pre><code>DB_DATABASE=bird_haven
DB_USERNAME=root
DB_PASSWORD=</code></pre>

  <pre><code>php artisan migrate --seed</code></pre>

  <h3>4️⃣ Install Frontend Dependencies</h3>
  <pre><code>npm install
npm run dev</code></pre>

  <h3>5️⃣ Start Development Server</h3>
  <pre><code>php artisan serve</code></pre>

  <p>Visit the application:</p>
  <ul>
    <li>http://localhost:8000</li>
    <li>http://localhost/bird-haven/public</li>
  </ul>

  <h2>🛠️ Development Commands</h2>
  <pre><code>npm run dev
php artisan optimize:clear</code></pre>

  <h2>📦 Production Build</h2>
  <pre><code>npm run build
php artisan optimize</code></pre>

  <h2>🤝 Contributing</h2>
  <p>Pull requests are welcome! For major changes, please open an issue first.</p>

  <h2>📄 License</h2>
  <p>This project is licensed under the <strong>MIT License</strong>.</p>

</body>
</html>

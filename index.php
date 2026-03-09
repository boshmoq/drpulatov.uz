<?php
/* ========================================================================
  PHP QISMI: Telegramga xabar yuborish va API xizmati
  ========================================================================
*/

// Agar bu POST so'rovi bo'lsa (forma yuborilganda), Telegramga jo'natadi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");

    define("BOT_TOKEN", "8517346899:AAEeB1YUebeCuzJjBGmR5p3LOGgQARy1I3c");
    $RECIPIENTS = [1227352039, 6155982488, 7897913022];

    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || !isset($input['name'], $input['phone'])) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid request"]);
        exit;
    }

    $name = htmlspecialchars(trim($input['name']));
    $phone = trim($input['phone']);
    $message = htmlspecialchars($input['message'] ?? "Без комментария");

    // Telefon raqamini tozalash
    $clean_phone = str_replace([" ", "-", "(", ")"], "", $phone);
    if ($clean_phone[0] !== "+") $clean_phone = "+" . $clean_phone;

    $text = "🆕 <b>НОВАЯ ЗАЯВКА С САЙТА</b>\n"
          . "➖➖➖➖➖➖➖➖➖➖\n"
          . "👤 <b>Имя:</b> {$name}\n"
          . "💬 <b>Жалоба:</b> {$message}\n"
          . "➖➖➖➖➖➖➖➖➖➖\n"
          . "📞 <b>Клиент:</b> <a href='tel:{$clean_phone}'>{$clean_phone}</a>";

    $count = 0;
    foreach ($RECIPIENTS as $chat_id) {
        $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";
        $data = [
            "chat_id" => $chat_id,
            "text" => $text,
            "parse_mode" => "HTML"
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
        ]);
        if (curl_exec($ch)) $count++;
        curl_close($ch);
    }

    echo json_encode(["status" => "success", "message" => "Sent to {$count}"]);
    exit; // API so'rovi tugadi, pastdagi HTML ko'rsatilmaydi
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dr. Pulatov | Стоматология</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #2c3e50;
            --accent-bg: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            overflow-x: hidden;
            padding-top: 70px;
        }

        h1, h2, h3 {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            color: var(--secondary-color);
        }

        .navbar {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            background: white;
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }
        
        .nav-logo {
            height: 60px;
            object-fit: fit;
        }

        .phone-btn {
            font-weight: 600;
            border-radius: 50px;
            padding: 8px 25px;
        }

        .hero-section {
            padding: 120px 0;
            background: linear-gradient(135deg, #eef2f3 0%, #ffffff 100%);
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .hero-img {
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            width: 100%;
            height: 400px;
        }

        .feature-card {
            border: none;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.19);
            transition: transform 0.3s ease;
            height: 100%;
        }

        .feature-card:hover { transform: translateY(-5px); }

        .icon-box {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .appointment-section {
            background-color: var(--primary-color);
            color: white;
            padding: 80px 0;
            position: relative;
        }

        .appointment-section h2 { color: white; }

        .form-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            color: #333;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
        }

        footer {
            background: var(--secondary-color);
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .spinner-border { display: none; }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><img class="nav-logo" src="./logo.png"></a>
            <div class="ms-auto d-flex align-items-center">
                <a href="tel:+998950414488" class="btn btn-outline-primary phone-btn">
                    <i class="fas fa-phone-alt me-2"></i>+998 (95) 041-44-88
                </a>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center flex-column-reverse flex-lg-row">
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <h1 class="display-4 fw-bold mb-3">Современная стоматология без боли</h1>
                    <p class="lead text-muted mb-4">Профессиональный подход к лечению зубов. Dr. Pulatov вернет вам уверенность в улыбке.</p>
                    <ul class="list-unstyled mb-4 text-muted">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Бесплатная консультация</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Современное оборудование</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i>Гарантия качества</li>
                    </ul>
                    <a href="#contact" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">Записаться на прием</a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=800&q=80" alt="Стоматолог" class="hero-img">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Почему выбирают нас</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="icon-box"><i class="fas fa-user-md"></i></div>
                        <h4>Опытный специалист</h4>
                        <p class="text-muted small">Многолетняя практика и профессионализм.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="icon-box"><i class="fas fa-magic"></i></div>
                        <h4>Безболезненно</h4>
                        <p class="text-muted small">Используем лучшие современные анестетики.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card text-center">
                        <div class="icon-box"><i class="fas fa-shield-alt"></i></div>
                        <h4>Стерильность 100%</h4>
                        <p class="text-muted small">Строгое соответствие санитарным нормам.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="appointment-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="display-5 fw-bold mb-3">Запишитесь на консультацию</h2>
                    <p class="lead mb-4 opacity-75">Оставьте заявку, и мы свяжемся с вами в ближайшее время.</p>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-phone fa-2x me-3 opacity-50"></i>
                        <div>
                            <span class="d-block small opacity-75">Телефон:</span>
                            <span class="h4 fw-bold">+998 95 041 44 88</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-card">
                        <h3 class="mb-4 text-center">Онлайн запись</h3>
                        <form id="consultationForm">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Ваше имя</label>
                                <input type="text" id="name" class="form-control" placeholder="Иван" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Номер телефона</label>
                                <input type="tel" id="phone" class="form-control" placeholder="+998 90 000 00 00" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Комментарий</label>
                                <textarea id="message" class="form-control" rows="3" placeholder="Что вас беспокоит?"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-submit shadow">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                <span class="btn-text">Отправить заявку</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p class="mb-1">&copy; 2026 Dr. Pulatov. Все права защищены.</p>
            <p class="small opacity-50">Developed by Boshmoq (Abu Bakr Gulomov)</p>
        </div>
    </footer>

    <script>
        document.getElementById('consultationForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = document.querySelector('.btn-submit');
            const btnText = document.querySelector('.btn-text');
            const spinner = document.querySelector('.spinner-border');
            
            btn.disabled = true;
            btnText.textContent = "Отправка...";
            spinner.style.display = "inline-block";

            const data = {
                name: document.getElementById('name').value,
                phone: document.getElementById('phone').value,
                message: document.getElementById('message').value || "Без комментария"
            };

            try {
                // PHP API xuddi shu faylning o'ziga murojaat qiladi
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    alert("Спасибо! Ваша заявка принята. Ожидайте звонка.");
                    document.getElementById('consultationForm').reset();
                } else {
                    alert("Произошла ошибка. Пожалуйста, позвоните нам.");
                }
            } catch (error) {
                alert("Ошибка сети. Проверьте интернет.");
            } finally {
                btn.disabled = false;
                btnText.textContent = "Отправить заявку";
                spinner.style.display = "none";
            }
        });
    </script>
    
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-17925493797"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'AW-17925493797');
    </script>
</body>
</html>
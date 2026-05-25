<?php
/**
 * HTML head and opening tags
 */
$config = get_conference_config();
$baseUrl = rtrim(function_exists('app_env') ? app_env('APP_BASE_URL', 'https://isedu.ru') : 'https://isedu.ru', '/');
$currentUrl = $baseUrl . (($_SERVER['REQUEST_URI'] ?? '/') ?: '/');
$pageTitle = 'Кадры ИБ';
$seoTitle = 'Всероссийская конференция Кадры ИБ';
$seoDescription = 'Всероссийская конференция «Кадры ИБ»: кадровое обеспечение информационной безопасности, образование, ФОИВ, индустрия. 25–27 ноября 2026 года, МИРЭА, Москва.';
$seoKeywords = 'информационная безопасность, кадры ИБ, конференция ИБ, пленум ФУМО ИБ, форум ИБ, образование ИБ, ФОИВ, кибербезопасность, подготовка кадров';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>

    <meta name="description" content="<?= e($seoDescription) ?>">
    <meta name="keywords" content="<?= e($seoKeywords) ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="theme-color" content="#0a0c14">

    <link rel="canonical" href="<?= e($currentUrl) ?>">
    <link rel="icon" type="image/x-icon" href="images/favicon/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:site_name" content="<?= e($seoTitle) ?>">
    <meta property="og:title" content="<?= e($seoTitle) ?>">
    <meta property="og:description" content="<?= e($seoDescription) ?>">
    <meta property="og:url" content="<?= e($currentUrl) ?>">
    <meta property="og:image" content="<?= e($baseUrl . '/images/logo_var.png') ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($seoTitle) ?>">
    <meta name="twitter:description" content="<?= e($seoDescription) ?>">
    <meta name="twitter:image" content="<?= e($baseUrl . '/images/logo_var.png') ?>">

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Event",
      "name": "Всероссийская конференция Кадры ИБ",
      "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
      "eventStatus": "https://schema.org/EventScheduled",
      "startDate": "2026-11-25T09:00:00+03:00",
      "endDate": "2026-11-27T18:00:00+03:00",
      "location": {
        "@type": "Place",
        "name": "МИРЭА — Российский технологический университет",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "проспект Вернадского, 78, стр. 6",
          "addressLocality": "Москва",
          "postalCode": "119454",
          "addressCountry": "RU"
        }
      },
      "organizer": {
        "@type": "Organization",
        "name": "Кадры ИБ",
        "url": "<?= e($baseUrl) ?>"
      },
      "url": "<?= e($currentUrl) ?>",
      "description": "<?= e($seoDescription) ?>"
    }
    </script>

    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

<?php
/**
 * Conference Site — Main Entry Point
 * Single-page layout with PHP includes for each section
 *
 * To hide/show sections, comment/uncomment the corresponding include line.
 * The layout will remain intact regardless of which sections are active.
 */

require_once __DIR__ . '/includes/functions.php';

// Set security headers
set_security_headers();
?>
<?php require __DIR__ . '/includes/header.php'; ?>
<?php require __DIR__ . '/sections/navigation.php'; ?>
<main>
    <?php require __DIR__ . '/sections/hero.php'; ?>
    <?php require __DIR__ . '/sections/about.php'; ?>
    <?php require __DIR__ . '/sections/goals.php'; ?>
    <?php require __DIR__ . '/sections/speakers.php'; ?>

    <?php /* --- Блок «Программа»: для временного скрытия закомментируйте строку ниже --- */ ?>
    <?php require __DIR__ . '/sections/schedule.php'; ?>

    <?php require __DIR__ . '/sections/partners.php'; ?>
    <?php require __DIR__ . '/sections/gallery.php'; ?>
    <?php require __DIR__ . '/sections/conditions.php'; ?>
    <?php require __DIR__ . '/sections/registration.php'; ?>
    <?php require __DIR__ . '/sections/venue.php'; ?>
</main>
<?php require __DIR__ . '/sections/footer.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>

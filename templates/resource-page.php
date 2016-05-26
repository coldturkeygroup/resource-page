<?php
/**
 * Template file for displaying the Resource Page
 *
 * @package    WordPress
 * @subpackage Program Page
 * @author     The Cold Turkey Group
 * @since      1.0.0
 */

global $resource_page, $wp_query;

$id = get_the_ID();
$title = get_the_title();
$logo = get_post_meta($id, 'logo', true);
$broker = get_post_meta($id, 'legal_broker', true);
$retargeting = get_post_meta($id, 'retargeting', true);

$count = 1;
$videos = [];
while ($count <= 5) {
    array_push($videos, [
        'title' => get_post_meta($id, 'video_' . $count . '_title', true),
        'url' => get_post_meta($id, 'video_' . $count . '_url', true)
    ]);
    $count++;
}

if (!$title || $title == '') {
    $title = 'Homebuyer Resource Page';
}

// Get the page colors
$primary_color = '#2eb9ff';
$hover_color = '#2eb9ff';

$color_setting = get_post_meta($id, 'primary_color', true);
$hover_setting = get_post_meta($id, 'hover_color', true);

if ($color_setting && $color_setting != '') {
    $primary_color = $color_setting;
}

if ($hover_setting && $hover_setting != '') {
    $hover_color = $hover_setting;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <?php wp_head(); ?>
    <style>
        <?php
        if( $primary_color != null ) {
            echo '
            .btn {
                background: ' . $primary_color . ' !important;
                border: 2px solid ' . $primary_color . ' !important; }
            .profiles h3, .cta h3 {
                color: ' . $primary_color . ' !important; }
            ';
        }
        if( $hover_color != null ) {
            echo '
            .btn:hover,
            .btn:focus {
                color: ' . $hover_color . ' !important;
                border-color: ' . $hover_color . ' !important; }
            ';
        }
        ?>
    </style>
</head>

<body <?php body_class(); ?>>
<div class="wrapper">
    <h1><?= $title ?></h1>

    <?php foreach ($videos as $video) { ?>
        <div class="row">
            <div class="col-xs-12 col-xs-offset-0 col-md-10 col-md-offset-1">
                <h3><?= $video['title'] ?></h3>
                <div class="embed-responsive embed-responsive-16by9">
                    <?= $video['url'] ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php if ($retargeting != null) { ?>
    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq)return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq)f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', '//connect.facebook.net/en_US/fbevents.js');
        fbq('init', '<?= $retargeting ?>');
        fbq('track', "PageView");</script>
    <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= $retargeting ?>&ev=PageView&noscript=1"/>
    </noscript>
<?php } ?>
<?php wp_footer(); ?>
<script>
    jQuery('document').ready(function () {
        jQuery('.embed-responsive').children(':first').addClass('embed-responsive-item');
    });
</script>

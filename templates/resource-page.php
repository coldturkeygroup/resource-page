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
$campaign = get_post_meta($id, 'platform_crm_campaign', true);
$background_image = get_post_meta($id, 'background_image', true);
$modal_title = get_post_meta($id, 'modal_title', true);
$modal_cta = get_post_meta($id, 'modal_cta', true);
$token = 'pf_resource_page';

$count = 1;
$videos = [];
while ($count <= 5) {
    array_push($videos, [
        'title' => get_post_meta($id, 'video_' . $count . '_title', true),
        'url' => get_post_meta($id, 'video_' . $count . '_url', true)
    ]);
    $count++;
}

if ($title == '') {
    $title = 'Homebuyer Resource Page';
}

if (($background_image == '') && function_exists('of_get_option')) {
    $background_image = of_get_option('home_page_background');
}

if ($modal_title == '') {
    $modal_title = 'Get Instant Access!';
}

if ($modal_cta == '') {
    $modal_cta = 'Watch The Videos!';
}

// Get the page colors
$primary_color = '#2eb9ff';
$hover_color = '#2eb9ff';

$color_setting = get_post_meta($id, 'primary_color', true);
$hover_setting = get_post_meta($id, 'hover_color', true);

if ($color_setting != '') {
    $primary_color = $color_setting;
}

if ($hover_setting != '') {
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
        if($background_image && $background_image != '') {
            echo '
            body {
                background-image: url(' . $background_image . ') !important;
            }
            ';
        }
        if( $primary_color != null ) {
            echo '
            .btn {
                background: ' . $primary_color . ' !important;
                border: 2px solid ' . $primary_color . ' !important; }
            .video-wrapper h3, .cta h3 {
                color: ' . $primary_color . ' !important; }
            ';
        }
        if( $hover_color != null ) {
            echo '
            .btn:hover,
            .btn:focus {
                background-color: ' . $hover_color . ' !important;
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
            <div class="col-xs-12 col-xs-offset-0 col-md-8 col-md-offset-2">
                <div class="video-wrapper">
                    <h3><?= $video['title'] ?></h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <?= $video['url'] ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <p class="broker"><?= $broker ?></p>
</div>

<div class="modal fade" id="resource-access" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
    <form method="POST" id="resource-form" action="https://create.platformcrm.com/subscribers" accept-charset="UTF-8">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="infoLabel"><?= $modal_title ?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="first_name" class="control-label">First Name</label>
                        <input class="form-control" autofocus="autofocus" required="required" placeholder="Your First Name" name="first_name" type="text" id="first_name">
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">Email Address</label>
                        <input class="form-control" required="required" placeholder="Your Email Address" name="email" type="email" id="email">
                    </div>
                    <input type="hidden" name="platform_crm_campaign" value="<?= $campaign ?>">
                    <input name="action" type="hidden" id="<?= $token ?>_submit_form" value="<?= $token ?>_submit_form">
                    <?php wp_nonce_field($token . '_submit_form', $token . '_nonce'); ?>
                </div>
                <div class="modal-footer">
                    <input type="submit" id="resource-submit" class="btn btn-primary btn-lg btn-block" value="<?= $modal_cta ?>">
                </div>
            </div>
        </div>
    </form>
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
    jQuery('document').ready(function ($) {
        <?php if(!isset($_GET['access']) || $_GET['access'] != 'true') { ?>
        $('#resource-access').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
        <?php } ?>
    });
</script>

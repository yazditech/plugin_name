<?php
    global $wpdb;

    $gwp_plugin_data = get_plugin_data(GWC_PATH . '/' . GWC_NAME . '.php')
?>
<div id="wpbody" class="yazdi-page">
    <div id="wpbody-content">
        <div class="wrap wow fadeInRight">
            <h1 class="headline-text">
                <span class="dashicons dashicons-info"></span>درباره پلاگین
            </h1>
            <div id="main-wrapper" class="wow fadeInRight">
                <div class="ym-wrapper">

                    <br>
                    <div class="wrap about-wrap">
                        <h1><?php echo $gwp_plugin_data['Name']; ?></h1>

                        <p class="about-text"><?php echo $gwp_plugin_data['Description']; ?></p>
                        <div class="wp-badge"
                             style="background: url('<?php echo YZES_IMAGES ?>logo.png') center 25px no-repeat #0073aa;background-size: 80px 80px;"></div>

                        <h2 class="nav-tab-wrapper wp-clearfix">
                            <a href="about.php" data-content="tab-content-1" class="nav-tab active">شناسنامه</a>
                            <a href="<?php echo $gwp_plugin_data['AuthorURI']; ?>" class="nav-tab" data-content="tab-content-2">وابستگی‌ها</a>
                            <a href="<?php echo $gwp_plugin_data['AuthorURI']; ?>" data-content="tab-content-3" class="nav-tab">مستندات</a>
                            <a href="<?php echo $gwp_plugin_data['AuthorURI']; ?>" class="nav-tab" data-content="tab-content-4">دست&zwnj;اندرکاران</a>
                        </h2>
                        <hr>
                        <div class="tab-content tab-content-1">

                        </div>
                        <div class="tab-content tab-content-2">
                            <h3>وابستگی‌های این پلاگین</h3>
                            <table class="form-table">
                                <tbody>
                                <?php
                                    foreach (unserialize(GWC_DEPENDENCY) as $gwp_item) { ?>
                                        <tr>
                                            <td><?php echo $gwp_item['name']; ?></td>
                                            <td><?php echo $gwp_item['version']; ?></td>
                                            <td><a href="<?php echo $gwp_item['link']; ?>"
                                                   class="button button-primary button-large">دریافت</a></td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-content tab-content-3">
                            <h3>نحوه استفاده از پلاگین</h3>
                            <br>
                            <div id="ym-amib-help" class="ym-help-details" style="display: block;">
                                <p><b>توضیحات</b></p>
                                <p>با استفاده از این پلاگین می‌توان چارت‌های سازمانی را با استفاده از اسکریپت orgchart ساخت. ورژن استفاده شده از این اسکریپت ورژن ۲۰۱۶ از این پلاگین استفاده شده است.</p>
                                <p><b>نحوه استفاده</b></p>
                                <p>برای استفاده از منوی مدیران یک page بصورت سر دسته می سازیم و سایر ساختار مدیران را در زیر این صفحه بصورت سلسله مراتبی تعریف میکنیم.</p>
                                <p>در صورتیکه بخواهیم برای هر مدیر یک تصویر انتخاب کنیم باید در صفحه مربوط به آن مدیر تصویر شاخص مدیر را آپلود کنیم.</p>
                                <p>برای نمایش این چارت از شورتکد زیر استفاده می‌کنیم:</p>
                                <xmp class="ym-codes">
                                    [show-orgchart]
                                </xmp>
                            </div>
                        </div>
                        <div class="tab-content tab-content-4"></div>
                        <table class="form-table tab-content tab-content-1 active">
                            <tbody>
                            <tr>
                                <th>نام پلاگین:</th>
                                <td><?php echo $gwp_plugin_data['Name']; ?></td>
                            </tr>
                            <tr>
                                <th>نام منحصر:</th>
                                <td><?php echo $gwp_plugin_data['TextDomain']; ?></td>
                            </tr>
                            <tr>
                                <th>نسخه:</th>
                                <td><?php echo $gwp_plugin_data['Version']; ?></td>
                            </tr>
                            <tr>
                                <th>پشتیبانی از شبکه:</th>
                                <td><?php echo ($gwp_plugin_data['Network']) ? 'در دسترس' : 'قابل دسترسی نیست'; ?></td>
                            </tr>
                            <tr>
                                <th>سازنده:</th>
                                <td><?php echo $gwp_plugin_data['AuthorName']; ?></td>
                            </tr>
                            <tr>
                                <th>تحت مالکیت:</th>
                                <td><a href="http://www.greenweb.ir">گرین‌وب سامانه‌نوین</a></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


        <!-- Load yazdi.css Styles -->
        <link rel="stylesheet" href="<?= YZES_URL ?>lib/yazdi.css">

        <!-- Page Animate Effects -->
        <link rel='stylesheet' href="<?= YZES_URL ?>lib/animate.min.css"/>
        <script type='text/javascript' src="<?= YZES_URL ?>lib/wow.min.js"></script>
        <script>
            new WOW().init();
        </script>
        <!-- Custom Styles For This Page -->
        <style>
            .update-nag {
                display: none;
            }

            .tab-content {
                display: none;
            }

            .tab-content.active {
                display: block;
            }

            .nav-tab.active {
                background-color: #fff;
                color: #0073aa;
            }

        </style>
        <script>
            jQuery('.nav-tab').click(function (e) {
                e.preventDefault();
                console.log(jQuery(this));
                jQuery(".tab-content").removeClass("active");

//                e.removeClass('active');
                jQuery(".nav-tab").removeClass("active");
                jQuery(this).addClass("active");

                console.log(jQuery(this).data('content'));

                jQuery('.' + jQuery(this).data('content')).addClass('active');
            })
        </script>

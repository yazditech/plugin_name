<?php

// Initialize and Defenition Section
global $wpdb;

#==================== Start Filters
$request_query_where = " WHERE 1 = 1 ";

if( isset($_GET['filters']) && $_GET['filters'] == 1 ) {

    if( $_GET[ 'ym_filter_1' ] && isset( $_GET[ 'ym_filter_1' ] ) && !empty( $_GET[ 'ym_filter_1' ] ) )
        $request_query_where .= " AND `ym_filter_1` LIKE '%".sanitize_text_field($_GET[ 'ym_filter_1' ])."%' ";

    if( $_GET[ 'gw_from_date' ] && isset( $_GET[ 'gw_from_date' ] ) && !empty( $_GET[ 'gw_from_date' ] ) )
        $request_query_where .= "  AND `payment_date` >= ".str_replace( '/', '', two_digits_date(sanitize_text_field($_GET[ 'gw_from_date' ]),'/') )." ";

}
#==================== End Filters


#==================== Start Pagination
$pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
$limit = 5; // number of rows in page
$offset = ($pagenum - 1) * $limit;
$total = $wpdb->get_var("SELECT COUNT(`id`) FROM {$wpdb->prefix}tbl_name ORDER BY `date_added` DESC");
$num_of_pages = ceil($total / $limit);
$page_links = paginate_links(array(
        'base' => add_query_arg('pagenum', '%#%'),
        'format' => '',
        'prev_text' => __('<span class="dashicons dashicons-arrow-right-alt2"></span>', 'text-domain'),
        'next_text' => __('<span class="dashicons dashicons-arrow-left-alt2"></span>', 'text-domain'),
        'total' => $num_of_pages,
        'current' => $pagenum
));
#==================== End Pagination


#==================== Start Select Queries
$gwp_select = $wpdb->get_results("SELECT *
                                    FROM {$wpdb->prefix}tbl_name
                                    $request_query_where
                                    ORDER BY `date_added` DESC
                                    LIMIT $offset, $limit
                                    ");
#==================== End Select Queries


#==================== Start Insert Queries
if (isset($_POST['gwc_nonces']) && wp_verify_nonce($_POST['gwc_nonces'], 'gwp_insert_form') ) {
    $form_data = array();
    $form_data['gw_field_1'] = sanitize_text_field($_POST['gw_field_1']);


    // Start File Upload Operations
    $file_errors = false;

    $gfms_date = jgetdate();
    $year = $gfms_date['year'];
    $month = $gfms_date['mon'];
    $day = $gfms_date['mday'];

    $directory = wp_upload_dir()["basedir"]."/gwc/$year/$month/";


    if(!is_dir($directory)) {
        //Create our directory.
        $res123 = mkdir( $directory, 755, true );
    }

    $mimes = array('image/jpeg','image/pjpeg','application/pdf');

    $fileinfo = finfo_open(FILEINFO_MIME_TYPE);

    if( isset( $_FILES[ 'gw_fiedd_file' ] ) ) {

        $img1 = $_FILES['gw_fiedd_file'];

        if ($img1['error'] == 0) {

            $imageType = finfo_file($fileinfo, $_FILES["gw_fiedd_file"]["tmp_name"]);

            if (in_array($imageType, $mimes)) {

                if ( $img1['size'] < 10 * 1024 * 1024 ) { // 10MB

                    $target_path = $directory;
                    $img1_name = "gwc_" . time() . "_" . $img1["name"];

                    if (!move_uploaded_file($img1["tmp_name"], $target_path . $img1_name)) {
                        $file_errors = "move_file";
                    } else {
                        $form_data['gw_fiedd_file'] = wp_upload_dir()["baseurl"]."/gwcts/$year/$month/$img1_name";
                        $file_errors = false;
                    }
                } else {
                    $file_errors = "size_file";
                }
            } else {
                $file_errors = "type_file";
            }
        }
    }
    // End File Upload Operations



    if ($file_errors == false ) {

        // Insert To Table
        $gwc_register_result = $wpdb->insert( "{$wpdb->prefix}tbl_name" ,
                array( 'id' => make_hash(5),
                        'customer_no' => $form_data['gw_customer_no'],
                        'date_added' => date('Y-m-d h:i:sa'),
                        'last_user_edited' => get_current_user_id()
                ),
                '%s',
                '%d',

                array(  '%s',
                        '%d',
                        '%d',
                ));

        if ( $gwc_register_result )
            $gwc_message = 'اطلاعات با موفقیت ثبت گردید.';
        else
            $gwc_message = 'متاسفانه در ثبت اطلاعات، مشکلی بوجود آمده است.';


    } elseif ($file_errors == 'size_file') {
        $gwc_message = 'سایز فایل مجاز نمی باشد.';
    } elseif ($file_errors == 'type_file') {
        $gwc_message = 'نوع فایل مجاز نمی باشد.';
    } elseif ($file_errors == 'move_file') {
        $gwc_message = 'خطای سرور در آپلود فایل.';
    }

}
#==================== End Insert Queries


#==================== Start Update Queries
if (isset($_POST['gwc_nonces']) && wp_verify_nonce($_POST['gwc_nonces'], 'gwp_edit_form') ) {

    $new_data = array();
    $new_data['gw_field_1'] = sanitize_text_field($_POST['gw_field_1']);

    $gwc_edit_result = $wpdb->update( "{$wpdb->prefix}tbl_name" ,
                                            array(
                                                    'gw_field_1'=> sanitize_text_field($new_data),
                                                    'gw_field_2'=> sanitize_text_field($new_data),
                                                    'gw_field_3'=> sanitize_text_field($new_data),
                                                    ),
                                                    array( 'id' => $request_id ),
                                                    array(
                                                            '%s'
                                                    ),
                                                    array( '%d' )
                                                );

    if($gwc_edit_result) $gwc_message = 'بروزرسانی با موفقیت انجام شد.';

}
#==================== End Update Queries


#==================== start Delete Queries
if (isset($_GET['action']) && $_GET['action'] == 'delete' && $_GET['id'] ) {

        if ($wpdb->delete($wpdb->prefix . 'tbl_name', array('ID' => sanitize_text_field($_GET['id'])), array('%d'))) {
            $gwc_message = 'رکورد مورد نظر با موفقیت حذف گردید';
    }
}
#==================== End Delete Queries


?>
<div id="wpbody" class="yazdi-page">
    <div id="wpbody-content">
        <div class="wrap wow fadeInRight">
            <h1 class="headline-text">
                <span class="dashicons dashicons-admin-settings"></span>تایتل صفحه مشاهده اطلاعات
                <a id="gwp-register-submit" name="gwp_filter_submit" class="ym-button ym-button-blue ym-button-new"
                    onclick="document.getElementById('gwp-insert-modal').style.display='block';"
                ">ایجاد آیتم جدید
                    <span class="dashicons dashicons-plus-alt" ></span>
                </a>
            </h1>
            <div id="main-wrapper" class="wow fadeInRight">
                <?php if ($gwc_message) : ?>
                    <div class="updated notice is-dismissible ym-msg" style="display: block;"><p><?=$gwc_message?></p></div>
                <?php endif; ?>
                <div class="ym-wrapper">
                    <form action="" name="ym_filter_form" id="ym-filter-form" method="get" class="pull-right">
                        <input type="hidden" name="page" value="panel1" >
                        <input type="hidden" name="filters" value="1" >
                        <div class="filters-wrapper">
                            <div class="ym-frm-inline">
                                <label for="">فیلتر یک: </label>
                                <input id="gwp-filter-1" type="text" name="gwp_filter_1" value="<?php echo ($_POST['gwp_filter_1']) ? $_POST['gwp_filter_1'] : '' ?>" placeholder="فیلتر یک">
                            </div>
                            <div class="ym-frm-inline">
                                <label for="">ار تاریخ: </label>
                                <input id="pick_date_gwp-filter-1" type="text" name="gwp_filter_2" value="" placeholder="از تاریخ">
                            </div>
                            <div class="ym-frm-inline">
                                <label for="">تا تاریخ: </label>
                                <input id="pick_date_gwp-filter-2" type="text" name="gwp_filter_3" value="" placeholder="تا تاریخ">
                            </div>
                            <div class="ym-frm-inline">
                                <label for="">فیلتر یک: </label>
                                <input id="gwp-filter-1" type="text" name="gwp_filter_4" value="" placeholder="فیلتر یک">
                            </div>
                            <div class="ym-frm-inline">
                                <label for="">فیلتر یک: </label>
                                <input id="gwp-filter-1" type="text" name="gwp_filter_5" value="" placeholder="فیلتر یک">
                            </div>
                            <div class="ym-frm-inline">
                                <label for="">فیلتر یک: </label>
                                <input id="gwp-filter-1" type="text" name="gwp_filter_6" value="" placeholder="فیلتر یک">
                            </div>
                            <div class="ym-frm-inline">
                                <label for="">فیلتر یک: </label>
                                <input id="gwp-filter-1" type="text" name="gwp_filter_7" value="" placeholder="فیلتر یک">
                            </div>
                            <div class="ym-frm-inline">
                                <label for="">فیلتر یک: </label>
                                <input id="gwp-filter-1" type="text" name="gwp_filter_8" value="" placeholder="فیلتر یک">
                            </div>
                            <div class="ym-frm-inline">
                                <label class="ym-check">فیلتر چک: </label>
                                <input class="yzd-check" name="gwp_filter_9" type="checkbox" checked="checked">
                            </div>
                            <div class="ym-frm-inline">
                                <label for="">فیلتر یک: </label>
                                <div class="ym-select">
                                    <select name="gwp_filter_1">
                                        <option value="">انتخاب گزینه</option>
                                        <option value="1">گزینه یک گزینه یک گزینه یک گزینه یک گزینه یک</option>
                                        <option value="2">گزینه دو</option>
                                        <option value="3">گزینه سه</option>
                                        <option value="4">گزینه چهار</option>
                                        <option value="5">گزینه پنج</option>
                                    </select>
                                    <span class="dashicons dashicons-arrow-down-alt2"></span>
                                </div>
                            </div>
                            <div class="ym-frm-inline">
                                <button id="gwp-register-submit" name="gwp_filter_submit" class="ym-button ym-button-blue">فیلتر<span class="dashicons dashicons-image-filter"></span></button>
                            </div>
                        </div>
                        <?php wp_nonce_field('ym_filter_form', 'gwc_filter_nonces'); ?>
                    </form>

                    <!-- Start Show data Table -->
                    <table id="printable" class="widefat responsive yazdi-wp-tbl" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>روش پرداخت</th>
                            <th>مبلغ (ریال)</th>
                            <th>توضیحات</th>
                            <th>تاریخ پرداخت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>ردیف</th>
                            <th>روش پرداخت</th>
                            <th>مبلغ (ریال)</th>
                            <th>توضیحات</th>
                            <th>تاریخ پرداخت</th>
                            <th>عملیات</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        <tr class="wow fadeInUp">
                            <td>داده ی یک</td>
                            <td>داده ی یک</td>
                            <td>داده ی یک</td>
                            <td>داده ی یک</td>
                            <td>داده ی یک</td>
                            <td>
                                <a name="gwp_edit" class="ym-button ym-button-blue-rev gwp-edit"

                                   onclick="document.getElementById('gwp-edit-modal').style.display='block';load_data( 'test', 'test2' );"
                                >ویرایش
                                    <span class="dashicons dashicons-edit"></span>
                                </a>
                                <a name="gwp_delete" class="ym-button ym-button-red-rev gwp-delete"
                                   href="<?php echo add_query_arg(array('action' => 'delete', 'cid' => '123')); ?>"
                                >حذف
                                    <span class="dashicons dashicons-trash"></span>
                                </a>

                            </td>
                        </tr>
                        <?php $row = 0; if( count($gwp_select) > 0 ) { ?>
                            <?php foreach( $gwp_select as $gwp_select_item ) { ?>
                                <tr class="wow fadeInUp">
                                    <td<?php $row++; echo $row; ?></td>
                                    <td><?=$gwp_select_item->field_1;?></td>
                                    <td><?=$gwp_select_item->field_2;?></td>
                                    <td><?=$gwp_select_item->field_3;?></td>
                                    <td><?=delimiter_to_date($gwp_select_item->field_4, '/');?></td>
                                    <td>
                                        <a name="gwp_edit" class="ym-button ym-button-blue-rev gwp-edit"

                                           onclick="document.getElementById('gwp-edit-modal').style.display='block';load_data( <?=$gwp_select_item->field_5?>, '<?=$gwp_select_item->field_6?>' );"
                                        >ویرایش
                                            <span class="dashicons dashicons-edit"></span>
                                        </a>
                                        <a name="gwp_delete" class="ym-button ym-button-red-rev gwp-delete"
                                           href="<?php echo add_query_arg(array('action' => 'delete', 'cid' => '123')); ?>"
                                        >حذف
                                            <span class="dashicons dashicons-trash"></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                    <!-- End Show Data Table -->

                    <br>
                    <!-- Show Pagination  -->
                    <?php
                    if ($page_links) {
                        echo '<div class="tablenav"><div class="tablenav-pages wow fadeInUp" style="margin: 1em 0">' . $page_links . '</div></div>';
                    }
                    ?>
                    <br>

                </div>
            </div>
        </div>

        <!-- Start Insert Modal -->
        <form action="<?php echo get_permalink(); ?>" enctype="multipart/form-data" name="gwp_insert_form" id="gwp-insert-modal-form" method="POST" class="yazdi-page">
            <div id="gwp-insert-modal" class="yzd-modal">
                <div class="yzd-modal-content yzd-animate-zoom yzd-card-8">
                    <header class="yzd-container">
                        <span onclick="document.getElementById('gwp-insert-modal').style.display='none'"
                              class="yzd-closebtn">&times;</span>
                                    <span class="title">ایجاد آیتم جدید</span>
                    </header>
                    <div class="yzd-container yzd-body">
                        <div class="ym-frm-row">
                            <label>فیلد یک:</label>
                            <input id="gw_fiedd_1" type="text" name="gw_fiedd_1">
                        </div>
                        <div class="ym-frm-row">
                            <label>تاریخ انتخابی:</label>
                            <input id="pick_date_gw_fiedd_2" type="text" name="gw_fiedd_2" value="">
                        </div>
                        <div class="ym-frm-row">
                            <label>دسته انتخابی:</label>
                            <div class="ym-select">
                                <select name="gwp_select_1">
                                    <option value="">انتخاب گزینه</option>
                                    <option value="1">گزینه یک گزینه یک گزینه یک گزینه یک گزینه یک</option>
                                    <option value="2">گزینه دو</option>
                                    <option value="3">گزینه سه</option>
                                    <option value="4">گزینه چهار</option>
                                    <option value="5">گزینه پنج</option>
                                </select>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </div>
                        </div>
                        <div class="ym-frm-row">
                            <label>آپلود فایل: </label>
                            <input id="gw_fiedd_file" type="file" name="gw_fiedd_file" value="" >
                            <div class="gfms-show-ext">
                                <span>فایل های مجاز: </span>
                                <span>.jpg</span>
                                <span>.pdf</span>
                            </div>
                        </div>
                        <div class="ym-frm-row">
                            <label>توضیحات:</label>
                            <textarea name="gw_fiedd_3" id="gw_fiedd_3" cols="28" rows="10"></textarea>
                        </div>
                        <div class="ym-frm-row">
                            <button id="gwp-insert-form-submit" name="gwp_insert_for_submit" class="ym-button ym-button-blue">ثبت اطلاعات
                                <span class="dashicons dashicons-plus"></span>
                            </button>
                        </div>
                    </div>
                    <footer class="yzd-container yzd-teal">
                        <p></p>
                    </footer>
                </div>
            </div>
            <?php wp_nonce_field('gwp_insert_form', 'gwc_nonces'); ?>
        </form>
        <!-- End Insert Modal -->

        <!-- Start Edit Modal -->
        <form action="<?php echo get_permalink(); ?>" enctype="multipart/form-data" name="gwp_edit_form" id="gwp-edit-modal-form" method="POST" class="yazdi-page">
            <div id="gwp-edit-modal" class="yzd-modal">
                <div class="yzd-modal-content yzd-animate-zoom">
                    <header class="yzd-container">
                        <span onclick="document.getElementById('gwp-edit-modal').style.display='none'"
                              class="yzd-closebtn">&times;</span>
                        <span class="title">ویرایش</span>
                    </header>
                    <div class="yzd-container yzd-body">
                        <div class="ym-frm-row">
                            <label>فیلد یک:</label>
                            <input id="gw_edit_fiedd_1" type="text" name="gw_fiedd_1" value="">
                        </div>
                        <div class="ym-frm-row">
                            <label>تاریخ انتخابی:</label>
                            <input id="pick_date_gw-edit-fiedd22" type="text" name="gw_fiedd_2" value="">
                        </div>
                        <div class="ym-frm-row">
                            <label>دسته انتخابی:</label>
                            <div class="ym-select">
                                <select name="gwp_select_1" id="gw_edit_select_1">
                                    <option value="">انتخاب گزینه</option>
                                    <option value="1">گزینه یک گزینه یک گزینه یک گزینه یک گزینه یک</option>
                                    <option value="2">گزینه دو</option>
                                    <option value="3">گزینه سه</option>
                                    <option value="4">گزینه چهار</option>
                                    <option value="5">گزینه پنج</option>
                                </select>
                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                            </div>
                        </div>
                        <div class="ym-frm-row">
                            <label>آپلود فایل: </label>
                            <input id="gw_fiedd_file" type="file" name="gw_fiedd_file" value="" >
                            <div class="gfms-show-ext">
                                <span>فایل های مجاز: </span>
                                <span>.jpg</span>
                                <span>.pdf</span>
                            </div>
                        </div>
                        <div class="ym-frm-row">
                            <label>توضیحات:</label>
                            <textarea name="gw_fiedd_3" id="gw_fiedd_3" cols="28" rows="10"></textarea>
                        </div>
                        <div class="ym-frm-row">
                            <button id="gwp-insert-form-submit" name="gwp_insert_for_submit" class="ym-button ym-button-blue">ویرایش اطلاعات
                                <span class="dashicons dashicons-plus"></span>
                            </button>
                        </div>
                    </div>
                    <footer class="yzd-container yzd-teal">
                        <p></p>
                    </footer>
                </div>
            </div>
            <?php wp_nonce_field('gwp_edit_form', 'gwc_nonces'); ?>
        </form>
        <!-- End Edit Modal -->
    </div>
</div>



<!--Persian Date Selection Example (Amib)-->
<link rel="stylesheet" href="<?= YZES_URL ?>lib/fa-date-selection/js-persian-cal.css">
<script src="<?= YZES_URL ?>lib/fa-date-selection/js-persian-cal.min.js"></script>
<script>
    ( function( $ ) {
        $.fn.persianCalendar = function(extra) {
            return this.each( function( index, element ) {
                var id = jQuery(element).attr("id");
                new AMIB.persianCalendar( id, extra );
            } );
        };
    })( jQuery );

    jQuery("input[id^=pick_date_]").persianCalendar( {} );
</script>

<!-- Load yazdi.css Styles -->
<link rel="stylesheet" href="<?=YZES_URL?>lib/yazdi.css" >

<!-- Page Animate Effects -->
<link rel='stylesheet' href="<?=YZES_URL?>lib/animate.min.css" />
<script type='text/javascript' src="<?=YZES_URL?>lib/wow.min.js" ></script>
<script>
    new WOW().init();
</script>

<!--Custom Functions For This Page-->
<script>
//        Load Data to Edit Form
    function load_data( field_1, field_2 ) {

        jQuery("#gw_edit_fiedd_1").val( field_1 );
        jQuery("#gw_edit_select_1").val("3");
//        jQuery("#gwp_select_1").attr("href", image_src );
    }
</script>
<!-- Custom Styles For This Page -->
<style>
    .update-nag {
        display: none;
    }
</style>

<script>
    jQuery(document).ready(function ($) {
        $(".gwp-delete").click(function (event) {
            if (!confirm('حذف شود ؟'))
                event.preventDefault();
        });
    });

</script>


<!-- Start JS Validation -->
<script src="<?php echo YZES_URL ?>lib/jquery_validation/jquery.validate.js"></script>
<script src="<?php echo YZES_URL ?>lib/jquery_validation/jqueryvalidation.org.js"></script>
<script>
    jQuery("documet").ready( function(){

        jQuery("#gwp-insert-modal-form").validate({
            rules: {
                gw_fiedd_1: {
                    required: true
                },
                gwp_select_1: {
                    required: true
                },
            },
            messages: {
                gw_fiedd_1: "الزامی است",
                gwp_select_1: "الزامی است"
            }
        });

    });
</script>
<!-- End JS Validation -->
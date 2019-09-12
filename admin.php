<div class="wrap">
    <?php
    if (isset($_POST['install'])) {
        if (isset($_POST['directory']) && $_POST['directory']) {
            $installDir = get_home_path() . $_POST['directory'];
            $siteUrl = get_option('siteurl') . $_POST['directory'];
            if (!file_exists($installDir)) {
                if (isset($_POST['site_title']) && $_POST['site_title'] && isset($_POST['site_description']) && $_POST['site_description'] && isset($_POST['admin_email']) && $_POST['admin_email'] && isset($_POST['admin_password']) && $_POST['admin_password']) {
                    mkdir($installDir);

                    $file = file_get_contents('http://formpardakht.com/latest.zip', false);
                    file_put_contents($installDir . '/latest.zip', $file);

                    $zip = new ZipArchive;
                    if ($zip->open($installDir . '/latest.zip')) {
                        $zip->extractTo($installDir);
                        $zip->close();
                    }
                    $sampleConfig = require($installDir . '/core/config-sample.php');

                    foreach ($sampleConfig as $key => $value) {
                        $sampleConfig[$key] = '"' . $value . '",';
                    }
                    $sampleConfig['APP_URL'] = '"' . $siteUrl . '",';
                    $sampleConfig['DB_HOST'] = '"' . DB_HOST . '",';
                    $sampleConfig['DB_DATABASE'] = '"' . DB_NAME . '",';
                    $sampleConfig['DB_USERNAME'] = '"' . DB_USER . '",';
                    $sampleConfig['DB_PASSWORD'] = '"' . DB_PASSWORD . '",';

                    $sampleConfig = print_r($sampleConfig, true);
                    $sampleConfig = str_replace("[", '"', $sampleConfig);
                    $sampleConfig = str_replace("]", '"', $sampleConfig);

                    file_put_contents($installDir . '/core/config.php', '<?php return ' . $sampleConfig . ';');

                    $data = [
                        'site_url' => $siteUrl,
                        'site_title' => $_POST['site_title'],
                        'site_description' => $_POST['site_description'],
                        'admin_email' => $_POST['admin_email'],
                        'admin_password' => $_POST['admin_password'],
                    ];

                    update_option('formpardakht_installed', 1);
                    update_option('formpardakht_directory', $_POST['directory']);

                    echo "<div class='notice notice-success is-dismissible'><p>در حال هدایت به صفحه تکمیل نصب اسکریپت...</p></div>";
                    echo "<script>setTimeout(function() {window.location.href = '" . $siteUrl . '/install/complete-ez?' . http_build_query($data) . "'}, 500)</script>";
                    return;
                } else {
                    echo "<div class='notice notice-error is-dismissible'><p>لطفا تمام فیلد های ضروری را تکمیل کنید</p></div>";
                }
            } else {
                echo "<div class='notice notice-error is-dismissible'><p>فولدر نصب از قبل موجود می باشد. لطفا مسیر دیگری برای نصب انتخاب کنید</p></div>";
            }
        } else {
            echo "<div class='notice notice-error is-dismissible'><p>وارد کردن محل نصب الزامی می باشد</p></div>";
        }
    }
    if (isset($_POST['save'])) {
        if (isset($_POST['directory']) && $_POST['directory']) {
            $oldInstallDir = get_home_path() . get_option('formpardakht_directory');
            $installDir = get_home_path() . $_POST['directory'];
            if (!file_exists($installDir)) {
                rename($oldInstallDir, $installDir);
                update_option('formpardakht_directory', $_POST['directory']);

                echo "<div class='notice notice-success is-dismissible'><p>محل نصب اسکریپت تغییر یافت</p></div>";
            } else {
                echo "<div class='notice notice-error is-dismissible'><p>فولدر نصب از قبل موجود می باشد. لطفا مسیر دیگری برای نصب انتخاب کنید</p></div>";
            }
        } else {
            echo "<div class='notice notice-error is-dismissible'><p>وارد کردن محل نصب الزامی می باشد</p></div>";
        }
    }
    ?>
    <?php if (get_option('formpardakht_installed')) : ?>
        <h1>تنظیمات اسکریپت فرم پرداخت</h1>
        <form method="post">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label>بخش مدیریت اسکریپت</label>
                    </th>
                    <td>
                        <a href="<?= get_option('siteurl') . get_option('formpardakht_directory') ?>/login" target="_blank"><?= get_option('siteurl') . get_option('formpardakht_directory') ?>/login</a>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="directory">محل نصب اسکریپت</label> <span style="color: red">*</span>
                    </th>
                    <td>
                        <input type="text" id="directory" name="directory" value="<?= get_option('formpardakht_directory') ?>" style="direction: ltr" />
                        <span><?= get_option('siteurl') ?></span>
                    </td>
                </tr>
            </table>
            <?php submit_button('ذخیره تغییرات', 'primary', 'save'); ?>
        </form>
    <?php else : ?>
        <h1>نصب اسکریپت فرم پرداخت</h1>
        <form method="post">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="directory">محل نصب اسکریپت</label> <span style="color: red">*</span>
                    </th>
                    <td>
                        <input type="text" id="directory" name="directory" value="/formpardakht" style="direction: ltr" />
                        <span><?= get_option('siteurl') ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="site_title">عنوان</label> <span style="color: red">*</span>
                    </th>
                    <td>
                        <input type="text" id="site_title" name="site_title" value="فرم پرداخت" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="site_description">توضیحات</label> <span style="color: red">*</span>
                    </th>
                    <td>
                        <input type="text" id="site_description" name="site_description" value="توضیحات" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="admin_email">ایمیل ادمین اسکریپت</label> <span style="color: red">*</span>
                    </th>
                    <td>
                        <input type="text" id="admin_email" name="admin_email" value="<?= get_option('admin_email') ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="admin_password">کلمه عبور ادمین اسکریپت</label> <span style="color: red">*</span>
                    </th>
                    <td>
                        <input type="text" id="admin_password" name="admin_password" />
                    </td>
                </tr>
            </table>
            <?php submit_button('شروع نصب', 'primary', 'install'); ?>
        </form>
    <?php endif ?>
</div>
<?php

function formpardakht_install()
{
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

function formpardakht_save()
{
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

function formpardakht_delete_dir($dir)
{
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator(
        $it,
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $file) {
        if ($file->isDir()) {
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($dir);
}

function formpardakht_check_installation()
{
    if (get_option('formpardakht_installed')) {
        $installDir = get_home_path() . get_option('formpardakht_directory');
        if (!file_exists($installDir)) {
            update_option('formpardakht_installed', 0);
        }
    }
}

<div class="wrap">
    <?php
    formpardakht_check_installation();

    if (isset($_POST['install'])) {
        formpardakht_install();
    }
    if (isset($_POST['save'])) {
        formpardakht_save();
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
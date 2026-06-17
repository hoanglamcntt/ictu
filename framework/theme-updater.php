<?php
/**
 * Theme Updater - Tự động cập nhật theme từ GitHub
 *
 * Có thể copy/paste sang bất kỳ theme nào.
 * Vào Appearance > Cập nhật Theme > Cấu hình để nhập thông tin GitHub.
 *
 * @package ThemeUpdater
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

// ====================================================================
// CẤU HÌNH MẶC ĐỊNH
// (Có thể ghi đè bằng define trong wp-config.php nếu muốn)
// ====================================================================
if ( ! defined( 'THUPD_DEFAULT_OWNER' ) )  define( 'THUPD_DEFAULT_OWNER', 'hoanglamcntt' );
if ( ! defined( 'THUPD_DEFAULT_REPO' ) )   define( 'THUPD_DEFAULT_REPO', 'ictu' );
if ( ! defined( 'THUPD_DEFAULT_BRANCH' ) ) define( 'THUPD_DEFAULT_BRANCH', 'main' );
if ( ! defined( 'THUPD_DEFAULT_SLUG' ) )   define( 'THUPD_DEFAULT_SLUG', 'ictu' );
if ( ! defined( 'THUPD_DEFAULT_NAME' ) )   define( 'THUPD_DEFAULT_NAME', 'ICTU' );
if ( ! defined( 'THUPD_OPTION_KEY' ) )     define( 'THUPD_OPTION_KEY', 'thupd_config' );

// ====================================================================
// HÀM ĐỌC/GHI CẤU HÌNH
// ====================================================================

/**
 * Lấy bảng mã lỗi ZipArchive (dùng hàm để tránh lỗi nếu ZipArchive chưa được load)
 */
function thupd_get_zip_errors() {
    if ( ! class_exists( 'ZipArchive' ) ) {
        return array();
    }
    return array(
        ZipArchive::ER_EXISTS => 'ER_EXISTS - File đã tồn tại.',
        ZipArchive::ER_INCONS => 'ER_INCONS - File ZIP không nhất quán.',
        ZipArchive::ER_INVAL  => 'ER_INVAL - Đối số không hợp lệ.',
        ZipArchive::ER_MEMORY => 'ER_MEMORY - Không đủ bộ nhớ.',
        ZipArchive::ER_NOENT  => 'ER_NOENT - File không tồn tại.',
        ZipArchive::ER_NOZIP  => 'ER_NOZIP - File không phải là ZIP hợp lệ.',
        ZipArchive::ER_OPEN   => 'ER_OPEN - Không thể mở file.',
        ZipArchive::ER_READ   => 'ER_READ - Lỗi đọc file.',
        ZipArchive::ER_SEEK   => 'ER_SEEK - Lỗi tìm kiếm trong file.',
    );
}

/**
 * Lấy cấu hình từ database (hoặc mặc định nếu chưa có)
 */
function thupd_get_config() {
    $defaults = array(
        'github_owner'  => THUPD_DEFAULT_OWNER,
        'github_repo'   => THUPD_DEFAULT_REPO,
        'github_branch' => THUPD_DEFAULT_BRANCH,
        'theme_slug'    => THUPD_DEFAULT_SLUG,
        'theme_name'    => THUPD_DEFAULT_NAME,
    );
    $saved = get_option( THUPD_OPTION_KEY, array() );
    if ( ! is_array( $saved ) ) {
        $saved = array();
    }
    return wp_parse_args( $saved, $defaults );
}

/**
 * Lưu cấu hình vào database
 */
function thupd_save_config( $data ) {
    $allowed = array( 'github_owner', 'github_repo', 'github_branch', 'theme_slug', 'theme_name' );
    $config  = array();
    foreach ( $allowed as $key ) {
        if ( isset( $data[ $key ] ) ) {
            $config[ $key ] = sanitize_text_field( $data[ $key ] );
        }
    }
    update_option( THUPD_OPTION_KEY, $config );
    return $config;
}

// ====================================================================
// KIỂM TRA HỆ THỐNG
// ====================================================================

/**
 * Kiểm tra các điều kiện tiên quyết trước khi update
 *
 * @return array Mảng lỗi (rỗng nếu OK)
 */
function thupd_preflight_checks() {
    $errors = array();

    // 1. Kiểm tra cấu hình có hợp lệ không
    $config = thupd_get_config();
    $required_fields = array(
        'github_owner'  => 'GitHub Owner',
        'github_repo'   => 'GitHub Repo',
        'github_branch' => 'Branch',
        'theme_slug'    => 'Theme Directory',
        'theme_name'    => 'Theme Name',
    );
    foreach ( $required_fields as $key => $label ) {
        if ( empty( $config[ $key ] ) ) {
            $errors[] = sprintf( 'Thiếu cấu hình "%s". Vào tab Cấu hình để nhập.', $label );
        }
    }
    if ( ! empty( $errors ) ) {
        return $errors;
    }

    // 2. Kiểm tra ZipArchive
    if ( ! class_exists( 'ZipArchive' ) ) {
        $errors[] = 'Máy chủ chưa cài đặt PHP extension <strong>ZipArchive</strong>. '
                  . 'Vui lòng liên hệ nhà cung cấp hosting để yêu cầu bật extension này.';
    }

    // 3. Kiểm tra thư mục theme có tồn tại và writable không
    $theme_dir = WP_CONTENT_DIR . '/themes/' . $config['theme_slug'];
    if ( ! is_dir( $theme_dir ) ) {
        $errors[] = sprintf(
            'Thư mục theme <code>%s</code> không tồn tại trong <code>wp-content/themes/</code>. '
          . 'Hãy kiểm tra lại cấu hình "Theme Directory".',
            esc_html( $config['theme_slug'] )
        );
    } elseif ( ! is_writable( $theme_dir ) ) {
        $errors[] = sprintf(
            'Thư mục theme <code>%s</code> không cho phép ghi. '
          . 'Vui lòng CHMOD thư mục này thành 755 hoặc 775.<br>'
          . 'Cách sửa: Dùng FTP hoặc File Manager, chuột phải vào thư mục <code>%s</code>, '
          . 'chọn "Permissions" và đặt thành 755.',
            esc_html( $config['theme_slug'] ),
            esc_html( $config['theme_slug'] )
        );
    }

    // 4. Kiểm tra thư mục uploads có writable không
    $upload_dir = wp_upload_dir();
    if ( isset( $upload_dir['basedir'] ) && ! is_writable( $upload_dir['basedir'] ) ) {
        $errors[] = 'Thư mục <code>wp-content/uploads/</code> không cho phép ghi. '
                  . 'Vui lòng CHMOD thành 755 hoặc 775.';
    }

    // 5. Kiểm tra khả năng tải file từ xa
    $can_remote = false;
    if ( function_exists( 'curl_init' ) ) {
        $can_remote = true;
    } elseif ( ini_get( 'allow_url_fopen' ) ) {
        $can_remote = true;
    } elseif ( function_exists( 'fsockopen' ) ) {
        $can_remote = true;
    }

    if ( ! $can_remote ) {
        $errors[] = 'Máy chủ không hỗ trợ tải file từ xa. '
                  . 'Cần bật một trong các tính năng: <strong>cURL</strong>, '
                  . '<strong>allow_url_fopen</strong>, hoặc <strong>fsockopen</strong>. '
                  . 'Vui lòng liên hệ nhà cung cấp hosting.';
    }

    return $errors;
}

// ====================================================================
// THÔNG TIN THEME HIỆN TẠI
// ====================================================================

/**
 * Lấy phiên bản theme hiện tại
 */
function thupd_get_current_theme_version() {
    $theme = wp_get_theme();
    return $theme->get( 'Version' );
}

/**
 * Lấy tên thư mục theme hiện tại (để dùng fallback)
 */
function thupd_get_current_theme_slug() {
    $theme = wp_get_theme();
    return $theme->get_stylesheet();
}

// ====================================================================
// GITHUB API
// ====================================================================

/**
 * Lấy URL download ZIP từ GitHub
 */
function thupd_get_github_download_url( $config ) {
    return sprintf(
        'https://api.github.com/repos/%s/%s/zipball/%s',
        rawurlencode( $config['github_owner'] ),
        rawurlencode( $config['github_repo'] ),
        rawurlencode( $config['github_branch'] )
    );
}

/**
 * Lấy thông tin commit mới nhất từ GitHub
 */
function thupd_get_github_latest_info( $config ) {
    $url = sprintf(
        'https://api.github.com/repos/%s/%s/commits/%s',
        rawurlencode( $config['github_owner'] ),
        rawurlencode( $config['github_repo'] ),
        rawurlencode( $config['github_branch'] )
    );

    $response = wp_remote_get( $url, array(
        'timeout' => 15,
        'headers' => array(
            'Accept'     => 'application/vnd.github.v3+json',
            'User-Agent' => 'WordPress-Theme-Updater/1.0',
        ),
    ) );

    if ( is_wp_error( $response ) ) {
        $err_code = $response->get_error_code();
        $err_msg  = $response->get_error_message();

        if ( strpos( $err_msg, 'timed out' ) !== false || strpos( $err_msg, 'timeout' ) !== false ) {
            $msg = 'Kết nối đến GitHub bị timeout sau 15 giây. '
                 . 'Kiểm tra kết nối internet của máy chủ. '
                 . 'Nếu dùng hosting tại Việt Nam, có thể cần cấu hình DNS hoặc proxy.';
        } elseif ( strpos( $err_msg, 'Could not resolve host' ) !== false ) {
            $msg = 'Không thể phân giải tên miền <code>api.github.com</code>. '
                 . 'Kiểm tra DNS server của máy chủ.';
        } elseif ( strpos( $err_msg, 'Connection refused' ) !== false ) {
            $msg = 'Kết nối bị từ chối. Có thể tường lửa (firewall) đang chặn kết nối đến GitHub.';
        } elseif ( strpos( $err_msg, 'SSL' ) !== false || strpos( $err_msg, 'certificate' ) !== false ) {
            $msg = 'Lỗi SSL khi kết nối GitHub. '
                 . 'Có thể máy chủ chưa cập nhật chứng chỉ CA. '
                 . 'Liên hệ hosting để yêu cầu cập nhật.';
        } else {
            $msg = 'Lỗi kết nối đến GitHub: [' . $err_code . '] ' . $err_msg;
        }

        return array(
            'success' => false,
            'message' => $msg,
        );
    }

    $code = wp_remote_retrieve_response_code( $response );

    if ( $code === 404 ) {
        return array(
            'success' => false,
            'message' => sprintf(
                'GitHub repository <code>%s/%s</code> không tìm thấy (HTTP 404). '
              . 'Kiểm tra lại "GitHub Owner" và "GitHub Repo" trong tab Cấu hình.',
                esc_html( $config['github_owner'] ),
                esc_html( $config['github_repo'] )
            ),
        );
    }

    if ( $code === 403 ) {
        $body_response = json_decode( wp_remote_retrieve_body( $response ), true );
        $rate_msg = isset( $body_response['message'] ) ? $body_response['message'] : '';
        $token_hint = 'define(\'THUPD_GITHUB_TOKEN\', \'your_token_here\');';

        return array(
            'success' => false,
            'message' => sprintf(
                'GitHub API giới hạn truy cập (HTTP 403).<br>'
              . '%s<br>'
              . 'Giải pháp: Đợi khoảng 1 giờ rồi thử lại, hoặc '
              . 'tạo Personal Access Token trên GitHub và thêm dòng sau vào <code>wp-config.php</code>:<br>'
              . '<code>%s</code>',
                esc_html( $rate_msg ),
                $token_hint
            ),
        );
    }

    if ( $code !== 200 ) {
        return array(
            'success' => false,
            'message' => sprintf(
                'GitHub API trả về mã lỗi HTTP <strong>%d</strong>. '
              . 'Vui lòng thử lại sau.',
                $code
            ),
        );
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    $commit_date = isset( $body['commit']['committer']['date'] )
        ? date( 'Y-m-d H:i:s', strtotime( $body['commit']['committer']['date'] ) )
        : 'Không rõ';

    $commit_sha     = isset( $body['sha'] ) ? substr( $body['sha'], 0, 7 ) : 'Không rõ';
    $commit_message = isset( $body['commit']['message'] ) ? $body['commit']['message'] : 'Không rõ';

    return array(
        'success'        => true,
        'commit_sha'     => $commit_sha,
        'commit_date'    => $commit_date,
        'commit_message' => $commit_message,
    );
}

// ====================================================================
// ADMIN MENU
// ====================================================================

add_action( 'admin_menu', 'thupd_add_menu' );
function thupd_add_menu() {
    add_submenu_page(
        'themes.php',
        'Cập nhật Theme',
        'Cập nhật Theme',
        'manage_options',
        'thupd-theme-update',
        'thupd_page_callback'
    );
}

// ====================================================================
// CSS
// ====================================================================

add_action( 'admin_enqueue_scripts', 'thupd_admin_styles' );
function thupd_admin_styles( $hook ) {
    if ( $hook !== 'appearance_page_thupd-theme-update' ) {
        return;
    }
    wp_enqueue_style( 'wp-admin' );
    wp_add_inline_style( 'wp-admin', '
        .thupd-wrap { max-width: 920px; margin-top: 20px; }
        .thupd-wrap .nav-tab-wrapper { margin-bottom: 20px; }
        .thupd-wrap .card { padding: 20px; margin-bottom: 20px; background: #fff; border: 1px solid #c3c4c7; box-shadow: 0 1px 1px rgba(0,0,0,.04); }
        .thupd-wrap .form-table th { width: 200px; }
        .thupd-wrap .button-update { padding: 10px 30px; height: auto; font-size: 16px; font-weight: 600; }
        .thupd-wrap .button-update:disabled { opacity: 0.7; cursor: not-allowed; }
        .thupd-wrap .update-status { margin-top: 20px; padding: 15px; border-radius: 4px; display: none; }
        .thupd-wrap .update-status.success { display: block; background: #ecf7ed; border-left: 4px solid #46b450; }
        .thupd-wrap .update-status.error { display: block; background: #fbeaea; border-left: 4px solid #dc3232; }
        .thupd-wrap .update-status.info { display: block; background: #e5f5fa; border-left: 4px solid #00a0d2; }
        .thupd-wrap .update-status.warning { display: block; background: #fff8e5; border-left: 4px solid #ffb900; }
        .thupd-wrap .update-log { margin-top: 15px; padding: 12px; background: #1d2327; color: #d4d4d4; border: 1px solid #2c3338; border-radius: 4px; max-height: 350px; overflow-y: auto; font-family: Consolas, Monaco, monospace; font-size: 12px; line-height: 1.6; display: none; }
        .thupd-wrap .update-log.active { display: block; }
        .thupd-wrap .update-log .log-info { color: #6bc; }
        .thupd-wrap .update-log .log-success { color: #6c6; }
        .thupd-wrap .update-log .log-error { color: #e66; }
        .thupd-wrap .update-log .log-warning { color: #fa0; }
        .thupd-wrap .spinner-wrapper { display: inline-block; vertical-align: middle; margin-left: 10px; }
        .thupd-wrap .version-info table { border-collapse: collapse; width: 100%; }
        .thupd-wrap .version-info td { padding: 8px 10px; border-bottom: 1px solid #e5e5e5; }
        .thupd-wrap .version-info td:first-child { font-weight: 600; width: 200px; color: #555; }
        .thupd-wrap .thupd-message { padding: 12px 16px; margin: 15px 0; border-radius: 4px; border-left: 4px solid #ccc; }
        .thupd-wrap .thupd-message.success { background: #ecf7ed; border-left-color: #46b450; }
        .thupd-wrap .thupd-message.error { background: #fbeaea; border-left-color: #dc3232; }
        .thupd-wrap .thupd-message.warning { background: #fff8e5; border-left-color: #ffb900; }
        .thupd-wrap .preflight-errors { margin: 15px 0; padding: 0; list-style: none; }
        .thupd-wrap .preflight-errors li { padding: 10px 14px; margin-bottom: 8px; background: #fbeaea; border-left: 4px solid #dc3232; border-radius: 3px; }
        .thupd-wrap .preflight-errors li::before { content: "\26a0\ufe0f "; }
        .thupd-wrap .preflight-ok { padding: 10px 14px; background: #ecf7ed; border-left: 4px solid #46b450; border-radius: 3px; margin: 15px 0; }
    ' );
}

// ====================================================================
// TRANG ADMIN
// ====================================================================

function thupd_page_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Bạn không có quyền truy cập trang này.' );
    }

    $config       = thupd_get_config();
    $active_tab   = isset( $_GET['tab'] ) ? $_GET['tab'] : 'update';
    $save_message = '';
    $save_type    = '';

    if ( isset( $_POST['thupd_save_config'] ) && check_admin_referer( 'thupd_config_action' ) ) {
        thupd_save_config( $_POST );
        $config       = thupd_get_config();
        $save_message = 'Cấu hình đã được lưu thành công.';
        $save_type    = 'success';
    }

    if ( isset( $_POST['thupd_reset_config'] ) && check_admin_referer( 'thupd_config_action' ) ) {
        delete_option( THUPD_OPTION_KEY );
        $config       = thupd_get_config();
        $save_message = 'Cấu hình đã được đặt lại về mặc định.';
        $save_type    = 'warning';
    }

    ?>
    <div class="wrap">
        <h1>Cập nhật Theme</h1>

        <nav class="nav-tab-wrapper">
            <a href="?page=thupd-theme-update&tab=update"  class="nav-tab <?php echo $active_tab === 'update'  ? 'nav-tab-active' : ''; ?>">Cập nhật</a>
            <a href="?page=thupd-theme-update&tab=config" class="nav-tab <?php echo $active_tab === 'config' ? 'nav-tab-active' : ''; ?>">Cấu hình</a>
        </nav>

        <div class="thupd-wrap">
            <?php if ( $active_tab === 'config' ) : ?>

                <div class="card">
                    <h2>Cấu hình GitHub</h2>
                    <p>Cấu hình repository GitHub để tải code theme về. Các giá trị này được lưu trong database và chỉ cần nhập một lần.</p>

                    <?php if ( $save_message ) : ?>
                        <div class="thupd-message <?php echo esc_attr( $save_type ); ?>">
                            <?php echo esc_html( $save_message ); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="">
                        <?php wp_nonce_field( 'thupd_config_action' ); ?>
                        <table class="form-table" role="presentation">
                            <tr>
                                <th scope="row"><label for="github_owner">GitHub Owner</label></th>
                                <td>
                                    <input type="text" id="github_owner" name="github_owner"
                                           value="<?php echo esc_attr( $config['github_owner'] ); ?>" class="regular-text" />
                                    <p class="description">Tên tài khoản hoặc tổ chức trên GitHub. VD: <code>hoanglamcntt</code></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="github_repo">GitHub Repo</label></th>
                                <td>
                                    <input type="text" id="github_repo" name="github_repo"
                                           value="<?php echo esc_attr( $config['github_repo'] ); ?>" class="regular-text" />
                                    <p class="description">Tên repository. VD: <code>ictu</code></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="github_branch">Branch</label></th>
                                <td>
                                    <input type="text" id="github_branch" name="github_branch"
                                           value="<?php echo esc_attr( $config['github_branch'] ); ?>" class="regular-text" />
                                    <p class="description">Branch chứa code theme. VD: <code>main</code> hoặc <code>master</code></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="theme_slug">Theme Directory</label></th>
                                <td>
                                    <input type="text" id="theme_slug" name="theme_slug"
                                           value="<?php echo esc_attr( $config['theme_slug'] ); ?>" class="regular-text" />
                                    <p class="description">Tên thư mục theme trong <code>wp-content/themes/</code>. VD: <code>ictu</code></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="theme_name">Theme Name</label></th>
                                <td>
                                    <input type="text" id="theme_name" name="theme_name"
                                           value="<?php echo esc_attr( $config['theme_name'] ); ?>" class="regular-text" />
                                    <p class="description">Giá trị <code>Theme Name:</code> trong <code>style.css</code>. VD: <code>ICTU</code></p>
                                </td>
                            </tr>
                        </table>
                        <p class="submit">
                            <button type="submit" name="thupd_save_config" class="button button-primary">Lưu cấu hình</button>
                            <button type="submit" name="thupd_reset_config" class="button" onclick="return confirm('Đặt lại cấu hình mặc định?');">Đặt lại mặc định</button>
                        </p>
                    </form>
                </div>

                <div class="card">
                    <h2>Kiểm tra kết nối</h2>
                    <p>Sau khi lưu cấu hình, nhấn nút bên dưới để kiểm tra kết nối đến GitHub.</p>
                    <button type="button" id="thupd-test-btn" class="button">Kiểm tra kết nối GitHub</button>
                    <span class="spinner-wrapper" id="thupd-test-spinner" style="display:none;">
                        <span class="spinner is-active"></span>
                    </span>
                    <div id="thupd-test-result" style="margin-top: 15px; display: none;"></div>
                    <script>
                    jQuery(document).ready(function($) {
                        $('#thupd-test-btn').on('click', function() {
                            var btn = $(this), result = $('#thupd-test-result'), spinner = $('#thupd-test-spinner');
                            btn.prop('disabled', true);
                            spinner.show();
                            result.hide().empty().removeClass('thupd-message success error warning');
                            $.post(ajaxurl, {
                                action: 'thupd_test_connection',
                                _ajax_nonce: '<?php echo wp_create_nonce( 'thupd_test_nonce' ); ?>'
                            }, function(resp) {
                                if (resp.success) {
                                    result.html('<strong>Kết nối thành công!</strong><br>' + resp.data.message)
                                          .addClass('thupd-message success').show();
                                } else {
                                    result.html('<strong>Kết nối thất bại!</strong><br>' + resp.data.message)
                                          .addClass('thupd-message error').show();
                                }
                            }, 'json').fail(function(xhr) {
                                var msg = 'Lỗi AJAX: khong thể kết nối đến máy chủ.';
                                if (xhr.status === 0) msg = 'Lỗi mạng hoặc request bị chặn.';
                                else if (xhr.status === 500) msg = 'Lỗi máy chủ nội bộ (500). Kiểm tra error log.';
                                else msg = 'Lỗi HTTP ' + xhr.status + ': ' + xhr.statusText;
                                result.html('<strong>' + msg + '</strong>').addClass('thupd-message error').show();
                            }).always(function() {
                                btn.prop('disabled', false);
                                spinner.hide();
                            });
                        });
                    });
                    </script>
                </div>

                <div class="card">
                    <h2>Kiểm tra hệ thống</h2>
                    <p>Kiểm tra các điều kiện tiên quyết trước khi thực hiện cập nhật.</p>
                    <button type="button" id="thupd-precheck-btn" class="button">Kiểm tra hệ thống</button>
                    <span class="spinner-wrapper" id="thupd-precheck-spinner" style="display:none;">
                        <span class="spinner is-active"></span>
                    </span>
                    <div id="thupd-precheck-result" style="margin-top: 15px;"></div>
                    <script>
                    jQuery(document).ready(function($) {
                        $('#thupd-precheck-btn').on('click', function() {
                            var btn = $(this), result = $('#thupd-precheck-result'), spinner = $('#thupd-precheck-spinner');
                            btn.prop('disabled', true);
                            spinner.show();
                            result.empty();
                            $.post(ajaxurl, {
                                action: 'thupd_preflight_check',
                                _ajax_nonce: '<?php echo wp_create_nonce( 'thupd_precheck_nonce' ); ?>'
                            }, function(resp) {
                                if (resp.success) {
                                    var html = '';
                                    if (resp.data.errors.length === 0) {
                                        html = '<div class="preflight-ok">Tất cả điều kiện hệ thống đều OK. Bạn có thể thực hiện cập nhật.</div>';
                                    } else {
                                        html = '<ul class="preflight-errors">';
                                        $.each(resp.data.errors, function(i, err) {
                                            html += '<li>' + err + '</li>';
                                        });
                                        html += '</ul>';
                                        html += '<p style="color:#dc3232;">Vui lòng khắc phục các lỗi trên trước khi cập nhật.</p>';
                                    }
                                    result.html(html);
                                } else {
                                    result.html('<div class="thupd-message error">' + resp.data.message + '</div>');
                                }
                            }, 'json').fail(function() {
                                result.html('<div class="thupd-message error">Lỗi kết nối AJAX.</div>');
                            }).always(function() {
                                btn.prop('disabled', false);
                                spinner.hide();
                            });
                        });
                    });
                    </script>
                </div>

            <?php else : ?>

                <?php
                $current_version = thupd_get_current_theme_version();
                $github_info     = thupd_get_github_latest_info( $config );
                $preflight       = thupd_preflight_checks();
                ?>
                <div class="card version-info">
                    <h2>Thông tin phiên bản</h2>
                    <table>
                        <tr><td class="label">Theme hiện tại</td><td><strong><?php echo esc_html( thupd_get_current_theme_slug() ); ?></strong></td></tr>
                        <tr><td class="label">Phiên bản hiện tại</td><td><strong><?php echo esc_html( $current_version ); ?></strong></td></tr>
                        <tr><td class="label">GitHub Repo</td><td><code><?php echo esc_html( $config['github_owner'] . '/' . $config['github_repo'] ); ?></code> (branch <code><?php echo esc_html( $config['github_branch'] ); ?></code>)</td></tr>
                        <?php if ( $github_info['success'] ) : ?>
                            <tr><td class="label">Commit mới nhất</td><td><strong><?php echo esc_html( $github_info['commit_sha'] ); ?></strong></td></tr>
                            <tr><td class="label">Ngày commit</td><td><?php echo esc_html( $github_info['commit_date'] ); ?></td></tr>
                            <tr><td class="label">Nội dung commit</td><td><em><?php echo esc_html( $github_info['commit_message'] ); ?></em></td></tr>
                        <?php else : ?>
                            <tr><td class="label">GitHub</td><td style="color:#dc3232;"><?php echo $github_info['message']; ?></td></tr>
                        <?php endif; ?>
                    </table>
                </div>

                <?php if ( ! empty( $preflight ) ) : ?>
                    <div class="card" style="border-left: 4px solid #dc3232;">
                        <h2 style="color:#dc3232;">Lỗi hệ thống cần khắc phục</h2>
                        <ul class="preflight-errors">
                            <?php foreach ( $preflight as $err ) : ?>
                                <li><?php echo $err; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p>Vào tab <strong>Cấu hình</strong> để kiểm tra chi tiết hoặc liên hệ quản trị viên hosting.</p>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <h2>Thực hiện cập nhật</h2>
                    <p>Nhấn nút bên dưới để tải xuống phiên bản mới nhất từ GitHub và cập nhật theme.</p>
                    <p class="description" style="color:#d63638;">
                        <strong>Lưu ý quan trọng:</strong> Trước khi cập nhật, hãy chắc chắn bạn đã backup theme hiện tại.
                        Quá trình này sẽ thay thế toàn bộ file theme.
                    </p>

                    <button type="button" id="thupd-update-btn" class="button button-primary button-update"
                        <?php echo ! empty( $preflight ) ? 'disabled' : ''; ?>>
                        Cập nhật theme từ GitHub
                    </button>
                    <span class="spinner-wrapper" id="thupd-spinner" style="display:none;">
                        <span class="spinner is-active"></span>
                    </span>

                    <div id="thupd-update-status" class="update-status"></div>
                    <div id="thupd-update-log" class="update-log"></div>
                </div>

                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var btn = $('#thupd-update-btn');
                    var status = $('#thupd-update-status');
                    var log = $('#thupd-update-log');
                    var spinner = $('#thupd-spinner');

                    function addLog(message, type) {
                        type = type || 'info';
                        log.show().addClass('active');
                        log.append('<div class="log-' + type + '">[' + type.toUpperCase() + '] ' + message + '</div>');
                        log.scrollTop(log[0].scrollHeight);
                    }

                    btn.on('click', function(e) {
                        e.preventDefault();
                        if (btn.prop('disabled')) return;

                        btn.prop('disabled', true);
                        btn.text('Dang cap nhat...');
                        spinner.show();
                        status.hide().removeClass('success error info warning');
                        log.empty().hide().removeClass('active');

                        addLog('Bat dau qua trinh cap nhat theme...', 'info');
                        addLog('Kiem tra cau hinh...', 'info');
                        addLog('Dang gui yeu cau tai code tu GitHub...', 'info');

                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'thupd_update_theme',
                                _ajax_nonce: '<?php echo wp_create_nonce( 'thupd_update_theme_nonce' ); ?>'
                            },
                            success: function(response) {
                                if (response.success) {
                                    addLog('Tai xuat va giai nen thanh cong!', 'success');
                                    addLog('Da sao chep file vao thu muc theme.', 'success');
                                    if (response.data.version) {
                                        addLog('Phien ban moi: ' + response.data.version, 'success');
                                    }
                                    addLog('Cap nhat hoan tat!', 'success');

                                    status.show().addClass('success');
                                    status.html('<strong>Thanh cong!</strong> Theme da duoc cap nhat len phien ban <strong>' + (response.data.version || 'moi nhat') + '</strong>.');
                                    status.append('<p><a href="?page=thupd-theme-update" class="button button-primary">Tai lai trang</a></p>');
                                } else {
                                    addLog('That bai! ' + response.data.message, 'error');
                                    status.show().addClass('error');
                                    status.html('<strong>That bai!</strong> ' + response.data.message);
                                    status.append('<p><button onclick="location.reload()" class="button">Thu lai</button></p>');
                                }
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                var msg = textStatus + ': ' + errorThrown;
                                if (xhr.status === 0) msg = 'Khong the ket noi den may chu. Kiem tra mang.';
                                else if (xhr.status === 500) msg = 'Loi may chu noi bo (500). Kiem tra WordPress error log.';
                                else if (xhr.status === 413) msg = 'File tai ve qua lon (413). Gioi han upload cua hosting co the qua thap.';
                                addLog('LOI KET NOI: ' + msg, 'error');
                                status.show().addClass('error');
                                status.html('<strong>Loi ket noi!</strong> ' + msg);
                                status.append('<p><button onclick="location.reload()" class="button">Thu lai</button></p>');
                            },
                            complete: function() {
                                btn.prop('disabled', false);
                                btn.text('Cap nhat theme tu GitHub');
                                spinner.hide();
                            }
                        });
                    });

                    <?php if ( ! empty( $preflight ) ) : ?>
                    status.show().addClass('warning');
                    status.html('<strong>Khong the cap nhat</strong> - Vao tab <strong>Cau hinh</strong> de kiem tra va khac phuc cac loi he thong truoc.');
                    <?php endif; ?>
                });
                </script>

            <?php endif; ?>
        </div>
    </div>
    <?php
}

// ====================================================================
// AJAX: KIỂM TRA KẾT NỐI
// ====================================================================

add_action( 'wp_ajax_thupd_test_connection', 'thupd_ajax_test_connection' );
function thupd_ajax_test_connection() {
    check_ajax_referer( 'thupd_test_nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Không có quyền.' ) );
    }

    $config = thupd_get_config();
    $info   = thupd_get_github_latest_info( $config );

    if ( $info['success'] ) {
        wp_send_json_success( array(
            'message' => sprintf(
                'Repo <strong>%s/%s</strong> (branch <strong>%s</strong>)<br>'
              . 'Commit gần nhất: <strong>%s</strong> (%s)<br>'
              . 'Nội dung: <em>%s</em>',
                esc_html( $config['github_owner'] ),
                esc_html( $config['github_repo'] ),
                esc_html( $config['github_branch'] ),
                esc_html( $info['commit_sha'] ),
                esc_html( $info['commit_date'] ),
                esc_html( $info['commit_message'] )
            ),
        ) );
    } else {
        wp_send_json_error( array( 'message' => $info['message'] ) );
    }
}

// ====================================================================
// AJAX: KIỂM TRA HỆ THỐNG
// ====================================================================

add_action( 'wp_ajax_thupd_preflight_check', 'thupd_ajax_preflight_check' );
function thupd_ajax_preflight_check() {
    check_ajax_referer( 'thupd_precheck_nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Không có quyền.' ) );
    }

    $errors = thupd_preflight_checks();

    wp_send_json_success( array(
        'errors' => $errors,
    ) );
}

// ====================================================================
// AJAX: CẬP NHẬT THEME
// ====================================================================

add_action( 'wp_ajax_thupd_update_theme', 'thupd_ajax_update_theme' );
function thupd_ajax_update_theme() {
    check_ajax_referer( 'thupd_update_theme_nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => 'Bạn không có quyền thực hiện hành động này.' ) );
    }

    $preflight = thupd_preflight_checks();
    if ( ! empty( $preflight ) ) {
        wp_send_json_error( array(
            'message' => 'Lỗi hệ thống:<br>' . implode( '<br>', $preflight ),
        ) );
    }

    $result = thupd_do_update();

    if ( is_wp_error( $result ) ) {
        wp_send_json_error( array( 'message' => $result->get_error_message() ) );
    }

    wp_send_json_success( array(
        'message' => 'Theme đã được cập nhật thành công từ GitHub.',
        'version' => $result,
    ) );
}

// ====================================================================
// XỬ LÝ CẬP NHẬT
// ====================================================================

/**
 * Kiểm tra quyền ghi thực tế trên một thư mục
 */
function thupd_is_really_writable( $dir ) {
    $test_file = $dir . '/.thupd_write_test';
    $result = @file_put_contents( $test_file, 'test' );
    if ( $result === false ) {
        return false;
    }
    @unlink( $test_file );
    return true;
}

/**
 * Thực hiện tải và cập nhật theme
 *
 * @return string|WP_Error
 */
function thupd_do_update() {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

    $config = thupd_get_config();

    $upload_dir = wp_upload_dir();
    $temp_dir   = $upload_dir['basedir'] . '/thupd-temp';

    if ( ! isset( $upload_dir['basedir'] ) || ! is_dir( $upload_dir['basedir'] ) ) {
        return new WP_Error( 'no_upload_dir', 'Không tìm thấy thư mục uploads. Vui lòng kiểm tra cấu hình WordPress.' );
    }

    if ( ! thupd_is_really_writable( $upload_dir['basedir'] ) ) {
        return new WP_Error( 'uploads_not_writable', 'Thư mục uploads không cho phép ghi. CHMOD 755 hoặc 775.' );
    }

    if ( file_exists( $temp_dir ) ) {
        thupd_delete_directory( $temp_dir );
    }
    wp_mkdir_p( $temp_dir );

    if ( ! is_dir( $temp_dir ) ) {
        return new WP_Error( 'cannot_create_temp', 'Không thể tạo thư mục tạm: ' . $temp_dir );
    }

    $zip_file     = $temp_dir . '/theme.zip';
    $download_url = thupd_get_github_download_url( $config );

    // Bước 1: Tải ZIP
    $download_result = download_url( $download_url, 120 );

    if ( is_wp_error( $download_result ) ) {
        thupd_delete_directory( $temp_dir );
        $err_code = $download_result->get_error_code();
        $err_msg  = $download_result->get_error_message();

        if ( $err_code === 'http_404' ) {
            return new WP_Error( 'download_failed',
                'Không tìm thấy repository trên GitHub (HTTP 404). Kiểm tra lại "GitHub Owner" và "GitHub Repo".' );
        }
        if ( $err_code === 'http_403' ) {
            return new WP_Error( 'download_failed',
                'GitHub từ chối truy cập (HTTP 403). Thử lại sau 1 giờ.' );
        }
        if ( strpos( $err_msg, 'size' ) !== false && strpos( $err_msg, 'exceeded' ) !== false ) {
            return new WP_Error( 'download_failed',
                'File ZIP quá lớn vượt quá giới hạn dung lượng của hosting.' );
        }

        return new WP_Error( 'download_failed',
            'Không thể tải file từ GitHub: [' . $err_code . '] ' . $err_msg );
    }

    if ( ! rename( $download_result, $zip_file ) ) {
        @unlink( $download_result );
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'move_failed', 'Không thể di chuyển file đã tải.' );
    }

    // Bước 2: Giải nén
    if ( ! class_exists( 'ZipArchive' ) ) {
        @unlink( $zip_file );
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'no_ziparchive',
            'Máy chủ chưa cài đặt PHP extension ZipArchive. Liên hệ nhà cung cấp hosting.' );
    }

    $unzip_dir = $temp_dir . '/extracted';
    wp_mkdir_p( $unzip_dir );

    $zip      = new ZipArchive();
    $zip_open = $zip->open( $zip_file );

    if ( $zip_open !== true ) {
        $zip_errno = thupd_get_zip_errors();
        $zip_err   = isset( $zip_errno[ $zip_open ] ) ? $zip_errno[ $zip_open ] : 'Mã lỗi: ' . $zip_open;
        @unlink( $zip_file );
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'unzip_failed',
            'Không thể mở file ZIP. ' . $zip_err . '<br>File tải về có thể bị hỏng.' );
    }

    $extract_result = $zip->extractTo( $unzip_dir );
    $zip->close();

    if ( ! $extract_result ) {
        @unlink( $zip_file );
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'extract_failed',
            'Không thể giải nén file ZIP. Ổ đĩa có thể đầy hoặc hết bộ nhớ.' );
    }

    @unlink( $zip_file );

    // Bước 3: Tìm thư mục theme
    $theme_source_dir = thupd_locate_theme_dir( $unzip_dir, $config['theme_name'] );

    if ( ! $theme_source_dir || ! is_dir( $theme_source_dir ) ) {
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'no_theme_found', sprintf(
            'Không tìm thấy thư mục theme <strong>%s</strong> trong file tải về. '
          . 'Kiểm tra lại "Theme Name" trong tab Cấu hình.',
            esc_html( $config['theme_name'] )
        ) );
    }

    $new_theme_data = get_file_data( $theme_source_dir . '/style.css', array( 'Version' => 'Version' ) );
    $new_version    = isset( $new_theme_data['Version'] ) ? $new_theme_data['Version'] : 'unknown';

    // Bước 4: Kiểm tra thư mục đích
    $theme_dir = WP_CONTENT_DIR . '/themes/' . $config['theme_slug'];

    if ( ! is_dir( $theme_dir ) ) {
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'theme_not_found', sprintf(
            'Thư mục theme <code>%s</code> không tồn tại. Kiểm tra lại "Theme Directory".',
            esc_html( $config['theme_slug'] )
        ) );
    }

    if ( ! thupd_is_really_writable( $theme_dir ) ) {
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'theme_not_writable', sprintf(
            'Thư mục theme <code>%s</code> không cho phép ghi. CHMOD thành 755 hoặc 775.',
            esc_html( $config['theme_slug'] )
        ) );
    }

    // Bước 5: Copy đè lên theme hiện tại
    $fs       = new WP_Filesystem_Direct( null );
    $failures = array();
    $ok       = thupd_copy_directory( $theme_source_dir, $theme_dir, $fs, $failures );

    if ( ! $ok ) {
        thupd_delete_directory( $temp_dir );
        $fail_msg = '';
        if ( ! empty( $failures ) ) {
            $fail_msg = 'Các file bị lỗi:<br>';
            foreach ( $failures as $f ) {
                $fail_msg .= '- <code>' . esc_html( $f ) . '</code><br>';
            }
        }
        return new WP_Error( 'copy_failed',
            'Không thể sao chép file vào thư mục theme.<br>' . $fail_msg );
    }

    // Bước 6: Dọn dẹp
    thupd_delete_directory( $temp_dir );

    // Bước 7: Xóa cache WordPress
    wp_cache_flush();

    // Bước 8: Kích hoạt lại theme
    switch_theme( $config['theme_slug'] );

    return $new_version;
}

// ====================================================================
// HÀM TIỆN ÍCH
// ====================================================================

/**
 * Tìm thư mục theme trong cấu trúc ZIP đã giải nén
 */
function thupd_locate_theme_dir( $unzip_dir, $theme_name ) {
    $items = scandir( $unzip_dir );
    foreach ( $items as $item ) {
        if ( $item === '.' || $item === '..' ) {
            continue;
        }
        $full_path = $unzip_dir . '/' . $item;
        if ( ! is_dir( $full_path ) ) {
            continue;
        }
        if ( file_exists( $full_path . '/style.css' ) ) {
            $content = file_get_contents( $full_path . '/style.css' );
            if ( strpos( $content, 'Theme Name: ' . $theme_name ) !== false ) {
                return $full_path;
            }
        }
        $sub = $full_path . '/' . basename( get_template_directory() );
        if ( file_exists( $sub . '/style.css' ) ) {
            return $sub;
        }
        $sub2 = $full_path . '/wp-content/themes/' . basename( get_template_directory() );
        if ( file_exists( $sub2 . '/style.css' ) ) {
            return $sub2;
        }
    }

    return thupd_find_theme_by_name( $unzip_dir, $theme_name );
}

/**
 * Tìm thư mục theme dựa vào nội dung style.css
 */
function thupd_find_theme_by_name( $dir, $theme_name ) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator( $dir, RecursiveDirectoryIterator::SKIP_DOTS ),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ( $iterator as $file ) {
        if ( $file->isFile() && $file->getFilename() === 'style.css' ) {
            $content = file_get_contents( $file->getPathname() );
            if ( strpos( $content, 'Theme Name: ' . $theme_name ) !== false ) {
                return $file->getPath();
            }
        }
    }

    $iterator->rewind();
    foreach ( $iterator as $file ) {
        if ( $file->isFile() && $file->getFilename() === 'style.css' ) {
            $content = @file_get_contents( $file->getPathname() );
            if ( $content !== false && strpos( $content, 'Theme Name:' ) !== false ) {
                return $file->getPath();
            }
        }
    }

    return false;
}

/**
 * Sao chép thư mục đệ quy
 */
function thupd_copy_directory( $source, $dest, $fs, &$failures = array() ) {
    if ( file_exists( $dest ) ) {
        $fs->rmdir( $dest, true );
    }

    if ( ! wp_mkdir_p( $dest ) ) {
        $failures[] = $dest . ' (không thể tạo thư mục)';
        return false;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator( $source, RecursiveDirectoryIterator::SKIP_DOTS ),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ( $iterator as $item ) {
        $dest_path = $dest . '/' . $iterator->getSubPathname();

        if ( $item->isDir() ) {
            if ( ! file_exists( $dest_path ) ) {
                if ( ! wp_mkdir_p( $dest_path ) ) {
                    $failures[] = $dest_path . ' (không thể tạo thư mục)';
                    return false;
                }
            }
        } else {
            if ( ! copy( $item->getPathname(), $dest_path ) ) {
                $failures[] = $iterator->getSubPathname();
                return false;
            }
        }
    }

    return true;
}

/**
 * Xóa thư mục đệ quy
 */
function thupd_delete_directory( $dir ) {
    if ( ! file_exists( $dir ) ) {
        return;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator( $dir, RecursiveDirectoryIterator::SKIP_DOTS ),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ( $iterator as $item ) {
        if ( $item->isDir() ) {
            @rmdir( $item->getPathname() );
        } else {
            @unlink( $item->getPathname() );
        }
    }

    @rmdir( $dir );
}
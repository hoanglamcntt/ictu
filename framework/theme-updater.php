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
    return $theme->get_stylesheet(); // 'ictu', 'mytheme', v.v.
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
            'Accept' => 'application/vnd.github.v3+json',
        ),
    ) );

    if ( is_wp_error( $response ) ) {
        return array(
            'success' => false,
            'message' => 'Không thể kết nối đến GitHub: ' . $response->get_error_message(),
        );
    }

    $code = wp_remote_retrieve_response_code( $response );
    if ( $code !== 200 ) {
        return array(
            'success' => false,
            'message' => 'GitHub API trả về mã lỗi: ' . $code,
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

/**
 * Thêm menu trang cập nhật theme
 */
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
        .thupd-wrap { max-width: 900px; margin-top: 20px; }
        .thupd-wrap .nav-tab-wrapper { margin-bottom: 20px; }
        .thupd-wrap .card { padding: 20px; margin-bottom: 20px; background: #fff; border: 1px solid #c3c4c7; box-shadow: 0 1px 1px rgba(0,0,0,.04); }
        .thupd-wrap .form-table th { width: 200px; }
        .thupd-wrap .button-update { padding: 10px 30px; height: auto; font-size: 16px; font-weight: 600; }
        .thupd-wrap .button-update:disabled { opacity: 0.7; }
        .thupd-wrap .update-status { margin-top: 20px; padding: 15px; border-radius: 4px; display: none; }
        .thupd-wrap .update-status.success { display: block; background: #ecf7ed; border-left: 4px solid #46b450; }
        .thupd-wrap .update-status.error { display: block; background: #fbeaea; border-left: 4px solid #dc3232; }
        .thupd-wrap .update-status.info { display: block; background: #e5f5fa; border-left: 4px solid #00a0d2; }
        .thupd-wrap .update-log { margin-top: 15px; padding: 10px; background: #f1f1f1; border: 1px solid #ccc; max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 12px; line-height: 1.6; display: none; }
        .thupd-wrap .update-log.active { display: block; }
        .thupd-wrap .spinner-wrapper { display: inline-block; vertical-align: middle; margin-left: 10px; }
        .thupd-wrap .version-info table { border-collapse: collapse; width: 100%; }
        .thupd-wrap .version-info td { padding: 8px 10px; border-bottom: 1px solid #e5e5e5; }
        .thupd-wrap .version-info td:first-child { font-weight: 600; width: 200px; color: #555; }
        .thupd-wrap .thupd-message { padding: 10px 15px; margin: 15px 0; border-radius: 4px; }
        .thupd-wrap .thupd-message.success { background: #ecf7ed; border-left: 4px solid #46b450; }
        .thupd-wrap .thupd-message.error { background: #fbeaea; border-left: 4px solid #dc3232; }
    ' );
}

// ====================================================================
// TRANG ADMIN
// ====================================================================

function thupd_page_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Bạn không có quyền truy cập trang này.' );
    }

    $config   = thupd_get_config();
    $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'update';

    // Xử lý lưu cấu hình
    $save_message = '';
    $save_type    = '';
    if ( isset( $_POST['thupd_save_config'] ) && check_admin_referer( 'thupd_config_action' ) ) {
        thupd_save_config( $_POST );
        $config       = thupd_get_config();
        $save_message = 'Cấu hình đã được lưu thành công.';
        $save_type    = 'success';
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

                <!-- ===== TAB CẤU HÌNH ===== -->
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
                        </p>
                    </form>
                </div>

                <!-- Kiểm tra kết nối -->
                <div class="card">
                    <h2>Kiểm tra kết nối</h2>
                    <p>Sau khi lưu cấu hình, bạn có thể kiểm tra kết nối GitHub.</p>
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
                            result.hide().empty().removeClass('thupd-message success error');
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
                            }, 'json').fail(function() {
                                result.html('<strong>Lỗi kết nối AJAX.</strong>').addClass('thupd-message error').show();
                            }).always(function() {
                                btn.prop('disabled', false);
                                spinner.hide();
                            });
                        });
                    });
                    </script>
                </div>

            <?php else : ?>

                <!-- ===== TAB CẬP NHẬT ===== -->
                <?php
                $current_version = thupd_get_current_theme_version();
                $github_info     = thupd_get_github_latest_info( $config );
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
                            <tr><td class="label">Nội dung commit</td><td><?php echo esc_html( $github_info['commit_message'] ); ?></td></tr>
                        <?php else : ?>
                            <tr><td class="label">GitHub</td><td style="color:#dc3232;"><?php echo esc_html( $github_info['message'] ); ?></td></tr>
                        <?php endif; ?>
                    </table>
                </div>

                <div class="card">
                    <h2>Thực hiện cập nhật</h2>
                    <p>Nhấn nút bên dưới để tải xuống phiên bản mới nhất từ GitHub và cập nhật theme.</p>
                    <p class="description">
                        <strong>Lưu ý:</strong> Trước khi cập nhật, hãy đảm bảo bạn đã backup theme hiện tại.
                        Quá trình này sẽ thay thế toàn bộ file theme.
                    </p>

                    <button type="button" id="thupd-update-btn" class="button button-primary button-update">
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

                    function addLog(message) {
                        log.show().addClass('active');
                        log.append('<div>' + message + '</div>');
                        log.scrollTop(log[0].scrollHeight);
                    }

                    btn.on('click', function(e) {
                        e.preventDefault();
                        if (btn.prop('disabled')) return;

                        btn.prop('disabled', true);
                        btn.text('Đang cập nhật...');
                        spinner.show();
                        status.hide().removeClass('success error info');
                        log.empty().hide().removeClass('active');

                        addLog('Bắt đầu quá trình cập nhật theme...');
                        addLog('Đang tải code mới nhất từ GitHub...');

                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'thupd_update_theme',
                                _ajax_nonce: '<?php echo wp_create_nonce( 'thupd_update_theme_nonce' ); ?>'
                            },
                            success: function(response) {
                                if (response.success) {
                                    status.show().addClass('success');
                                    status.html('<strong>Thành công!</strong> ' + response.data.message);
                                    if (response.data.version) {
                                        addLog('Phiên bản mới: ' + response.data.version);
                                    }
                                    addLog('Cập nhật hoàn tất!');
                                    status.append('<p><a href="?page=thupd-theme-update" class="button">Tải lại trang</a></p>');
                                } else {
                                    status.show().addClass('error');
                                    status.html('<strong>Thất bại!</strong> ' + response.data.message);
                                    addLog('LỖI: ' + response.data.message);
                                }
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                status.show().addClass('error');
                                status.html('<strong>Lỗi kết nối!</strong> ' + textStatus + ': ' + errorThrown);
                                addLog('LỖI KẾT NỐI: ' + textStatus + ' - ' + errorThrown);
                            },
                            complete: function() {
                                btn.prop('disabled', false);
                                btn.text('Cập nhật theme từ GitHub');
                                spinner.hide();
                            }
                        });
                    });
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
                'Repo <strong>%s/%s</strong> (branch <strong>%s</strong>) — Commit gần nhất: <strong>%s</strong> (%s)',
                esc_html( $config['github_owner'] ),
                esc_html( $config['github_repo'] ),
                esc_html( $config['github_branch'] ),
                esc_html( $info['commit_sha'] ),
                esc_html( $info['commit_date'] )
            ),
        ) );
    } else {
        wp_send_json_error( array( 'message' => $info['message'] ) );
    }
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
 * Thực hiện tải và cập nhật theme
 *
 * @return string|WP_Error
 */
function thupd_do_update() {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';

    $config = thupd_get_config();

    // Tạo thư mục tạm
    $upload_dir = wp_upload_dir();
    $temp_dir   = $upload_dir['basedir'] . '/thupd-temp';

    if ( file_exists( $temp_dir ) ) {
        thupd_delete_directory( $temp_dir );
    }
    wp_mkdir_p( $temp_dir );

    $zip_file = $temp_dir . '/theme.zip';

    // URL download
    $download_url = thupd_get_github_download_url( $config );

    // === Bước 1: Tải ZIP ===
    $download_result = download_url( $download_url, 120 );

    if ( is_wp_error( $download_result ) ) {
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'download_failed', 'Không thể tải file từ GitHub: ' . $download_result->get_error_message() );
    }

    if ( ! rename( $download_result, $zip_file ) ) {
        @unlink( $download_result );
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'move_failed', 'Không thể di chuyển file tải xuống.' );
    }

    // === Bước 2: Giải nén ===
    $unzip_dir = $temp_dir . '/extracted';
    wp_mkdir_p( $unzip_dir );

    if ( ! class_exists( 'ZipArchive' ) ) {
        @unlink( $zip_file );
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'no_ziparchive', 'Máy chủ không hỗ trợ ZipArchive.' );
    }

    $zip      = new ZipArchive();
    $zip_open = $zip->open( $zip_file );
    if ( $zip_open !== true ) {
        @unlink( $zip_file );
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'unzip_failed', 'Không thể giải nén file ZIP (mã lỗi: ' . $zip_open . ').' );
    }

    $zip->extractTo( $unzip_dir );
    $zip->close();
    @unlink( $zip_file );

    // === Bước 3: Tìm thư mục theme ===
    $theme_source_dir = thupd_locate_theme_dir( $unzip_dir, $config['theme_name'] );

    if ( ! $theme_source_dir || ! is_dir( $theme_source_dir ) ) {
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'no_theme_found', sprintf(
            'Không tìm thấy thư mục theme "%s" trong file tải về. Hãy kiểm tra lại cấu hình "Theme Name".',
            $config['theme_name']
        ) );
    }

    // Đọc phiên bản từ style.css
    $new_theme_data = get_file_data( $theme_source_dir . '/style.css', array( 'Version' => 'Version' ) );
    $new_version    = isset( $new_theme_data['Version'] ) ? $new_theme_data['Version'] : 'unknown';

    // === Bước 4: Copy đè lên theme hiện tại ===
    $theme_dir = WP_CONTENT_DIR . '/themes/' . $config['theme_slug'];

    if ( ! is_dir( $theme_dir ) ) {
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'theme_not_found', sprintf(
            'Thư mục theme "%s" không tồn tại trong wp-content/themes/.',
            $config['theme_slug']
        ) );
    }

    $fs = new WP_Filesystem_Direct( null );
    $ok = thupd_copy_directory( $theme_source_dir, $theme_dir, $fs );

    if ( ! $ok ) {
        thupd_delete_directory( $temp_dir );
        return new WP_Error( 'copy_failed', 'Không thể sao chép file. Vui lòng kiểm tra quyền ghi thư mục theme.' );
    }

    // === Bước 5: Dọn dẹp ===
    thupd_delete_directory( $temp_dir );

    // === Bước 6: Kích hoạt lại theme ===
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
    // Cách 1: Duyệt thư mục gốc
    $items = scandir( $unzip_dir );
    foreach ( $items as $item ) {
        if ( $item === '.' || $item === '..' ) {
            continue;
        }
        $full_path = $unzip_dir . '/' . $item;
        if ( ! is_dir( $full_path ) ) {
            continue;
        }
        // Kiểm tra style.css ngay tại thư mục này
        if ( file_exists( $full_path . '/style.css' ) ) {
            $content = file_get_contents( $full_path . '/style.css' );
            if ( strpos( $content, 'Theme Name: ' . $theme_name ) !== false ) {
                return $full_path;
            }
        }
        // Kiểm tra thư mục con trùng với theme_slug
        $sub = $full_path . '/' . basename( get_template_directory() );
        if ( file_exists( $sub . '/style.css' ) ) {
            return $sub;
        }
        // Kiểm tra thư mục con 'wp-content/themes/{slug}'
        $sub2 = $full_path . '/wp-content/themes/' . basename( get_template_directory() );
        if ( file_exists( $sub2 . '/style.css' ) ) {
            return $sub2;
        }
    }

    // Cách 2: Tìm theo theme_name trong toàn bộ cây thư mục
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

    // Fallback cuối: file style.css nào có "Theme Name:" thì lấy
    foreach ( $iterator as $file ) {
        if ( $file->isFile() && $file->getFilename() === 'style.css' ) {
            $content = file_get_contents( $file->getPathname() );
            if ( strpos( $content, 'Theme Name:' ) !== false ) {
                return $file->getPath();
            }
        }
    }

    return false;
}

/**
 * Sao chép thư mục đệ quy
 */
function thupd_copy_directory( $source, $dest, $fs ) {
    if ( file_exists( $dest ) ) {
        $fs->rmdir( $dest, true );
    }
    wp_mkdir_p( $dest );

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator( $source, RecursiveDirectoryIterator::SKIP_DOTS ),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ( $iterator as $item ) {
        $dest_path = $dest . '/' . $iterator->getSubPathname();
        if ( $item->isDir() ) {
            if ( ! file_exists( $dest_path ) ) {
                wp_mkdir_p( $dest_path );
            }
        } else {
            if ( ! copy( $item->getPathname(), $dest_path ) ) {
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
            rmdir( $item->getPathname() );
        } else {
            unlink( $item->getPathname() );
        }
    }

    rmdir( $dir );
}
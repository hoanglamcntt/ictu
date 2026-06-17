<?php
/**
 * Theme Updater - Tự động cập nhật theme từ GitHub
 *
 * @package ICTU
 */

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Lấy thông tin phiên bản theme hiện tại
 */
function ictu_get_current_theme_version() {
    $theme = wp_get_theme();
    return $theme->get( 'Version' );
}

/**
 * Lấy URL download ZIP từ GitHub repository (branch main)
 */
function ictu_get_github_download_url() {
    return 'https://api.github.com/repos/hoanglamcntt/ictu/zipball/main';
}

/**
 * Lấy thông tin commit mới nhất từ GitHub (để biết phiên bản)
 */
function ictu_get_github_latest_info() {
    $url = 'https://api.github.com/repos/hoanglamcntt/ictu/commits/main';
    
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
    
    // Lấy ngày commit (dùng làm "phiên bản" tham khảo)
    $commit_date = isset( $body['commit']['committer']['date'] ) 
        ? date( 'Y-m-d H:i:s', strtotime( $body['commit']['committer']['date'] ) ) 
        : 'Không rõ';
    
    $commit_sha = isset( $body['sha'] ) ? substr( $body['sha'], 0, 7 ) : 'Không rõ';
    $commit_message = isset( $body['commit']['message'] ) ? $body['commit']['message'] : 'Không rõ';

    return array(
        'success' => true,
        'commit_sha' => $commit_sha,
        'commit_date' => $commit_date,
        'commit_message' => $commit_message,
    );
}

/**
 * Thêm menu trang cập nhật theme vào Admin
 */
add_action( 'admin_menu', 'ictu_add_theme_update_menu' );
function ictu_add_theme_update_menu() {
    add_submenu_page(
        'themes.php',
        'Cập nhật Theme',
        'Cập nhật Theme',
        'manage_options',
        'ictu-theme-update',
        'ictu_theme_update_page_callback'
    );
}

/**
 * Enqueue script cho trang cập nhật
 */
add_action( 'admin_enqueue_scripts', 'ictu_theme_update_scripts' );
function ictu_theme_update_scripts( $hook ) {
    if ( $hook !== 'appearance_page_ictu-theme-update' ) {
        return;
    }
    
    wp_enqueue_style( 'wp-admin' );
    
    wp_add_inline_style( 'wp-admin', '
        .ictu-update-wrap {
            max-width: 800px;
            margin-top: 20px;
        }
        .ictu-update-wrap .card {
            padding: 20px;
            margin-bottom: 20px;
        }
        .ictu-update-wrap .button-update {
            padding: 10px 30px;
            height: auto;
            font-size: 16px;
            font-weight: 600;
        }
        .ictu-update-wrap .button-update:disabled {
            opacity: 0.7;
        }
        .ictu-update-wrap .update-status {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
            display: none;
        }
        .ictu-update-wrap .update-status.success {
            display: block;
            background: #ecf7ed;
            border-left: 4px solid #46b450;
        }
        .ictu-update-wrap .update-status.error {
            display: block;
            background: #fbeaea;
            border-left: 4px solid #dc3232;
        }
        .ictu-update-wrap .update-status.info {
            display: block;
            background: #e5f5fa;
            border-left: 4px solid #00a0d2;
        }
        .ictu-update-wrap .update-log {
            margin-top: 15px;
            padding: 10px;
            background: #f1f1f1;
            border: 1px solid #ccc;
            max-height: 300px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 12px;
            line-height: 1.6;
            display: none;
        }
        .ictu-update-wrap .update-log.active {
            display: block;
        }
        .ictu-update-wrap .spinner-wrapper {
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
        }
        .ictu-update-wrap .version-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .ictu-update-wrap .version-info table {
            border-collapse: collapse;
            width: 100%;
        }
        .ictu-update-wrap .version-info td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e5e5;
        }
        .ictu-update-wrap .version-info td:first-child {
            font-weight: 600;
            width: 180px;
        }
        .ictu-update-wrap .version-info .label {
            color: #555;
        }
    ' );
}

/**
 * Nội dung trang cập nhật theme
 */
function ictu_theme_update_page_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Bạn không có quyền truy cập trang này.' );
    }
    
    $current_version = ictu_get_current_theme_version();
    $github_info = ictu_get_github_latest_info();
    ?>
    <div class="wrap">
        <h1>Cập nhật Theme ICTU</h1>
        
        <div class="ictu-update-wrap">
            <!-- Thông tin phiên bản -->
            <div class="card version-info">
                <h2>Thông tin phiên bản</h2>
                <table>
                    <tr>
                        <td class="label">Phiên bản hiện tại</td>
                        <td><strong><?php echo esc_html( $current_version ); ?></strong></td>
                    </tr>
                    <?php if ( $github_info['success'] ) : ?>
                        <tr>
                            <td class="label">Commit mới nhất (GitHub)</td>
                            <td><strong><?php echo esc_html( $github_info['commit_sha'] ); ?></strong></td>
                        </tr>
                        <tr>
                            <td class="label">Ngày commit</td>
                            <td><?php echo esc_html( $github_info['commit_date'] ); ?></td>
                        </tr>
                        <tr>
                            <td class="label">Nội dung commit</td>
                            <td><?php echo esc_html( $github_info['commit_message'] ); ?></td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <td class="label">GitHub</td>
                            <td style="color:#dc3232;"><?php echo esc_html( $github_info['message'] ); ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
            
            <!-- Nút cập nhật -->
            <div class="card">
                <h2>Thực hiện cập nhật</h2>
                <p>Nhấn nút bên dưới để tải xuống phiên bản mới nhất từ GitHub và cập nhật theme.</p>
                <p class="description">
                    <strong>Lưu ý:</strong> Trước khi cập nhật, hãy đảm bảo bạn đã backup theme hiện tại. 
                    Quá trình này sẽ thay thế toàn bộ file theme.
                </p>
                
                <button type="button" id="ictu-update-btn" class="button button-primary button-update">
                    Cập nhật theme từ GitHub
                </button>
                <span class="spinner-wrapper" id="ictu-spinner" style="display:none;">
                    <span class="spinner is-active"></span>
                </span>
                
                <!-- Kết quả -->
                <div id="ictu-update-status" class="update-status"></div>
                
                <!-- Log -->
                <div id="ictu-update-log" class="update-log"></div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var btn = $('#ictu-update-btn');
        var status = $('#ictu-update-status');
        var log = $('#ictu-update-log');
        var spinner = $('#ictu-spinner');
        
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
                    action: 'ictu_update_theme',
                    _ajax_nonce: '<?php echo wp_create_nonce( 'ictu_update_theme_nonce' ); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        status.show().addClass('success');
                        status.html('<strong>Thành công!</strong> ' + response.data.message);
                        if (response.data.version) {
                            addLog('Phiên bản mới: ' + response.data.version);
                        }
                        addLog('Cập nhật hoàn tất!');
                        status.append('<p><a href="<?php echo admin_url('admin.php?page=ictu-theme-update'); ?>" class="button">Tải lại trang</a></p>');
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
    <?php
}

/**
 * Xử lý AJAX cập nhật theme
 */
add_action( 'wp_ajax_ictu_update_theme', 'ictu_ajax_update_theme' );
function ictu_ajax_update_theme() {
    // Kiểm tra nonce
    check_ajax_referer( 'ictu_update_theme_nonce' );
    
    // Kiểm tra quyền
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array(
            'message' => 'Bạn không có quyền thực hiện hành động này.',
        ) );
    }
    
    // Thực hiện cập nhật
    $result = ictu_do_theme_update();
    
    if ( is_wp_error( $result ) ) {
        wp_send_json_error( array(
            'message' => $result->get_error_message(),
        ) );
    }
    
    wp_send_json_success( array(
        'message' => 'Theme đã được cập nhật thành công từ GitHub.',
        'version' => $result,
    ) );
}

/**
 * Thực hiện quá trình tải và cập nhật theme
 *
 * @return string|WP_Error Phiên bản mới nếu thành công, WP_Error nếu thất bại
 */
function ictu_do_theme_update() {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
    
    // Tạo thư mục tạm
    $upload_dir = wp_upload_dir();
    $temp_dir = $upload_dir['basedir'] . '/ictu-theme-temp';
    
    // Dọn dẹp thư mục tạm nếu đã tồn tại
    if ( file_exists( $temp_dir ) ) {
        ictu_delete_directory( $temp_dir );
    }
    wp_mkdir_p( $temp_dir );
    
    // Đường dẫn file ZIP tạm
    $zip_file = $temp_dir . '/theme.zip';
    
    // URL download từ GitHub
    $download_url = ictu_get_github_download_url();
    
    // === Bước 1: Tải file ZIP ===
    $download_result = download_url( $download_url, 120 );
    
    if ( is_wp_error( $download_result ) ) {
        ictu_delete_directory( $temp_dir );
        return new WP_Error( 'download_failed', 'Không thể tải file từ GitHub: ' . $download_result->get_error_message() );
    }
    
    // Di chuyển file tạm vào thư mục của chúng ta
    if ( ! rename( $download_result, $zip_file ) ) {
        @unlink( $download_result );
        ictu_delete_directory( $temp_dir );
        return new WP_Error( 'move_failed', 'Không thể di chuyển file tải xuống.' );
    }
    
    // === Bước 2: Giải nén ZIP ===
    $unzip_dir = $temp_dir . '/extracted';
    wp_mkdir_p( $unzip_dir );
    
    if ( ! class_exists( 'ZipArchive' ) ) {
        @unlink( $zip_file );
        ictu_delete_directory( $temp_dir );
        return new WP_Error( 'no_ziparchive', 'Máy chủ không hỗ trợ ZipArchive. Vui lòng liên hệ quản trị viên hosting.' );
    }
    
    $zip = new ZipArchive();
    $zip_open = $zip->open( $zip_file );
    
    if ( $zip_open !== true ) {
        @unlink( $zip_file );
        ictu_delete_directory( $temp_dir );
        return new WP_Error( 'unzip_failed', 'Không thể giải nén file ZIP (mã lỗi: ' . $zip_open . ').' );
    }
    
    $zip->extractTo( $unzip_dir );
    $zip->close();
    
    // Xóa file ZIP
    @unlink( $zip_file );
    
    // === Bước 3: Tìm thư mục theme trong thư mục đã giải nén ===
    // GitHub tạo ZIP với cấu trúc: hoanglamcntt-ictu-<commit>/...
    $items = scandir( $unzip_dir );
    $theme_source_dir = null;
    
    foreach ( $items as $item ) {
        if ( $item === '.' || $item === '..' ) continue;
        $full_path = $unzip_dir . '/' . $item;
        if ( is_dir( $full_path ) ) {
            // Kiểm tra xem có chứa style.css (file chính của theme) không
            if ( file_exists( $full_path . '/style.css' ) ) {
                $theme_source_dir = $full_path;
                break;
            }
            // Nếu không, kiểm tra trong thư mục con wp-content/themes/ictu/
            if ( file_exists( $full_path . '/wp-content/themes/ictu/style.css' ) ) {
                $theme_source_dir = $full_path . '/wp-content/themes/ictu';
                break;
            }
            // Kiểm tra thư mục con ictu/
            if ( file_exists( $full_path . '/ictu/style.css' ) ) {
                $theme_source_dir = $full_path . '/ictu';
                break;
            }
        }
    }
    
    // Nếu không tìm thấy, thử tìm file style.css trong thư mục con
    if ( ! $theme_source_dir ) {
        $theme_source_dir = ictu_find_theme_dir( $unzip_dir );
    }
    
    if ( ! $theme_source_dir || ! is_dir( $theme_source_dir ) ) {
        ictu_delete_directory( $temp_dir );
        return new WP_Error( 'no_theme_found', 'Không tìm thấy thư mục theme trong file tải về.' );
    }
    
    // Đọc phiên bản từ style.css mới
    $new_theme_data = get_file_data( $theme_source_dir . '/style.css', array( 'Version' => 'Version' ) );
    $new_version = isset( $new_theme_data['Version'] ) ? $new_theme_data['Version'] : 'unknown';
    
    // === Bước 4: Sao chép file theme mới ===
    $theme_dir = get_template_directory();
    
    // Backup file functions.php cũ (để giữ lại các thiết lập riêng nếu cần)
    // Tuy nhiên, với giải pháp này, chúng ta ghi đè hoàn toàn
    
    $fs = new WP_Filesystem_Direct( null );
    
    // Xóa thư mục theme cũ (trừ file screenshot.png và các file không cần thiết?)
    // Ở đây chúng ta sẽ thay thế toàn bộ
    $copy_result = ictu_copy_directory( $theme_source_dir, $theme_dir, $fs );
    
    if ( ! $copy_result ) {
        ictu_delete_directory( $temp_dir );
        return new WP_Error( 'copy_failed', 'Không thể sao chép file theme mới. Vui lòng kiểm tra quyền ghi của thư mục wp-content/themes/ictu/.' );
    }
    
    // === Bước 5: Dọn dẹp ===
    ictu_delete_directory( $temp_dir );
    
    // === Bước 6: Kích hoạt lại theme để đảm bảo hooks chạy ===
    switch_theme( 'ictu' );
    
    return $new_version;
}

/**
 * Tìm thư mục theme trong cấu trúc đã giải nén
 */
function ictu_find_theme_dir( $dir ) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator( $dir, RecursiveDirectoryIterator::SKIP_DOTS ),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ( $iterator as $file ) {
        if ( $file->isFile() && $file->getFilename() === 'style.css' ) {
            $content = file_get_contents( $file->getPathname() );
            if ( strpos( $content, 'Theme Name: ICTU' ) !== false ) {
                return $file->getPath();
            }
            // Fallback: nếu không tìm thấy "Theme Name: ICTU", coi thư mục chứa style.css là theme
            if ( strpos( $content, 'Theme Name:' ) !== false ) {
                return $file->getPath();
            }
        }
    }
    
    return false;
}

/**
 * Sao chép thư mục (đệ quy)
 */
function ictu_copy_directory( $source, $dest, $fs ) {
    // Xóa thư mục đích trước khi copy
    if ( file_exists( $dest ) ) {
        $fs->rmdir( $dest, true );
    }
    
    // Tạo lại thư mục đích
    wp_mkdir_p( $dest );
    
    // Copy từng file
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
 * Xóa thư mục (đệ quy)
 */
function ictu_delete_directory( $dir ) {
    if ( ! file_exists( $dir ) ) return;
    
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
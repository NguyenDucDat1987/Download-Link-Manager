<?php
if (!defined('ABSPATH')) {
    exit;
}

class DLM_Updater
{
    private $slug;
    private $plugin_file;
    private $username;
    private $repo;
    private $github_response;

    public function __construct($plugin_file, $username, $repo)
    {
        $this->plugin_file = $plugin_file;
        $this->username = $username;
        $this->repo = $repo;
        $this->slug = dirname(plugin_basename($plugin_file));

        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        add_filter('plugins_api', array($this, 'check_info'), 10, 3);
        add_filter('upgrader_source_selection', array($this, 'rename_github_zip'), 10, 4);
    }

    private function get_github_release()
    {
        if (!empty($this->github_response)) {
            return $this->github_response;
        }

        $transient_key = 'dlm_gh_update_' . $this->slug;
        $cached = get_transient($transient_key);

        if ($cached) {
            $this->github_response = $cached;
            return $cached;
        }

        $url = "https://api.github.com/repos/{$this->username}/{$this->repo}/releases/latest";
        $response = wp_remote_get($url, array('sslverify' => false));

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

        if (isset($data->tag_name)) {
            set_transient($transient_key, $data, 12 * HOUR_IN_SECONDS);
            $this->github_response = $data;
            return $data;
        }

        return false;
    }

    public function check_update($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }

        $release = $this->get_github_release();
        if (!$release) {
            return $transient;
        }

        // Remove 'v' prefix if present
        $new_version = ltrim($release->tag_name, 'v');
        $current_version = $transient->checked[$this->slug . '/' . basename($this->plugin_file)];

        if (version_compare($current_version, $new_version, '<')) {
            $obj = new stdClass();
            $obj->slug = $this->slug;
            $obj->new_version = $new_version;
            $obj->url = $release->html_url;
            $obj->package = $release->zipball_url;

            // Prefer assets if available (e.g. pre-built zip)
            if (!empty($release->assets)) {
                foreach ($release->assets as $asset) {
                    if (strpos($asset->content_type, 'zip') !== false) {
                        $obj->package = $asset->browser_download_url;
                        break;
                    }
                }
            }

            $transient->response[$this->slug . '/' . basename($this->plugin_file)] = $obj;
        }

        return $transient;
    }

    public function check_info($false, $action, $args)
    {
        if ('plugin_information' !== $action || $args->slug !== $this->slug) {
            return $false;
        }

        $release = $this->get_github_release();
        if (!$release) {
            return $false;
        }

        $new_version = ltrim($release->tag_name, 'v');

        $obj = new stdClass();
        $obj->name = 'Download Link Manager Pro';
        $obj->slug = $this->slug;
        $obj->version = $new_version;
        $obj->author = '<a href="https://github.com/' . $this->username . '">Dat Nguyen</a>';
        $obj->homepage = $release->html_url;
        $obj->requires = '5.0';
        $obj->tested = '6.9';
        $obj->download_link = $release->zipball_url;

        if (!empty($release->assets)) {
            foreach ($release->assets as $asset) {
                if (strpos($asset->content_type, 'zip') !== false) {
                    $obj->download_link = $asset->browser_download_url;
                    break;
                }
            }
        }

        $obj->sections = array(
            'description' => 'Plugin update managed via GitHub.',
            'changelog' => nl2br($release->body)
        );

        return $obj;
    }

    public function rename_github_zip($source, $remote_source, $upgrader, $hook_extra = null)
    {
        global $wp_filesystem;

        // Only trigger if it's our plugin being updated
        if (isset($hook_extra['plugin']) && $hook_extra['plugin'] === $this->slug . '/' . basename($this->plugin_file)) {
            $path_parts = pathinfo($source);
            $new_path = trailingslashit($path_parts['dirname']) . $this->slug;

            if ($wp_filesystem->move($source, $new_path)) {
                return trailingslashit($new_path);
            }
        }

        return $source;
    }
}

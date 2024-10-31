<?php

/**
 * Downloads remote resources.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

/**
 * Downloads remote resources.
 *
 * This class defines functions that make it easier to deal with downloaded
 * resources, like images, from Podiant.
 *
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Download {
    /**
     * The URL of the resource to download.
     *
     * @since 1.0
     * @access public
     * @var string $url The URL of the resource to download.
     */
    public $url;

    /**
     * The temporary name of the locally-saved download.
     *
     * @since 1.0
     * @access protected
     * @var string $filename The temporary name of the locally-saved download.
     */
    protected $filename;

    /**
     * Instantiates the object.
     *
     * @since 1.0
     * @param string $url The URL of the resource to download.
     */
    public function __construct($url) {
        $this->url = $url;
    }

    /**
     * Saves the downloaded file to disk.
     *
     * @since 1.0
     */
    public function save() {
        if (!$this->filename || !is_file($this->filename)) {
            podiant_log("Downloading $this->url", PODIANT_LOG_DEBUG);
            $result = download_url($this->url);

            if (is_wp_error($result)) {
                throw new Exception($result->get_error_message());
            }

            $this->filename = $result;
        }

        return $this->filename;
    }

    /**
     * Attaches the download to a post.
     *
     * @since 1.0
     * @param integer $post_id The ID of the WP post to attach to.
     * @param string $description The attachment description.
     */
    public function attach($post_id, $description='', $featured=false) {
        $attachment_id = media_sideload_image($this->url, $post_id, $description, 'id');

        if (is_wp_error($attachment_id)) {
            throw new Exception($attachment_id->get_error_message());
        }

        if ($featured) {
            set_post_thumbnail($post_id, $attachment_id);
        }
    }

    /**
     * Cleans up.
     *
     * @since 1.0
     */
    public function __destruct() {
        if ($this->filename && file_exists($this->filename)) {
            try {
                unlink($this->filename);
            } catch (Excepetion $ex) {
                podiant_log(
                    'Error deleting temporary downloaded file.',
                    PODIANT_LOG_ERROR,
                    array('exc' => $ex)
                );
            }
        }
    }
}

<?php
namespace PZAI;
if (!defined('ABSPATH')) exit;

require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-settings.php';
require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-logger.php';
require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-admin.php';
require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-catalog.php';
require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-knowledge.php';
require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-engine.php';
require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-rest.php';
require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-visitor.php';
require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-conversion-tracker.php';
require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-widget.php';
require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-logger.php';

class Plugin {
    private static $instance = null;
    public $settings;
    public $logger;
    public $admin;
    public $catalog;
    public $knowledge;
    public $engine;
    public $rest;
    public $visitor;
    public $widget;

    public static function instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        $this->settings = new Settings();
        $this->logger = new Logger($this->settings);
        $this->catalog = new Catalog();
        $this->knowledge = new Knowledge();
        $this->engine = new Engine($this->catalog, $this->knowledge, $this->settings, $this->logger);
        $this->rest = new Rest($this->engine, $this->settings);
        $this->visitor = new Visitor($this->settings);
        $this->widget = new Widget($this->settings);
        new Conversion_Tracker();
        if (is_admin()) $this->admin = new Admin($this->settings, $this->logger, $this->catalog);
    }
}

  <?php
  defined('_JEXEC') || die('<html><head><script>location.href = location.origin</script></head></html>');

  use Joomla\CMS\Installer\Adapter\TemplateAdapter;
  use Joomla\CMS\Factory;

  /**
   * Installation class to perform additional changes during install/uninstall/update
   */
  class SlothInstallerScript extends \Joomla\CMS\Installer\InstallerScript {
    /**
     * Constructor
     *
     * @param   TemplateAdapter  $adapter  The object responsible for running this script
     */
    public function __construct(TemplateAdapter $adapter) {
      $this->minimumJoomla = '4.0';
      $this->minimumPhp = JOOMLA_MINIMUM_PHP;
    }

    /**
     * Called after any type of action
     *
     * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
     * @param   TemplateAdapter  $adapter  The object responsible for running this script
     *
     * @return  boolean  True on success
     */
    public function postflight($route, TemplateAdapter $adapter) {
      if (in_array($route, ["install", "discover_install", "update"])) {
        if (!@copy(JPATH_ROOT . '/templates/sloth/js/sw.min.js', JPATH_ROOT . '/sw.min.js')) {
          Factory::getApplication()->enqueueMessage('Oops: ', 'Couldn\'t copy the service worker in the root directory');
        }
      }
      if ($route === 'uninstall') {
        if (is_file(JPATH_ROOT . '/sw.min.js')) {
          unlink(JPATH_ROOT . '/sw.min.js');
        }
      }
    }
  }

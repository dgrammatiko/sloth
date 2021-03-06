<?php
namespace Joomla\CMS\Document\Renderer\Html;

\defined('JPATH_PLATFORM') || die;

use Joomla\CMS\Document\DocumentRenderer;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\WebAsset\WebAssetAttachBehaviorInterface;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\HTML\HTMLHelper;

class SlothmetasRenderer extends DocumentRenderer {
  /**
   * Renders the document metas and returns the results as a string
   *
   * @param   string  $head     (unused)
   * @param   array   $params   Associative array of values
   * @param   string  $content  The script
   *
   * @return  string  The output of the script
   *
   * @since   4.0.0
   */
  public function render($head, $params = [], $content = null) {
    // Convert the tagids to titles
    if (isset($this->_doc->_metaTags['name']['tags'])) {
      $tagsHelper = new TagsHelper;
      $this->_doc->_metaTags['name']['tags'] = implode(', ', $tagsHelper->getTagNames($this->_doc->_metaTags['name']['tags']));
    }

    /** @var \Joomla\CMS\Application\CMSApplication $app */
    $app = Factory::getApplication();
    $wa  = $this->_doc->getWebAssetManager();

    // Check for AttachBehavior and web components
    foreach ($wa->getAssets('script', true) as $asset)
    {
      if ($asset instanceof WebAssetAttachBehaviorInterface)
      {
        $asset->onAttachCallback($this->_doc);
      }
    }

    // Trigger the onBeforeCompileHead event
    $app->triggerEvent('onBeforeCompileHead');

    // Add Script Options as inline asset
    $scriptOptions = $this->_doc->getScriptOptions();

    if ($scriptOptions) {
      $prettyPrint = (JDEBUG && \defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : false);
      $jsonOptions = json_encode($scriptOptions, $prettyPrint);
      $jsonOptions = $jsonOptions ? $jsonOptions : '{}';

      $wa->addInlineScript(
        $jsonOptions,
        ['name' => 'joomla.script.options', 'position' => 'before'],
        ['type' => 'application/json', 'class' => 'joomla-script-options new'],
        ['core']
      );
    }

    // Lock the AssetManager
    $wa->lock();

    $buffer = '<meta charset="' . $this->_doc->getCharset() . '">';

    // Generate META tags (needs to happen as early as possible in the head)
    foreach ($this->_doc->_metaTags as $type => $tag) {
      foreach ($tag as $name => $contents) {
        if ($type === 'http-equiv' && !($this->_doc->isHtml5() && $name === 'content-type')) {
          $buffer .= '<meta http-equiv="' . $name . '" content="' . htmlspecialchars($contents, ENT_COMPAT, 'UTF-8') . '">';
        } elseif ($type !== 'http-equiv' && !empty($contents)) {
          $buffer .= '<meta ' . $type . '="' . $name . '" content="' . htmlspecialchars($contents, ENT_COMPAT, 'UTF-8') . '">';
        }
      }
    }

    // Special treatment for child templates
    $originalsiteJsonFile = '/media/templates/site/' . $this->_doc->template . '/site.json';
    if (!is_file(JPATH_ROOT . '/media/templates/site/' . $this->_doc->template . '/site.json')) {
      $originalsiteJsonFile = '/media/templates/site/sloth/site.json';
    }
    $buffer .= '<meta name="description" content="' . htmlspecialchars($this->_doc->getDescription(), ENT_COMPAT, 'UTF-8') . '">';
    $buffer .= '<meta name="generator" content="' . htmlspecialchars($this->_doc->getGenerator(), ENT_COMPAT, 'UTF-8') . '">';
    $buffer .= '<title>' . htmlspecialchars($this->_doc->getTitle(), ENT_COMPAT, 'UTF-8') . '</title>';
    $buffer .= '<meta name="viewport" content="width=device-width,minimum-scale=1">';
    $buffer .= '<link rel="manifest" href="' . $originalsiteJsonFile . '">';
    $buffer .= '<link rel="apple-touch-icon" type="image/png" sizes="180x180" href="'. HTMLHelper::image('apple-touch-icon.png', '', [], true, 1) . '">';

    // Generate link declarations
    foreach ($this->_doc->_links as $link => $linkAtrr) {
      $buffer .= '<link href="' . $link . '" ' . $linkAtrr['relType'] . '="' . $linkAtrr['relation'] . '"';

      if (\is_array($linkAtrr['attribs'])) {
        if ($temp = ArrayHelper::toString($linkAtrr['attribs'])) {
          $buffer .= ' ' . $temp;
        }
      }

      $buffer .= '>';
    }

    return $buffer;
  }
}

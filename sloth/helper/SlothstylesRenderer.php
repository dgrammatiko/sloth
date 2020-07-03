<?php
namespace Joomla\CMS\Document\Renderer\Html;

\defined('JPATH_PLATFORM') || die;

use Joomla\CMS\Document\DocumentRenderer;
use Joomla\CMS\WebAsset\WebAssetItemInterface;

class SlothstylesRenderer extends DocumentRenderer {
	/**
	 * List of already rendered src
	 *
	 * @var array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private $renderedSrc = [];
	private $inlineData = [];

	/**
	 * Renders the document stylesheets and style tags and returns the results as a string
	 *
	 * @param   string  $head     (unused)
	 * @param   array   $params   Associative array of values
	 * @param   string  $content  The script
	 *
	 * @return  string  The output of the script
	 *
	 * @since   4.0.0
	 */
	public function render($head, $params = array(), $content = null) {
		$tab          = $this->_doc->_getTab();
		$buffer       = '';
		$wam          = $this->_doc->getWebAssetManager();
		$assets       = $wam->getAssets('style', true);

		// Get a list of inline assets and their relation with regular assets
		$inlineAssets   = $wam->filterOutInlineAssets($assets);
		$inlineRelation = $wam->getInlineRelation($inlineAssets);

		// Merge with existing styleSheets, for rendering
		$assets = array_merge(array_values($assets), $this->_doc->_styleSheets);

		// Generate stylesheet links
		foreach ($assets as $key => $item) {
			$asset = $item instanceof WebAssetItemInterface ? $item : null;

			// Add href attribute for non Asset item
			if (!$asset) {
				$item['href'] = $key;
			}

			// Check for inline content "before"
			if ($asset && !empty($inlineRelation[$asset->getName()]['before'])) {
				foreach ($inlineRelation[$asset->getName()]['before'] as $itemBefore) {
					$buffer .= $this->renderInlineElement($itemBefore);

					// Remove this item from inline queue
					unset($inlineAssets[$itemBefore->getName()]);
				}
			}

			$buffer .= $this->renderElement($item);

			// Check for inline content "after"
			if ($asset && !empty($inlineRelation[$asset->getName()]['after'])) {
				foreach ($inlineRelation[$asset->getName()]['after'] as $itemBefore) {
					$buffer .= $this->renderInlineElement($itemBefore);

					// Remove this item from inline queue
					unset($inlineAssets[$itemBefore->getName()]);
				}
			}
		}

		// Generate script declarations for assets
		foreach ($inlineAssets as $item) {
			$buffer .= $this->renderInlineElement($item);
		}

		// Generate stylesheet declarations
		foreach ($this->_doc->_style as $type => $contents) {
			// Test for B.C. in case someone still store stylesheet declarations as single string
			if (\is_string($contents)) {
				$contents = [$contents];
			}

			foreach ($contents as $content)
			{
				$buffer .= $this->renderInlineElement(
					[
						'type' => $type,
						'content' => $content,
					]
				);
			}
		}

		if (count($this->inlineData)) {
      $buffer .= '<style>';
      foreach ($this->inlineData as $data) {
        $buffer .= $data;
      }
      $buffer .= '</style>';
    }

		return $buffer;
	}

	/**
	 * Renders the element
	 *
	 * @param   WebAssetItemInterface|array  $item  The element
	 *
	 * @return  string  The resulting string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function renderElement($item) : string {
		$buffer = '';
		$asset  = $item instanceof WebAssetItemInterface ? $item : null;
		$src    = $asset ? $asset->getUri() : ($item['href'] ?? '');

		// Make sure we have a src, and it not already rendered
		if (!$src || !empty($this->renderedSrc[$src])) {
			return '';
		}

		$mediaVersion = $this->_doc->getMediaVersion();

		// Get the attributes and other options
		if ($asset) {
			$attribs     = $asset->getAttributes();
			$version     = $asset->getVersion();

			// Add an asset info for debugging
			if (JDEBUG) {
				$attribs['data-asset-name'] = $asset->getName();

				if ($asset->getDependencies()) {
					$attribs['data-asset-dependencies'] = implode(',', $asset->getDependencies());
				}
			}
		} else {
			$attribs     = $item;
			$version     = isset($attribs['options']['version']) ? $attribs['options']['version'] : '';
		}

		// To prevent double rendering
		$this->renderedSrc[$src] = true;

		// Avoid double rel="", StyleSheet can have only rel="stylesheet"
		unset($attribs['rel']);

		if ($attribs['inline']) {
      $this->inlineData[] = @file_get_contents(JPATH_ROOT . $src);
    } else {
      // Render the element with attributes
      $buffer .= '<link href="' . htmlspecialchars($src) . '" rel="stylesheet"';
      $buffer .= $this->renderAttributes($attribs);
      $buffer .= ' />';
    }

    return $buffer;
	}

	/**
	 * Renders the inline element
	 *
	 * @param   WebAssetItemInterface|array  $item  The element
	 *
	 * @return  string  The resulting string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function renderInlineElement($item) : string {
		$buffer = '';

		if ($item instanceof WebAssetItemInterface) {
			$attribs = $item->getAttributes();
			$content = $item->getOption('content');
		} else {
			$attribs = $item;
			$content = $item['content'] ?? '';

			unset($attribs['content']);
		}

		// Do not produce empty elements
		if (!$content) {
			return '';
		}

		// Add "nonce" attribute if exist
		if ($this->_doc->cspNonce) {
			$attribs['nonce'] = $this->_doc->cspNonce;
		}

		$buffer .= '<style';
		$buffer .= $this->renderAttributes($attribs);
		$buffer .= '>';

		$buffer .= $content;

		$buffer .= '</style>';

		return $buffer;
	}

	/**
	 * Renders the element attributes
	 *
	 * @param   array  $attributes  The element attributes
	 *
	 * @return  string  The attributes string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function renderAttributes(array $attributes) : string {
		$buffer = '';

		$defaultCssMimes = array('text/css');

		foreach ($attributes as $attrib => $value) {
			// Don't add the 'options' attribute. This attribute is for internal use (version, conditional, etc).
			if ($attrib === 'options' || $attrib === 'href') {
				continue;
			}

			// Don't add type attribute if document is HTML5 and it's a default mime type. 'mime' is for B/C.
			if (\in_array($attrib, array('type', 'mime')) && $this->_doc->isHtml5() && \in_array($value, $defaultCssMimes)) {
				continue;
			}

			// Don't add type attribute if document is HTML5 and it's a default mime type. 'mime' is for B/C.
			if ($attrib === 'mime') {
				$attrib = 'type';
			}

			// Add attribute to script tag output.
			$buffer .= ' ' . htmlspecialchars($attrib, ENT_COMPAT, 'UTF-8');

			// Json encode value if it's an array.
			$value = !is_scalar($value) ? json_encode($value) : $value;

			$buffer .= '="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"';
		}

		return $buffer;
	}
}

<?php
namespace TYPO3\CMS\Impexp;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Extension of the page tree class. Used to get the tree of pages to export.
 *
 * @author Kasper Skårhøj <kasperYYYY@typo3.com>
 */
class LocalPageTree extends \TYPO3\CMS\Backend\Tree\View\BrowseTreeView {

	/**
	 * Initialization
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Wrapping title from page tree.
	 *
	 * @param string $title Title to wrap
	 * @param mixed $v (See parent class)
	 * @return string Wrapped title
	 * @todo Define visibility
	 */
	public function wrapTitle($title, $v) {
		$title = trim($title) === '' ? '<em>[' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:labels.no_title', TRUE) . ']</em>' : htmlspecialchars($title);
		return $title;
	}

	/**
	 * Wrapping Plus/Minus icon
	 *
	 * @param string $icon Icon HTML
	 * @param mixed $cmd (See parent class)
	 * @param mixed $bMark (See parent class)
	 * @return string Icon HTML
	 * @todo Define visibility
	 */
	public function PM_ATagWrap($icon, $cmd, $bMark = '') {
		return $icon;
	}

	/**
	 * Wrapping Icon
	 *
	 * @param string $icon Icon HTML
	 * @param array $row Record row (page)
	 * @return string Icon HTML
	 * @todo Define visibility
	 */
	public function wrapIcon($icon, $row) {
		return $icon;
	}

	/**
	 * Select permissions
	 *
	 * @return string SQL where clause
	 * @todo Define visibility
	 */
	public function permsC() {
		return $this->BE_USER->getPagePermsClause(1);
	}

	/**
	 * Tree rendering
	 *
	 * @param integer $pid PID value
	 * @param string $clause Additional where clause
	 * @return array Array of tree elements
	 * @todo Define visibility
	 */
	public function ext_tree($pid, $clause = '') {
		// Initialize:
		$this->init(' AND ' . $this->permsC() . $clause);
		// Get stored tree structure:
		$this->stored = unserialize($this->BE_USER->uc['browseTrees']['browsePages']);
		// PM action:
		$PM = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode('_', \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('PM'));
		// traverse mounts:
		$titleLen = (int)$this->BE_USER->uc['titleLen'];
		$treeArr = array();
		$idx = 0;
		// Set first:
		$this->bank = $idx;
		$isOpen = $this->stored[$idx][$pid] || $this->expandFirst;
		// save ids
		$curIds = $this->ids;
		$this->reset();
		$this->ids = $curIds;
		// Set PM icon:
		$cmd = $this->bank . '_' . ($isOpen ? '0_' : '1_') . $pid;
		$icon = '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($this->backPath, ('gfx/ol/' . ($isOpen ? 'minus' : 'plus') . 'only.gif'), 'width="18" height="16"') . ' align="top" alt="" />';
		$firstHtml = $this->PM_ATagWrap($icon, $cmd);
		if ($pid > 0) {
			$rootRec = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordWSOL('pages', $pid);
			$firstHtml .= $this->wrapIcon(\TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForRecord('pages', $rootRec), $rootRec);
		} else {
			$rootRec = array(
				'title' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'],
				'uid' => 0
			);
			$firstHtml .= $this->wrapIcon('<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($this->backPath, 'gfx/i/_icon_website.gif', 'width="18" height="16"') . ' align="top" alt="" />', $rootRec);
		}
		$this->tree[] = array('HTML' => $firstHtml, 'row' => $rootRec);
		if ($isOpen) {
			// Set depth:
			$depthD = '<img' . \TYPO3\CMS\Backend\Utility\IconUtility::skinImg($this->backPath, 'gfx/ol/blank.gif', 'width="18" height="16"') . ' align="top" alt="" />';
			if ($this->addSelfId) {
				$this->ids[] = $pid;
			}
			$this->getTree($pid, 999, $depthD);
			$idH = array();
			$idH[$pid]['uid'] = $pid;
			if (count($this->buffer_idH)) {
				$idH[$pid]['subrow'] = $this->buffer_idH;
			}
			$this->buffer_idH = $idH;
		}
		// Add tree:
		$treeArr = array_merge($treeArr, $this->tree);
		return $treeArr;
	}

}

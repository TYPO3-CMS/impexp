<?php
namespace TYPO3\CMS\Impexp\Utility;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Utility for import / export
 * Can be used for API access for simple importing of files
 *
 */
class ImportExportUtility {

	/**
	 * Import a T3D file directly
	 *
	 * @param string $file The full absolute path to the file
	 * @param int $pid The pid under which the t3d file should be imported
	 * @throws \ErrorException
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	public function importT3DFile($file, $pid) {
		$importResponse = array();
		if (!is_string($file)) {
			throw new \InvalidArgumentException('Input parameter $file has to be of type string', 1377625645);
		}
		if (!is_int($pid)) {
			throw new \InvalidArgumentException('Input parameter $int has to be of type integer', 1377625646);
		}
		/** @var $import \TYPO3\CMS\Impexp\ImportExport */
		$import = GeneralUtility::makeInstance('TYPO3\\CMS\\Impexp\\ImportExport');
		$import->init(0, 'import');

		$this->emitAfterImportExportInitialisationSignal($import);

		if ($file && @is_file($file)) {
			if ($import->loadFile($file, 1)) {
				// Import to root page:
				$import->importData($pid);
				// Get id of container page:
				$newPages = $import->import_mapId['pages'];
				reset($newPages);
				$importResponse = current($newPages);
			}
		}

		// Check for errors during the import process:
		if (empty($importResponse) && $errors = $import->printErrorLog()) {
			throw new \ErrorException($errors, 1377625537);
		} else {
			return $importResponse;
		}
	}

	/**
	 * Get the SignalSlot dispatcher
	 *
	 * @return \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 */
	protected function getSignalSlotDispatcher() {
		return GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
	}

	/**
	 * Emits a signal after initialization
	 *
	 * @param \TYPO3\CMS\Impexp\ImportExport $import
	 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
	 * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
	 */
	protected function emitAfterImportExportInitialisationSignal(\TYPO3\CMS\Impexp\ImportExport $import) {
		$this->getSignalSlotDispatcher()->dispatch(__CLASS__, 'afterImportExportInitialisation', array($import));
	}
}

<?php
namespace TYPO3\CMS\Impexp\Tests\Functional\Import\GroupFileAndFileReferenceItem;

/*
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

require_once __DIR__ . '/../AbstractImportTestCase.php';

/**
 * Functional test for the ImportExport
 */
class ImportInEmptyDatabaseTest extends \TYPO3\CMS\Impexp\Tests\Functional\Import\AbstractImportTestCase {

	/**
	 * @var array
	 */
	protected $additionalFoldersToCreate = array(
		'/uploads/tx_impexpgroupfiles'
	);

	/**
	 * @var array
	 */
	protected $testExtensionsToLoad = array(
		'typo3/sysext/impexp/Tests/Functional/Fixtures/Extensions/impexp_group_files'
	);

	/**
	 * @var string
	 */
	protected $assertionDataSetDirectory = 'typo3/sysext/impexp/Tests/Functional/Import/GroupFileAndFileReferenceItem/DataSet/Assertion/';

	/**
	 * @test
	 */
	public function importGroupFileAndFileReferenceItem() {

		$this->import->loadFile(__DIR__ . '/../../Fixtures/ImportExportXml/impexp-group-file-and-file_reference-item.xml', 1);
		$this->import->importData(0);

		$this->assertAssertionDataSet('importGroupFileAndFileReferenceItem');

		$this->assertFileEquals(__DIR__ . '/../../Fixtures/Folders/fileadmin/user_upload/typo3_image5.jpg', PATH_site . 'fileadmin/user_upload/typo3_image5.jpg');
		$this->assertFileEquals(__DIR__ . '/../../Fixtures/Folders/uploads/tx_impexpgroupfiles/typo3_image4.jpg', PATH_site . 'uploads/tx_impexpgroupfiles/typo3_image4.jpg');

		$this->testFilesToDelete[] = PATH_site . 'fileadmin/user_upload/typo3_image5.jpg';
		$this->testFilesToDelete[] = PATH_site . 'uploads/tx_impexpgroupfiles/typo3_image4.jpg';

	}

	/**
	 * @test
	 */
	public function importGroupFileAndFileReferenceItemButImagesNotIncluded() {

		$this->import->loadFile(PATH_site . 'typo3/sysext/impexp/Tests/Functional/Fixtures/ImportExportXml/impexp-group-file-and-file_reference-item-but-images-not-included.xml', 1);
		$this->import->importData(0);

		$this->assertAssertionDataSet('importGroupFileAndFileReferenceItem');

		$this->assertFileEquals(__DIR__ . '/../../Fixtures/Folders/fileadmin/user_upload/typo3_image5.jpg', PATH_site . 'fileadmin/user_upload/typo3_image5.jpg');
		$this->assertFileEquals(__DIR__ . '/../../Fixtures/Folders/uploads/tx_impexpgroupfiles/typo3_image4.jpg', PATH_site . 'uploads/tx_impexpgroupfiles/typo3_image4.jpg');

		$this->testFilesToDelete[] = PATH_site . 'fileadmin/user_upload/typo3_image5.jpg';
		$this->testFilesToDelete[] = PATH_site . 'uploads/tx_impexpgroupfiles/typo3_image4.jpg';
	}

}

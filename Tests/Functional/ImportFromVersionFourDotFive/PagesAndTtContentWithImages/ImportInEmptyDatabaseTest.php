<?php
namespace TYPO3\CMS\Impexp\Tests\Functional\ImportFromVersionFourDotFive\PagesAndTtContentWithImages;

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

require_once __DIR__ . '/../../Import/AbstractImportTestCase.php';

/**
 * Functional test for the ImportExport
 */
class ImportInEmptyDatabaseTest extends \TYPO3\CMS\Impexp\Tests\Functional\Import\AbstractImportTestCase {

	protected $assertionDataSetDirectory = 'typo3/sysext/impexp/Tests/Functional/ImportFromVersionFourDotFive/PagesAndTtContentWithImages/DataSet/Assertion/';

	/**
	 * @test
	 */
	public function importPagesAndRelatedTtContentWithImages() {
		$this->import->loadFile(__DIR__ . '/ImportExportXml/pages-and-ttcontent-with-images.xml', 1);
		$this->import->importData(0);

		$this->assertAssertionDataSet('importPagesAndRelatedTtContentWithImages');

		$this->assertFileEquals(__DIR__ . '/../../Fixtures/Folders/fileadmin/user_upload/typo3_image2.jpg', PATH_site . 'fileadmin/user_upload/_imported/typo3_image2.jpg');
		$this->assertFileEquals(__DIR__ . '/../../Fixtures/Folders/fileadmin/user_upload/typo3_image3.jpg', PATH_site . 'fileadmin/user_upload/_imported/typo3_image3.jpg');
		$this->assertFileEquals(__DIR__ . '/../../Fixtures/Folders/fileadmin/user_upload/typo3_image5.jpg', PATH_site . 'fileadmin/user_upload/_imported/fileadmin/user_upload/typo3_image5.jpg');
		$this->assertFileEquals(__DIR__ . '/../../Fixtures/Folders/uploads/tx_impexpgroupfiles/typo3_image4.jpg', PATH_site . 'fileadmin/user_upload/_imported/fileadmin/user_upload/typo3_image4.jpg');

	}

}
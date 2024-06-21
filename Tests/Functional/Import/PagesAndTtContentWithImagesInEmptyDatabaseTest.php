<?php

declare(strict_types=1);

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

namespace TYPO3\CMS\Impexp\Tests\Functional\Import;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Impexp\Import;
use TYPO3\CMS\Impexp\Tests\Functional\AbstractImportExportTestCase;

final class PagesAndTtContentWithImagesInEmptyDatabaseTest extends AbstractImportExportTestCase
{
    #[Test]
    public function importPagesAndRelatedTtContentWithImagesOnCaseSensitiveFilesystems(): void
    {
        $subject = $this->get(Import::class);
        $subject->setPid(0);

        if (!$this->isCaseSensitiveFilesystem()) {
            self::markTestSkipped('Test not available on case insensitive filesystems.');
        }

        $subject->loadFile('EXT:impexp/Tests/Functional/Fixtures/XmlImports/pages-and-ttcontent-with-image.xml');
        $subject->importData();

        $this->testFilesToDelete[] = Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg';

        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/DatabaseAssertions/importPagesAndRelatedTtContentWithImagesOnCaseSensitiveFilesystems.csv');

        self::assertFileEquals(__DIR__ . '/../Fixtures/Folders/fileadmin/user_upload/typo3_image2.jpg', Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg');
    }

    #[Test]
    public function importPagesAndRelatedTtContentWithImagesOnCaseInsensitiveFilesystems(): void
    {
        $subject = $this->get(Import::class);
        $subject->setPid(0);

        if ($this->isCaseSensitiveFilesystem()) {
            self::markTestSkipped('Test not available on case sensitive filesystems.');
        }

        $subject->loadFile('EXT:impexp/Tests/Functional/Fixtures/XmlImports/pages-and-ttcontent-with-image.xml');
        $subject->importData();

        $this->testFilesToDelete[] = Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg';

        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/DatabaseAssertions/importPagesAndRelatedTtContentWithImagesOnCaseInsensitiveFilesystems.csv');

        self::assertFileEquals(__DIR__ . '/../Fixtures/Folders/fileadmin/user_upload/typo3_image2.jpg', Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg');
    }

    #[Test]
    public function importPagesAndRelatedTtContentWithImagesButWithoutStorageOnCaseSensitiveFilesystems(): void
    {
        $subject = $this->get(Import::class);
        $subject->setPid(0);

        if (!$this->isCaseSensitiveFilesystem()) {
            self::markTestSkipped('Test not available on case insensitive filesystems.');
        }

        $subject->loadFile('EXT:impexp/Tests/Functional/Fixtures/XmlImports/pages-and-ttcontent-with-image-without-storage.xml');
        $subject->importData();

        $this->testFilesToDelete[] = Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg';

        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/DatabaseAssertions/importPagesAndRelatedTtContentWithImagesButWithoutStorageOnCaseSensitiveFilesystems.csv');

        self::assertFileEquals(__DIR__ . '/../Fixtures/Folders/fileadmin/user_upload/typo3_image2.jpg', Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg');
    }

    #[Test]
    public function importPagesAndRelatedTtContentWithImagesButWithoutStorageOnCaseInsensitiveFilesystems(): void
    {
        $subject = $this->get(Import::class);
        $subject->setPid(0);

        if ($this->isCaseSensitiveFilesystem()) {
            self::markTestSkipped('Test not available on case sensitive filesystems.');
        }

        $subject->loadFile('EXT:impexp/Tests/Functional/Fixtures/XmlImports/pages-and-ttcontent-with-image-without-storage.xml');
        $subject->importData();

        $this->testFilesToDelete[] = Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg';

        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/DatabaseAssertions/importPagesAndRelatedTtContentWithImagesButWithoutStorageOnCaseInsensitiveFilesystems.csv');

        self::assertFileEquals(__DIR__ . '/../Fixtures/Folders/fileadmin/user_upload/typo3_image2.jpg', Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg');
    }

    #[Test]
    public function importPagesAndRelatedTtContentWithImagesWithSpacesInPath(): void
    {
        $subject = $this->get(Import::class);
        $subject->setPid(0);

        $subject->loadFile('EXT:impexp/Tests/Functional/Fixtures/XmlImports/pages-and-ttcontent-with-image-with-spaces-in-path.xml');
        $subject->importData();

        $this->testFilesToDelete[] = Environment::getPublicPath() . '/fileadmin/user_upload/folder_with_spaces/typo3_image2.jpg';
        $this->testFilesToDelete[] = Environment::getPublicPath() . '/fileadmin/user_upload/folder_with_spaces/typo3_image3.jpg';

        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/DatabaseAssertions/importPagesAndRelatedTtContentWithImagesWithSpacesInPath.csv');

        self::assertFileEquals(__DIR__ . '/../Fixtures/Folders/fileadmin/user_upload/typo3_image2.jpg', Environment::getPublicPath() . '/fileadmin/user_upload/folder_with_spaces/typo3_image2.jpg');
        self::assertFileEquals(__DIR__ . '/../Fixtures/Folders/fileadmin/user_upload/typo3_image3.jpg', Environment::getPublicPath() . '/fileadmin/user_upload/folder_with_spaces/typo3_image3.jpg');
    }

    #[Test]
    public function importPagesAndRelatedTtContentWithImagesButNotIncluded(): void
    {
        $subject = $this->get(Import::class);
        $subject->setPid(0);

        // Files are parallel to the fixture .xml file in a folder - impexp tests for /../ not allowed in path, so we set an absolute path here
        $subject->loadFile('EXT:impexp/Tests/Functional/Fixtures/XmlImports/pages-and-ttcontent-with-image-but-not-included.xml');
        $subject->importData();

        $this->testFilesToDelete[] = Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg';

        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/DatabaseAssertions/importPagesAndRelatedTtContentWithImagesButNotIncluded.csv');

        self::assertFileEquals(__DIR__ . '/../Fixtures/Folders/fileadmin/user_upload/typo3_image2.jpg', Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg');
    }

    #[Test]
    public function importPagesAndRelatedTtContentWithImageWithForcedUids(): void
    {
        $subject = $this->get(Import::class);
        $subject->setPid(0);

        try {
            $subject->loadFile('EXT:impexp/Tests/Functional/Fixtures/XmlImports/pages-and-ttcontent-with-image-with-forced-uids.xml');
            $subject->setForceAllUids(true);
            $subject->importData();
        } catch (\Exception $e) {
        }

        $this->testFilesToDelete[] = Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg';

        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/DatabaseAssertions/importPagesAndRelatedTtContentWithImageWithForcedUids.csv');

        self::assertFileEquals(__DIR__ . '/../Fixtures/Folders/fileadmin/user_upload/typo3_image2.jpg', Environment::getPublicPath() . '/fileadmin/user_upload/typo3_image2.jpg');

        $expectedErrors = [
            'Forcing uids of sys_file records is not supported! They will be imported as new records!',
        ];
        $errors = $subject->getErrorLog();
        self::assertSame($expectedErrors, $errors);
    }

    #[Test]
    public function importPagesAndRelatedTtContentWithImagesAndNewStorage(): void
    {
        GeneralUtility::mkdir(Environment::getPublicPath() . '/fileadmin_invalid_path');

        $subject = $this->get(Import::class);
        $subject->setPid(0);
        $subject->loadFile('EXT:impexp/Tests/Functional/Fixtures/XmlImports/pages-and-ttcontent-with-image-with-invalid-storage.xml');
        $subject->importData();

        self::assertFileEquals(
            __DIR__ . '/../Fixtures/Folders/fileadmin/user_upload/typo3_image2.jpg',
            Environment::getPublicPath() . '/fileadmin_invalid_path/user_upload/typo3_image2.jpg'
        );
    }

    #[Test]
    public function importPagesAndRelatedTtContentWithMissingImageRemovesSysFileReferenceToo(): void
    {
        $subject = $this->get(Import::class);
        $subject->setPid(0);
        try {
            $subject->loadFile('EXT:impexp/Tests/Functional/Fixtures/XmlImports/pages-and-ttcontent-with-missing-image.xml');
            $subject->importData();
        } catch (\Exception $e) {
        }

        $expectedErrors = [
            'Error: No file found for ID 4a705ca3ef43b53dc00de861ba2c86af',
            'Error: sys_file_reference record "1" with relation to sys_file record "1", which is not part of the import data, was not imported.',
            'Lost relation: sys_file_reference:1',
            'Lost relation: sys_file:1',
        ];
        $errors = $subject->getErrorLog();
        self::assertSame($expectedErrors, $errors);
    }
}

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

final class ImagesWithStoragesTest extends AbstractImportExportTestCase
{
    #[Test]
    public function importMultipleImagesWithMultipleStorages(): void
    {
        GeneralUtility::mkdir(Environment::getPublicPath() . '/fileadmin-1');
        GeneralUtility::mkdir(Environment::getPublicPath() . '/fileadmin-3');

        $subject = $this->get(Import::class);
        $subject->setPid(0);
        $subject->loadFile('EXT:impexp/Tests/Functional/Fixtures/XmlImports/images-with-storages.xml');
        $subject->importData();

        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/DatabaseAssertions/importImagesWithStorages.csv');
        self::assertFileExists(Environment::getPublicPath() . '/fileadmin-1/user_upload/typo3_image3.jpg');
        self::assertFileExists(Environment::getPublicPath() . '/fileadmin-3/user_upload/typo3_image2.jpg');
    }

    #[Test]
    public function importImagesWithStaticAndFallbackStorages(): void
    {
        GeneralUtility::mkdir(Environment::getPublicPath() . '/fileadmin_invalid_path');

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/DatabaseImports/sys_file_storages.csv');

        $subject = $this->get(Import::class);
        $subject->setPid(0);
        $subject->loadFile('EXT:impexp/Tests/Functional/Fixtures/XmlImports/images-with-static-and-fallback-storages.xml');
        $subject->importData();

        self::assertFileExists(Environment::getPublicPath() . '/fileadmin_invalid_path/user_upload/typo3_image2.jpg');
        self::assertFileExists(Environment::getPublicPath() . '/fileadmin_invalid_path/user_upload/typo3_image3.jpg');
        self::assertFileExists(Environment::getPublicPath() . '/fileadmin_invalid_path/user_upload/typo3_image5.jpg');
        self::assertFileExists(Environment::getPublicPath() . '/typo3conf/ext/template_extension/Resources/Public/Templates/Empty.html');
    }
}

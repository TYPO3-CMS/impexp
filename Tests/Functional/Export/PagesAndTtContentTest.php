<?php
namespace TYPO3\CMS\Impexp\Tests\Functional\Export;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Impexp\Export;
use TYPO3\CMS\Impexp\Tests\Functional\AbstractImportExportTestCase;

/**
 * Test case
 */
class PagesAndTtContentTest extends AbstractImportExportTestCase
{
    /**
     * @var array
     */
    protected $pathsToLinkInTestInstance = [
            'typo3/sysext/impexp/Tests/Functional/Fixtures/Folders/fileadmin/user_upload' => 'fileadmin/user_upload'
    ];

    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
            'typo3/sysext/impexp/Tests/Functional/Fixtures/Extensions/template_extension'
    ];

    protected function setUp()
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/../Fixtures/DatabaseImports/pages.xml');
        $this->importDataSet(__DIR__ . '/../Fixtures/DatabaseImports/tt_content.xml');
        $this->importDataSet(__DIR__ . '/../Fixtures/DatabaseImports/sys_file.xml');
        $this->importDataSet(__DIR__ . '/../Fixtures/DatabaseImports/sys_file-export-pages-and-tt-content.xml');
    }

    /**
     * @test
     */
    public function exportPagesAndRelatedTtContent()
    {
        $subject = GeneralUtility::makeInstance(Export::class);
        $subject->init();

        $subject->setRecordTypesIncludeFields(
            [
                'pages' => [
                    'title',
                    'deleted',
                    'doktype',
                    'hidden',
                    'perms_everybody'
                ],
                'tt_content' => [
                    'CType',
                    'header',
                    'header_link',
                    'deleted',
                    'hidden',
                    't3ver_oid'
                ],
                'sys_file' => [
                    'storage',
                    'type',
                    'metadata',
                    'identifier',
                    'identifier_hash',
                    'folder_hash',
                    'mime_type',
                    'name',
                    'sha1',
                    'size',
                    'creation_date',
                    'modification_date',
                ],
            ]
        );

        $subject->relOnlyTables = [
                'sys_file',
        ];

        $subject->export_addRecord('pages', BackendUtility::getRecord('pages', 1));
        $subject->export_addRecord('pages', BackendUtility::getRecord('pages', 2));
        $subject->export_addRecord('tt_content', BackendUtility::getRecord('tt_content', 1));
        $subject->export_addRecord('tt_content', BackendUtility::getRecord('tt_content', 2));

        $this->setPageTree($subject, 1, 1);

        // After adding ALL records we set relations:
        for ($a = 0; $a < 10; $a++) {
            $addR = $subject->export_addDBRelations($a);
            if (empty($addR)) {
                break;
            }
        }

        $subject->export_addFilesFromRelations();
        $subject->export_addFilesFromSysFilesRecords();

        $out = $subject->compileMemoryToFileContent('xml');

        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../Fixtures/XmlExports/' . $this->databasePlatform . '/pages-and-ttcontent.xml',
            $out
        );
    }
}

<?php
/*
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Unit6\Worldpay;

use Unit6\HTTP;

/**
 * Test Evidence Instance
 *
 * Check for correct operation of the Evidence class.
 */
class EvidenceTest extends \PHPUnit_Framework_TestCase
{
    private $evidence;

    public function setUp()
    {
    }

    public function tearDown()
    {
        unset($this->evidence);
    }

    public function testEvidenceFileSizeInterval()
    {
        $this->assertEquals(EVIDENCE_SIZE, Evidence::MAX_FILE_SIZE);
        $this->assertEquals(EVIDENCE_INTERVAL, Evidence::MIN_UPLOAD_INTERVAL);
    }

    public function testEvidenceRequiredParameters()
    {
        $params = [
            'documentName' => DOCUMENT_NAME,
            'documentDataInBase64' => DOCUMENT_DATA,
        ];

        foreach (Evidence::$required as $key) {
            $this->assertArrayHasKey($key, $params);
            $this->assertNotEmpty($params[$key]);
        }

        return $params;
    }

    public function testCreateInstanceOfEvidenceWithNoArguments()
    {
        $evidence = new Evidence();

        $this->assertInstanceOf(__NAMESPACE__ . '\Evidence', $evidence);
    }

    public function testCreateInstanceOfEvidenceUseWithMethods()
    {
        $evidence = (new Evidence())
            ->withDocumentName(DOCUMENT_NAME)
            ->withDocumentData(DOCUMENT_DATA);

        $this->assertInstanceOf(__NAMESPACE__ . '\Evidence', $evidence);
    }

    /**
     * @depends testEvidenceRequiredParameters
     */
    public function testCreateInstanceOfEvidence(array $params)
    {
        $evidence = new Evidence($params['documentName'], $params['documentDataInBase64']);

        $this->assertInstanceOf(__NAMESPACE__ . '\Evidence', $evidence);
        $this->assertInstanceOf(__NAMESPACE__ . '\AbstractResource', $evidence);

        return $evidence;
    }

    /**
     * @depends testCreateInstanceOfEvidence
     */
    public function testGetEvidenceDocumentName(Evidence $evidence)
    {
        $this->assertEquals(DOCUMENT_NAME, $evidence->getDocumentName());
    }

    /**
     * @depends testCreateInstanceOfEvidence
     */
    public function testGetEvidenceDocumentData(Evidence $evidence)
    {
        $this->assertEquals(DOCUMENT_DATA, $evidence->getDocumentData());
    }

    /**
     * @depends testCreateInstanceOfEvidence
     */
    public function testSetEvidenceDocumentName(Evidence $evidence)
    {
        $name = 'white.gif';

        $evidence->setDocumentName($name);

        $this->assertEquals($name, $evidence->getDocumentName());
    }

    /**
     * @depends testCreateInstanceOfEvidence
     */
    public function testSetEvidenceDocumentData(Evidence $evidence)
    {
        $data = 'R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

        $evidence->setDocumentData($data);

        $this->assertEquals($data, $evidence->getDocumentData());
    }

    public function testCreateInstanceOfEvidenceWithUploadedFile()
    {
        $file = tempnam('/tmp', uniqid());
        $handle = fopen($file, 'w');
        $size = fwrite($handle, 'foobar');
        fclose($handle);

        $uploadedFile = new HTTP\UploadedFile($file, $name = 'doc.txt', $type = 'text/plain', $size);

        $evidence = (new Evidence())->withFile($uploadedFile);

        $this->assertInstanceOf(__NAMESPACE__ . '\Evidence', $evidence);

        return $evidence;
    }

    /**
     * @depends testCreateInstanceOfEvidenceWithUploadedFile
     */
    public function testGetEvidenceFileInstance(Evidence $evidence)
    {
        $file = $evidence->getFile();

        $this->assertInstanceOf('Unit6\HTTP\UploadedFile', $file);
    }

    /**
     * @depends testCreateInstanceOfEvidence
     */
    public function testGetEvidenceParameters(Evidence $evidence)
    {
        $params = $evidence->getParameters();

        $this->assertArrayHasKey('documentName', $params);
        $this->assertArrayHasKey('documentDataInBase64', $params);
    }
}
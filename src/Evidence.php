<?php
/**
 * This file is part of the Worldpay package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Worldpay;

use InvalidArgumentException;
use UnexpectedValueException;

use Unit6\HTTP\UploadedFile;

/**
 * Evidence for Disputed Order
 *
 * Documented evidence can be supplied for disputed orders.
 */
class Evidence extends AbstractResource
{
    /**
     * Maximum File Size
     *
     * Native size per upload is 4MB or 5.33MB when base64 encoded.
     *
     * @var int Size in bytes
     */
    const MAX_FILE_SIZE = 4000000;

    /**
     * Minimum Upload Interval
     *
     * One upload every 10 minutes.
     *
     * @var int Duration in seconds.
     */
    const MIN_UPLOAD_INTERVAL = 600;

    /**
     * Supported File Extensions
     *
     * @var array
     */
    public static $fileExtensions = [
        'zip', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'tiff', 'pdf', 'txt'
    ];

    /**
     * Required parameters
     *
     * @var array
     */
    public static $required = [
        'documentName',
        'documentDataInBase64'
    ];

    /**
     * Uploaded File
     *
     * Instance of the file that has been uploaded.
     *
     * @var UploadedFile
     */
    protected $file;

    /**
     * Document Name
     *
     * The name of the document that is being uploaded.
     *
     * @var string
     */
    protected $documentName;

    /**
     * Document Data
     *
     * The content of the document that is being uploaded, encoded as Base64.
     *
     * @var string
     */
    protected $documentData;

    /**
     * Create new evidence
     *
     * @param string|null $documentName Document name.
     * @param string|null $documentData Document content encoded as Base64.
     */
    public function __construct($documentName = null, $documentData = null)
    {
        $this->documentName = $documentName;
        $this->documentData = $documentData;
    }

    /**
     * Evidence with Uploaded File
     *
     * @param UploadedFile $file
     *
     * @return self
     */
    public function withFile(UploadedFile $file)
    {
        self::validate($file);

        $stream = $file->getStream();
        $stream->rewind();

        $contents = $stream->getContents();

        $data = base64_encode($contents);
        $name = $file->getClientFilename();

        $clone = clone $this;
        $clone->file = $file;
        $clone->documentName = $name;
        $clone->documentData = $data;

        return $clone;
    }

    /**
     * Evidence with Document Name
     *
     * @param string $name Document name.
     *
     * @return self
     */
    public function withDocumentName($name)
    {
        $clone = clone $this;
        $clone->documentName = $name;

        return $clone;
    }

    /**
     * Set Document Name for Evidence
     *
     * @param string $name Document name.
     *
     * @return void
     */
    public function setDocumentName($name)
    {
        $this->documentName = $name;
    }

    /**
     * Get Evidence Document Name
     *
     * @return string
     */
    public function getDocumentName()
    {
        return $this->documentName;
    }

    /**
     * Evidence with Document Data
     *
     * @param string $data Document content encoded as Base64.
     *
     * @return self
     */
    public function withDocumentData($data)
    {
        $clone = clone $this;
        $clone->documentData = $data;

        return $clone;
    }

    /**
     * Set Document Data for Evidence
     *
     * @param string $data Document content encoded as Base64.
     *
     * @return void
     */
    public function setDocumentData($data)
    {
        $this->documentData = $data;
    }

    /**
     * Get Evidence Document Data
     *
     * @return string
     */
    public function getDocumentData()
    {
        return $this->documentData;
    }

    /**
     * Get Uploaded Evidence File
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get Evidence Parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return [
            'documentName' => $this->getDocumentName(),
            'documentDataInBase64' => $this->getDocumentData(),
        ];
    }

    /**
     * Validate Uploaded Evidence File
     *
     * @param UploadedFile $file
     *
     * @return void
     */
    public static function validate(UploadedFile &$file)
    {
        if ( ! ($file instanceof UploadedFile)) {
            throw new UnexpectedValueException('File must be an UploadedFile instance');
        }

        $size = $file->getSize();

        if (empty($size)) {
            throw new UnexpectedValueException('File cannot be empty');
        }

        if ($size > self::MAX_FILE_SIZE) {
            throw new UnexpectedValueException(sprintf('File size (%d bytes) exeeds maximum (%d bytes)', $size, self::MAX_FILE_SIZE));
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $extension = strtolower($extension);

        if ( ! in_array($extension, self::$fileExtensions)) {
            throw new UnexpectedValueException(sprintf('File extension ("%s") invalid; Permitted extensions: %s', $extension, implode(', ', self::$fileExtensions)));
        }
    }
}
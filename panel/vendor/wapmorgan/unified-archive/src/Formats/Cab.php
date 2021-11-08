<?php
namespace wapmorgan\UnifiedArchive\Formats;

use CabArchive;
use Exception;
use wapmorgan\UnifiedArchive\ArchiveEntry;
use wapmorgan\UnifiedArchive\ArchiveInformation;
use wapmorgan\UnifiedArchive\UnsupportedOperationException;

class Cab extends BasicFormat
{
    /** @var CabArchive */
    protected $cab;

    /**
     * BasicFormat constructor.
     *
     * @param string $archiveFileName
     * @throws Exception
     */
    public function __construct($archiveFileName)
    {
        $this->open($archiveFileName);
    }

    /**
     * Iso format destructor
     */
    public function __destruct()
    {
        $this->cab = null;
    }

    /**
     * @param $archiveFileName
     * @throws Exception
     */
    protected function open($archiveFileName)
    {
        try {
            $this->cab = new CabArchive($archiveFileName);
        } catch (Exception $e) {
            throw new Exception('Could not open Cab archive: '.$e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return ArchiveInformation
     */
    public function getArchiveInformation()
    {
        $information = new ArchiveInformation();
        foreach ($this->cab->getFileNames() as $file) {
            $information->files[] = $file;
            $file_info = $this->cab->getFileData($file);
            $information->uncompressedFilesSize += $file_info->size;
            $information->compressedFilesSize += $file_info->packedSize;
        }
        return $information;
    }

    /**
     * @return array
     */
    public function getFileNames()
    {
        return $this->cab->getFileNames();
    }

    /**
     * @param string $fileName
     *
     * @return bool
     */
    public function isFileExists($fileName)
    {
        return in_array($fileName, $this->cab->getFileNames(), true);
    }

    /**
     * @param string $fileName
     *
     * @return ArchiveEntry|false
     */
    public function getFileData($fileName)
    {
        $data = $this->cab->getFileData($fileName);

        return new ArchiveEntry($fileName, $data->packedSize, $data->size, $data->unixtime, $data->is_compressed);
    }

    /**
     * @param string $fileName
     *
     * @return string|false
     * @throws Exception
     */
    public function getFileContent($fileName)
    {
        return $this->cab->getFileContent($fileName);
    }

    /**
     * @param string $fileName
     *
     * @return bool|resource|string
     * @throws Exception
     */
    public function getFileResource($fileName)
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $this->cab->getFileContent($fileName));
        rewind($resource);
        return $resource;
    }

    /**
     * @param string $outputFolder
     * @param array $files
     *
     * @return false|resource
     * @throws UnsupportedOperationException
     * @throws Exception
     */
    public function extractFiles($outputFolder, array $files)
    {
        return $this->cab->extract($outputFolder, $files);
    }

    /**
     * @param string $outputFolder
     *
     * @return false|resource
     * @throws UnsupportedOperationException
     * @throws Exception
     */
    public function extractArchive($outputFolder)
    {
        return $this->cab->extract($outputFolder);
    }

    /**
     * @param array $files
     *
     * @return false|int
     * @throws UnsupportedOperationException
     */
    public function deleteFiles(array $files)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * @param array $files
     *
     * @return false|int
     * @throws UnsupportedOperationException
     */
    public function addFiles(array $files)
    {
        throw new UnsupportedOperationException();
    }

    /**
     * @param array $files
     * @param string $archiveFileName
     *
     * @return false|int
     * @throws UnsupportedOperationException
     */
    public static function createArchive(array $files, $archiveFileName){
        throw new UnsupportedOperationException();
    }
}
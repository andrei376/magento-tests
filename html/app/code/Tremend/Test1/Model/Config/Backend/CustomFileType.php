<?php

namespace Tremend\Test1\Model\Config\Backend;

use Magento\Framework\App\ObjectManager;

class CustomFileType extends \Magento\Config\Model\Config\Backend\File
{
    const UPLOAD_DIR = 'yourfolder';

    /**
     * @return string[]
     */
    protected function _getAllowedExtensions()
    {
        ObjectManager::getInstance()
            ->get('Psr\Log\LoggerInterface')->error('CustomFileType');

        return ['csv', 'xls', 'exe', 'png', 'jpg'];
    }

    /**
     * Return path to directory for upload file
     *
     * @return string
     * @throw \Magento\Framework\Exception\LocalizedException
     */
    protected function _getUploadDir()
    {
        $path = $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(self::UPLOAD_DIR));
        ObjectManager::getInstance()
            ->get('Psr\Log\LoggerInterface')->error('CustomFileType _getUploadDir=' . $path);

        return $path;
    }

    protected function _addWhetherScopeInfo()
    {
        return true;
    }
}

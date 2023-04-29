<?php
namespace Tremend\Test1\Model\Config\Backend;

class Image extends \Magento\Config\Model\Config\Backend\Image
{
    const UPLOAD_DIR = 'yourfolder';

    /**
     * Getter for allowed extensions of uploaded files.
     *
     * @return string[]
     */
    protected function _getAllowedExtensions()
    {
        \Magento\Framework\App\ObjectManager::getInstance()
            ->get('Psr\Log\LoggerInterface')->debug('Your Message');
        \Magento\Framework\App\ObjectManager::getInstance()
            ->get('Psr\Log\LoggerInterface')->error('Your Error');
        return ['jpg', 'jpeg', 'gif', 'svg'];
    }


    /**
     * Return path to directory for upload file
     *
     * @return string
     * @throw \Magento\Framework\Exception\LocalizedException
     */
    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(self::UPLOAD_DIR));
    }

    protected function _addWhetherScopeInfo()
    {
        return true;
    }


    protected function getTmpFileName()
    {
        $tmpName = null;
        if (isset($_FILES['groups']))
        {
            $tmpName = $_FILES['groups']['tmp_name'][$this->getGroupId()]['fields'][$this->getField()]['value'];
        }
        else
        {
            $tmpName = is_array($this->getValue()) ? $this->getValue()['tmp_name'] : null;
        }
        return $tmpName;
    }
    public function beforeSave()
    {
        $value = $this->getValue();
        $deleteFlag = is_array($value) && !empty($value['delete']);
        if ($this->isTmpFileAvailable($value) && $imageName = $this->getUploadedImageName($value))
        {
            $fileTmpName = $this->getTmpFileName();
            if ($this->getOldValue() && ($fileTmpName || $deleteFlag))
            {
                $this->_mediaDirectory->delete(self::UPLOAD_DIR . '/' . $this->getOldValue());
            }
        }
        return parent::beforeSave();
    }

    private function isTmpFileAvailable($value)
    {
        return is_array($value) && isset($value[0]['tmp_name']);
    }

    private function getUploadedImageName($value)
    {
        if (is_array($value) && isset($value[0]['name']))
        {
            return $value[0]['name'];
        }
        return '';
    }
}

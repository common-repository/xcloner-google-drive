<?php

use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter as GoogleDriveAdapter;

class XCloner_Google_Drive_Adapter extends GoogleDriveAdapter{
	
	public function delete($path){
		
		if(! get_option("xcloner_gdrive_empty_trash",0) ){
			return parent::delete($path);
		}
				
		if ($file = $this->getFileObject($path)) {
            list ($parentId, $id) = $this->splitPath($path);
            if ($parents = $file->getParents()) {
                $file = new Google_Service_Drive_DriveFile();
                $opts = [];
                if (count($parents) > 1) {
                    $opts['removeParents'] = $parentId;
                } 
                
                if ($this->service->files->delete($id, $opts)) {
                    unset($this->cacheFileObjects[$id], $this->cacheHasDirs[$id]);
                    return true;
                }
            }
        }
        return false;
        
	}
	
}

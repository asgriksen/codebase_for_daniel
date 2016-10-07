<?php namespace App\Http\Controllers;

class FileController extends APIBaseController {

	public function upload() {
        $config = new \Flow\Config();
        $config->setTempDir(base_path() . TEMP_PATH . '/');
        $destination = base_path() . UPLOADS_FILE_PATH . '/';
        $file = new \Flow\File($config);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            if ( ! $file->checkChunk() ) {
                return \Response::make('',204);
            }
        } else {
            if ($file->validateChunk()) {
                $file->saveChunk();
            } else {
                return \Response::make('', 400);
            }
        }
        $name = md5(microtime());
        // $url = action('FileController@get', array('name' => $name));
        $url = asset('/uploads/files/' . $name);
        if ($file->validateFile() && $file->save($destination . $name)) {
            return \Response::make($url, 200);
        }
	}

	public function get($name) {
		$target_file = base_path() . UPLOADS_FILE_PATH . '/' . $name;

		if(file_exists($target_file)) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
            $size = filesize($target_file);
			$mimetype = finfo_file($finfo, $target_file);

			header('Content-type: ' . $mimetype);
            header("Content-Transfer-Encoding: binary");
            header('Accept-Ranges: bytes');

            /* The three lines below basically make the download non-cacheable */
            header("Cache-control: private");
            header('Pragma: private');
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

			// multipart-download and download resuming support
            if(isset($_SERVER['HTTP_RANGE']))
            {
                list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
                list($range) = explode(",",$range,2);
                list($range, $range_end) = explode("-", $range);
                $range=intval($range);
                if(!$range_end) {
                    $range_end=$size-1;
                } else {
                    $range_end=intval($range_end);
                }

                $new_length = $range_end-$range+1;
                header("HTTP/1.1 206 Partial Content");
                header("Content-Length: $new_length");
                header("Content-Range: bytes $range-$range_end/$size");
            } else {
                $new_length=$size;
                header("Content-Length: ".$size);
            }

            /* output the file itself */
            $chunksize = 1*(1024*1024); //you may want to change this
            $bytes_send = 0;
            if ($file = fopen($target_file, 'r'))
            {
                if(isset($_SERVER['HTTP_RANGE']))
                    fseek($file, $range);

                while(!feof($file) && (!connection_aborted()) && ($bytes_send<$new_length)){
                    $buffer = fread($file, $chunksize);
                    print($buffer); //echo($buffer); // is also possible
                    flush();
                    $bytes_send += strlen($buffer);
                }
                fclose($file);
            } else die('Error - can not open file.');

			exit();
		}
		else {
			return $this->setError(['name' => 'file not found'])->error(null, 404);
		}
	}

}
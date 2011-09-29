<?php

interface iIcmsStream {

	function __construct(&$parent);

	function canWrite();
	function canRead();
	function canCreate();

	function stream_open();
	function stream_close();
	function stream_read($count);
	function stream_write($data);
	function stream_eof();
	function stream_tell();
	function stream_seek($offset,$whence);
	function stream_stat();
	function stream_set_option ( int $option , int $arg1 , int $arg2);
	function stream_lock($mode);
	function stream_flush();
	function stream_cast ( int $cast_as );
	function rmdir ();
	
	function dir_opendir();
	function dir_closedir();
	function dir_readdir();
	function dir_rewinddir();
	function unlink($path);

	function url_stat($path,$flags);	

}

?>
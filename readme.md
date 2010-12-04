Huaners
=======

This is a simple backup script to upload files and sql dumps to Amazon S3.

Help
----
 
Options are really simple
 
./huaners.php -d <directory>

Creates just a dump of the directory and SQL if enabled in the file.

./huaners.php -d <directory> -w 

Creates a dump of a WordPress install where it gathers the SQL details from wp-config.php fle. For quick and easy wordpress backups before upgrades or what not.

Just add -t flag to have huaners upload to S3 using the details.

License
-------
Copyright (c) 2010, Iain Cambridge
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the 
following conditions are met:

* Redistributions of source code must retain the above copyright notice, this list of conditions and the following 
disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following 
disclaimer in the documentation and/or other materials provided with the distribution.
* Neither the name of the Iain Cambridge nor the names of its contributors may be used to endorse or promote products
derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, 
INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR 
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE 
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. 
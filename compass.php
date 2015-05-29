<?php
    $dirScan = new DirScan();
    
    /**
    * Directory scanner
    * 
    * Scans the directory, displays the files and 
    * file details (the size, last modification) 
    *
    * @author jkmas <jkmasg@gmail.com>
    * @version 0.9.2
    * @access public
    * @todo Method scanDir have more than one cycle
    */
    class DirScan {

        const VERSION = '0.9.2';

        //Arrays with directories and files
        public $dirs = [];         
        public $files = [];

        //Breadcrumbs
        public $breadCrumbs = [];

        //Message
        public $message = null;

        /**
        * Constructor
        * @access public
        */
        public function __construct() {
            $this->scanDir();        
            $this->makeBreadCrumbs();
        }

        /**
        * Allow scan only subdirectories
        * @access public
        * @param String $path Path
        */
        public function controlPermissions($path){
            //if possible threat, redirect
            if(preg_match("/.*\.\..*/", $path)){
                header('Location: ' . ".", true, 302);
                die();
            }
        }

        /**
        * Scan directory, fill arrays
        * @access public
        * @todo Finish checking, if the directory exists  
        */
        public function scanDir(){
            //search __DIR__ or __DIR__ with GET parameters							
            $search = !empty($_GET['dir']) ? __DIR__.$_GET['dir'] : __DIR__;
                
            $this->controlPermissions($search); 
            
            //Check if it is really directory
            if (!is_dir($search)){
                echo "<b class='warn'>Warning: No such directory!</b>";
                return;
            }
            
            $output = scandir($search);            

            //set "." (actual directory) to null
            $output[0] = null;

            for ($i=0;$i<count($output);$i++){
                //find directory (parameters from $search with / and find result)
                if(is_Dir($search.DIRECTORY_SEPARATOR.$output[$i]) && $output[$i]!== null){
                    $dirPath = "?dir=".urlencode($this->__getDirPaths());
                    //remove last directory
                    if ($output[$i] === ".."){
                        $dirname = !empty($_GET['dir']) ? dirname($_GET['dir']) : null;
                        //if is only / or GET is null change to nothing
                        $dirPath = $dirname==DIRECTORY_SEPARATOR | empty($dirname) ? '' : "?dir=".urlencode($dirname);             
                    } else {
                        //if is not "..", we want path to new directory ($output)
                        $dirPath = $dirPath.urlencode(DIRECTORY_SEPARATOR.$output[$i]);
                    }                      
                    $this->dirs[$output[$i]] = $dirPath;
                    $output[$i] = null;
                }
            }

            foreach ($output as $value) {
                if (!empty($value)){         
                    $path = $this->__getFilePaths().$value;   
                    $relativePath = $this->__getRelativeFilePath().$value;

                    //get size of file and datetime of last modified
                    $fileSize = filesize($relativePath);   

                    //get permissions
                    $perms = $this->showPermission($relativePath);

                    //not calculate 0
                    $size = $fileSize===0 ? "0B" : $this->unitsConversion($fileSize);
                    $lastModified = date("F d Y H:i:s", filemtime($relativePath));

                    $this->files[$value] = [$path,$size,$perms,$lastModified]; 
                }
            }
        }  

        /**
        * Make breadcrumbs for better navigation
        * @access public 
        */
        public function makeBreadCrumbs(){
            $this->breadCrumbs = !empty($_GET['dir']) ? explode("/",$_GET['dir']) : [""];
        }

        /**
        * Make size of file human friendly
        * @access public
        * @param int $size Size of file
        * @return String size of file with units
        */
        public function unitsConversion($size){
            //log 
            $base = log($size, 1024);
            $units = ['B','KB', 'MB', 'GB', 'TB'];
            //round for searching in array, maximum TB
            $round = floor($base)<5 ? floor($base) : 4;
            //size divided by 1024^(floor($base))
            return round($size/pow(1024, $round), 2).$units[$round];
        }

        /**
        * Get permission in this form rwxrwxrwx
        * @access public
        * @param string $path Path to file/directory
        * @return Permission
        */
        public function showPermission($path){
            $perms = ["---","--x","-w-",
                      "-wx","r--","r-x",
                      "rw-","rwx"];
            $get = substr(sprintf('%o', fileperms($path)), -4);
            return $perms[$get[1]].$perms[$get[2]].$perms[$get[3]];
        }

        /**
        * GETTERS of paths of files and directories, relative paths from PHP running script
        * @access public
        */
        public function __getFilePaths(){
            //Set server REQUEST_URI without compass.php.*
            $replacePath = !empty($_GET['dir']) ? $_GET['dir'].DIRECTORY_SEPARATOR : DIRECTORY_SEPARATOR;
            return preg_replace("/\/compass\.php.*/", 
                                 $replacePath, 
                                 $_SERVER['REQUEST_URI']);
        }
        public function __getDirPaths(){
            //If isset $_GET, return path of directory
            return $dirPath = !empty($_GET['dir']) ? $_GET['dir'] : null;        
        }
        public function __getRelativeFilePath(){
            //Return path from running PHP script
            return empty($_GET['dir']) ? 
                   ".".DIRECTORY_SEPARATOR : 
                   ".".DIRECTORY_SEPARATOR.$_GET['dir'].DIRECTORY_SEPARATOR;
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- Metadata -->
        <meta charset="UTF-8" />
        <meta name="author" content="JkmAS"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <!-- Title -->
        <title>Directory compass</title>
        <link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono' rel='stylesheet' type='text/css'>
        <style type="text/css">
            body{
                font-family: 'Ubuntu Mono';
                background-color: #330033;
                color: white; 
                min-width: 320px;        
            }       
            table{
                width: 100%;			
                border-collapse: collapse;
                border: none;
            }
            @media screen and (min-device-width: 800px) {
                table {
                    margin: 5% 25% auto;
                    width: 50%;
                }
            }
            tbody tr:hover{
                background-color: #4d004d;
            }
            caption{
                text-align: left;
            }
            @media screen and (max-device-width: 800px) {
                th:nth-last-child(1), td:nth-last-child(1){
                    display: none;
                }
            }
            td:nth-child(1n+2){
                text-align: center;
            }
            .dir td:first-child{
                color: #6699cc;
                font-weight: bold;
            }
            .file td:first-child{
                color: #9fcf72;
                font-weight: bold;
            }
            footer{
                position: absolute;
                bottom: 0;
                left: 20%;
                right: 20%;
                height: 50px;
                text-align: center;			
            }
            @media screen and (max-device-width: 800px){
                footer{ 
                    display: none;
                }
            }
            @media screen and (max-device-height: 400px){
                footer{ 
                    display: none;
                }                
            }
            footer span{
                color: red;
            }
            footer a{
                color: white;
            }
            footer a:hover{
                text-decoration: none;
            }
            tbody{
                cursor: pointer;
            }
            td:nth-child(3n){
                cursor: help;
            }
            .warn{
                color: #E8DE2A;
            }
        </style>
    </head>
    <body>
        <noscript>The application requires JavaScript enabled!</noscript>
        <table>
            <caption>
                <?php foreach($dirScan->breadCrumbs as $crumb): ?>
                <b><?= htmlspecialchars($crumb,ENT_QUOTES) ?></b>/
                <?php endforeach; ?>            
            </caption>
            <thead>
                <tr>
                    <th></th>
                    <th>Size</th>
                    <th>Permissions</th>
                    <th>Last modified</th>
                </tr>
            </thead>
            <tbody>                           
                <?php foreach ($dirScan->dirs as $name => $path): ?> 
                    <?php if ($name === null): ?>
                        <?php continue ?> 
                    <?php endif; ?>      
                    <tr class="dir" data-href="compass.php<?= $path ?>" onclick="search(this); return false;">
                        <td>                               
                            <?php if ($name === ".."): ?>
                                <?= "&#8624;.." ?>
                            <?php else: ?>
                                <?= htmlspecialchars($name,ENT_QUOTES) ?>
                            <?php endif; ?>    
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                <?php endforeach; ?>
                       
                <?php foreach ($dirScan->files as $name => $details): ?> 
                    <tr class="file" data-href="<?= htmlspecialchars($details[0], ENT_QUOTES) ?>" onclick="search(this); return false;">
                        <td><?= htmlspecialchars($name,ENT_QUOTES) ?></td>
                        <td><?= htmlspecialchars($details[1],ENT_QUOTES) ?></td>
                        <td title="owner-group-other users (r-read, w-write, x-execute)"><?= htmlspecialchars($details[2],ENT_QUOTES) ?></td>
                        <td><?= htmlspecialchars($details[3],ENT_QUOTES) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 
        <footer>
            <small>Made with <span>&hearts;</span> by <a href="//jkmas.cz">JkmAS</a></small>
        </footer> 
	<script>
            <!--
            function search(element) {
                window.location.href = element.getAttribute("data-href");
            }
            //-->
	</script>     
    </body>
</html>
	

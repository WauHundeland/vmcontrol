# VMControl - Control your VirtualBox VMs from a web browser

## Requirements
  - Microsoft Windows XP or newer
  - Oracle VM VirtualBox
  - VBoxManage (included in VirtualBox)
  - PHP 7.3+

**Important**: 
Added VMs must have a PIIX4 IDE controller with a optical disk slot!

![image](https://user-images.githubusercontent.com/66002359/140609107-c3a4462b-bfb6-485a-819d-b354f00c8735.png)

Aditionally, added VMS must be created with the user that runs the web server. 



## Setup

Step 1: Preparing the environment

First, create a database. Currently only MySQL is supported. 
Then, execute this SQL Script in a database. 

~~~sql
START TRANSACTION;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passwort` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vorname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nachname` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `vm` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);
  
ALTER TABLE `vm`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `vm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;
~~~

For example, you can use phpMyAdmin to create a database and configure it. 
![image](https://user-images.githubusercontent.com/66002359/140606985-f8961e94-d620-4646-85d6-27518826a462.png)
![image](https://user-images.githubusercontent.com/66002359/140607111-dbe07827-87f5-44c0-a95d-a206aae37450.png)


Now you can download the repo and upload it to your web server using PHP. Make sure that you have installed VirtualBox in the path `C:\Program Files\Oracle\VirtualBox\`.
After uploading it to a web server, you can configure it. Take a look at `conf.php`. In this file is the config located. You must edit following line: 
~~~php
$pdo = new PDO('mysql:host=localhost;dbname=// database name', '// user', '// pass');
~~~
Replace the placeholders with your database name, database user and database password. 
Now you must setup a isofolder. Backslash `\` must be escaped to `\\`. 
~~~php
$isofolder = "C:\\uploaded\\iso\\files\\will\\stored\\here\\";
~~~
The last setting controls your server connection ip. This will be used to generate batch commands. 
~~~php
$ip = "123.456.789.0";
~~~

Step 2: Adding VMs

A user account system is used to assign virtual machines to individual users. The website asks you to create a user account or to log in. The user data is stored in the database table `users`. Create user accounts on the website and look up the associated IDs in the database. Now create an entry for the virtual machine in the database table `vm`. 
~~~sql
INSERT INTO `vm` (`name`, `userid`) VALUES ('your VirtualBox VM name', 'your user id')
~~~

CustoMS
========

The CustoMS CMS source code.

Once uploaded to your server and database has been created (database structure can be found in data/sql/db.sql), connect to the database with the config in lib/classes/Database.class.php. Next, go to [yoursite.com]/index.php?action=admin_sign_up and create an admin account. The necessary init method will run and it is ready to go!

NOTE: the contents of the app directory goes inside your root html directory and everything else goes one directory up, so as to not allow access via HTTP.
NOTE: If you experience a 500 Internal Server Error when you attempt to load the app, try recreating the .htaccess file.

Enjoy!
Ashley Menhennett